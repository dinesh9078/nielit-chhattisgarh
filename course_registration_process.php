<?php
session_start();
require_once 'db.php';

// Set JSON header for AJAX responses
header('Content-Type: application/json');

// Function to generate unique application ID
function generateApplicationId() {
    return 'APP' . date('Y') . sprintf('%06d', rand(1, 999999));
}

// Function to handle file upload
function handleFileUpload($file, $directory, $applicationId, $fileType) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, $allowedTypes)) {
        throw new Exception("Invalid file type for $fileType. Only JPG, PNG, and PDF files are allowed.");
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception("File size too large for $fileType. Maximum size is 5MB.");
    }
    
    // Create unique filename
    $fileName = $applicationId . '_' . $fileType . '_' . time() . '.' . $fileExtension;
    $uploadPath = "uploads/registrations/$directory/" . $fileName;
    
    // Create directory if it doesn't exist
    $fullDir = dirname($uploadPath);
    if (!file_exists($fullDir)) {
        mkdir($fullDir, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $uploadPath;
    } else {
        throw new Exception("Failed to upload $fileType");
    }
}

// Function to validate required fields
function validateRequiredFields($data, $requiredFields) {
    $errors = [];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            $errors[] = ucwords(str_replace('_', ' ', $field)) . " is required";
        }
    }
    return $errors;
}

// Main processing
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Validate required fields
    $requiredFields = [
        'fullName', 'fatherName', 'category', 'bloodGroup', 'gender', 
        'dob', 'phone', 'parentPhone', 'aadhaar'
    ];
    
    $errors = validateRequiredFields($_POST, $requiredFields);
    if (!empty($errors)) {
        throw new Exception('Missing required fields: ' . implode(', ', $errors));
    }
    
    // Generate unique application ID
    $applicationId = generateApplicationId();

    // Get course information from URL parameter / POST
    $courseCode = $_POST['courseCode'] ?? '';
    $courseName = $_POST['courseName'] ?? '';

    // If course info not in POST, try to get from database
    if (empty($courseName) && !empty($courseCode)) {
        $stmt = $pdo->prepare("SELECT title FROM courses WHERE course_code = ?");
        $stmt->execute([$courseCode]);
        $course = $stmt->fetch();
        $courseName = $course ? $course['title'] : 'Unknown Course';
    }

    // Duplicate check: same Aadhaar and course code should not register again
    if (!empty($_POST['aadhaar']) && !empty($courseCode)) {
        $dupStmt = $pdo->prepare("SELECT COUNT(*) FROM course_registrations WHERE aadhaar_number = :aadhaar AND course_code = :course_code");
        $dupStmt->execute([
            ':aadhaar' => $_POST['aadhaar'],
            ':course_code' => $courseCode,
        ]);
        $existing = (int)$dupStmt->fetchColumn();
        if ($existing > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ]);
            exit;
        }
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Handle file uploads
    $uploadedFiles = [];
    try {
        $uploadedFiles['passport_photo'] = handleFileUpload($_FILES['passportPhoto'] ?? null, 'photos', $applicationId, 'photo');
        $uploadedFiles['signature'] = handleFileUpload($_FILES['signature'] ?? null, 'signatures', $applicationId, 'signature');
        $uploadedFiles['thumb_print'] = handleFileUpload($_FILES['thumbPrint'] ?? null, 'thumbprints', $applicationId, 'thumbprint');
        $uploadedFiles['category_certificate'] = handleFileUpload($_FILES['categoryCert'] ?? null, 'certificates', $applicationId, 'category_cert');
        $uploadedFiles['aadhaar_document'] = handleFileUpload($_FILES['aadhaarUpload'] ?? null, 'documents', $applicationId, 'aadhaar');
    } catch (Exception $e) {
        // Clean up any uploaded files on error
        foreach ($uploadedFiles as $file) {
            if ($file && file_exists($file)) {
                unlink($file);
            }
        }
        throw $e;
    }
    
    
    // Insert main registration data
    $sql = "INSERT INTO course_registrations (
        application_id, course_code, course_name,
        full_name, father_name, mother_name, category, tribe_community,
        blood_group, identification_mark, gender, date_of_birth,
        email, phone, parent_phone, aadhaar_number, differently_abled,
        perm_address_line1, perm_city, perm_state, perm_district, perm_pincode,
        corr_address_line1, corr_city, corr_state, corr_district, corr_pincode,
        passport_photo, signature, thumb_print, category_certificate, aadhaar_document
    ) VALUES (
        :application_id, :course_code, :course_name,
        :full_name, :father_name, :mother_name, :category, :tribe_community,
        :blood_group, :identification_mark, :gender, :date_of_birth,
        :email, :phone, :parent_phone, :aadhaar_number, :differently_abled,
        :perm_address_line1, :perm_city, :perm_state, :perm_district, :perm_pincode,
        :corr_address_line1, :corr_city, :corr_state, :corr_district, :corr_pincode,
        :passport_photo, :signature, :thumb_print, :category_certificate, :aadhaar_document
    )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':application_id' => $applicationId,
        ':course_code' => $courseCode,
        ':course_name' => $courseName,
        ':full_name' => $_POST['fullName'],
        ':father_name' => $_POST['fatherName'],
        ':mother_name' => $_POST['motherName'] ?? null,
        ':category' => $_POST['category'],
        ':tribe_community' => $_POST['tribe'] ?? null,
        ':blood_group' => $_POST['bloodGroup'],
        ':identification_mark' => $_POST['idMark'] ?? null,
        ':gender' => $_POST['gender'],
        ':date_of_birth' => $_POST['dob'],
        ':email' => $_POST['email'] ?? null,
        ':phone' => $_POST['phone'],
        ':parent_phone' => $_POST['parentPhone'],
        ':aadhaar_number' => $_POST['aadhaar'],
        ':differently_abled' => $_POST['differentlyAbled'] ?? 'no',
        ':perm_address_line1' => $_POST['permAddress1'] ?? null,
        ':perm_city' => $_POST['permCity'] ?? null,
        ':perm_state' => $_POST['permState'] ?? null,
        ':perm_district' => $_POST['permDistrict'] ?? null,
        ':perm_pincode' => $_POST['permPincode'] ?? null,
        ':corr_address_line1' => $_POST['corrAddress1'] ?? null,
        ':corr_city' => $_POST['corrCity'] ?? null,
        ':corr_state' => $_POST['corrState'] ?? null,
        ':corr_district' => $_POST['corrDistrict'] ?? null,
        ':corr_pincode' => $_POST['corrPincode'] ?? null,
        ':passport_photo' => $uploadedFiles['passport_photo'],
        ':signature' => $uploadedFiles['signature'],
        ':thumb_print' => $uploadedFiles['thumb_print'],
        ':category_certificate' => $uploadedFiles['category_certificate'],
        ':aadhaar_document' => $uploadedFiles['aadhaar_document']
    ]);
    
    $registrationId = $pdo->lastInsertId();
    
    // Handle academic qualifications
    if (isset($_POST['academics']) && is_array($_POST['academics'])) {
        $academicStmt = $pdo->prepare("
            INSERT INTO academic_qualifications (
                registration_id, exam_passed, board_university, year_of_passing, 
                percentage_cgpa, marksheet_document
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($_POST['academics'] as $index => $academic) {
            if (!empty($academic['examPassed']) && !empty($academic['boardUniversity'])) {
                // Handle marksheet upload if exists
                $marksheetPath = null;
                if (isset($_FILES['marksheets']) && isset($_FILES['marksheets'][$index])) {
                    try {
                        $marksheetPath = handleFileUpload(
                            $_FILES['marksheets'][$index], 
                            'marksheets', 
                            $applicationId, 
                            'marksheet_' . ($index + 1)
                        );
                    } catch (Exception $e) {
                        // Continue without marksheet if upload fails
                        error_log("Marksheet upload failed: " . $e->getMessage());
                    }
                }
                
                $academicStmt->execute([
                    $registrationId,
                    $academic['examPassed'],
                    $academic['boardUniversity'],
                    $academic['yearOfPassing'] ?? null,
                    $academic['percentageCgpa'] ?? null,
                    $marksheetPath
                ]);
            }
        }
    }
    
    // Log the application submission
    $logStmt = $pdo->prepare("
        INSERT INTO application_logs (registration_id, action, new_status, notes, created_by) 
        VALUES (?, 'submitted', 'pending', 'Application submitted by student', 'system')
    ");
    $logStmt->execute([$registrationId]);
    
    // Commit transaction
    $pdo->commit();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Application submitted successfully!',
        'applicationId' => $applicationId,
        'registrationId' => $registrationId
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
    
    // Clean up uploaded files on error
    if (isset($uploadedFiles)) {
        foreach ($uploadedFiles as $file) {
            if ($file && file_exists($file)) {
                unlink($file);
            }
        }
    }
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

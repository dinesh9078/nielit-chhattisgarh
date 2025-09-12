# NIELIT Chhattisgarh Course Registration System

A comprehensive course registration system for NIELIT Chhattisgarh with admin panel, student portal, and automated duplicate checking.

## ✨ Features

### Student Features
- Course browsing and registration
- Student login/registration
- Personal dashboard
- Application tracking
- **🔒 Duplicate enrollment prevention** - Same Aadhaar cannot enroll in the same course twice

### Admin Features
- Admin dashboard with analytics
- Course management (CRUD operations)
- Student registration management
- Notice management
- Real-time statistics

### System Features
- Responsive design with Tailwind CSS
- Secure authentication
- Duplicate registration prevention
- RESTful API endpoints
- MySQL database integration

## 🆕 Recent Updates

### Latest Features (v1.1)
- ✅ **Duplicate Enrollment Prevention**: Implemented Aadhaar-based duplicate checking
- ✅ **Enhanced Student Dashboard**: Improved navigation and user flow
- ✅ **Test Suite**: Added comprehensive testing pages
- ✅ **UI/UX Improvements**: Better navigation and user experience

## 🚀 Quick Setup

### Prerequisites
- XAMPP/WAMP server
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/krompx/nielit-chhattisgarh.git
   cd nielit-chhattisgarh
   ```

2. **Start XAMPP services**
   - Start Apache
   - Start MySQL

3. **Create database configuration**
   - Copy `db.php.example` to `db.php`
   - Update database credentials

4. **Initialize the database**
   - Visit: `http://localhost/nielit-chhattisgarh/setup.php`
   - This will create all necessary tables and seed data

5. **Access the application**
   - **Home**: `http://localhost/nielit-chhattisgarh/`
   - **Admin Panel**: `http://localhost/nielit-chhattisgarh/admin_login.php`
     - Username: `admin`
     - Password: `admin123`
   - **Test Duplicate Prevention**: `http://localhost/nielit-chhattisgarh/test_duplicate_check.php`

## 📁 Project Structure

```
nielit-chhattisgarh/
├── api/                          # API endpoints
│   ├── admin_courses.php         # Course management API
│   ├── admin_notices.php         # Notice management API
│   ├── admin_registrations.php   # Registration data API
│   └── get_courses.php           # Public course data
├── admin_dashboard.php           # Admin panel dashboard
├── admin_login.php               # Admin authentication
├── course.php                    # Course browsing page
├── course_registration_process.php # Registration processing with duplicate check
├── student_dashboard.php         # Student portal with improved navigation
├── student_login.php             # Student authentication
├── index.php                     # Homepage
├── setup.php                     # Database setup
├── test_duplicate_check.php      # Test duplicate prevention system
└── assets/                       # Images and static files
```

## 🔑 Key Features Implemented

### 🔒 Duplicate Prevention System
- **Aadhaar-based validation**: Prevents same Aadhaar number from enrolling in identical courses
- **Real-time checking**: Validation occurs during registration process
- **Error handling**: Clear error messages for duplicate attempts
- **Test interface**: Comprehensive testing page at `test_duplicate_check.php`

**How it works:**
1. During registration, system checks existing enrollments
2. Compares Aadhaar number + course code combination
3. Blocks duplicate enrollments with clear error message
4. Allows same Aadhaar for different courses

### 🎯 Enhanced Student Experience
- **Improved navigation**: NIELIT logo now links to homepage
- **Better user flow**: "Apply for Course" buttons redirect to course browsing
- **Responsive design**: Works seamlessly on all devices
- **Clear feedback**: Informative success/error messages

### 📊 Admin Dashboard
- Real-time statistics and analytics
- Course management with CRUD operations
- Student registration oversight
- Notice management system
- Registration reports and data export

## 🔌 API Endpoints

- `GET /api/get_courses.php` - Fetch available courses
- `GET /api/get_notices.php` - Fetch system notices
- `GET /api/admin_registrations.php` - Admin registration data
- `POST /course_registration_process.php` - Process registrations (with duplicate check)

## 🗄️ Database Schema

### Main Tables
- `students` - Student information and credentials
- `courses` - Available courses with details
- `registrations` - Student course enrollments (with Aadhaar tracking)
- `notices` - System-wide notices
- `admin_users` - Admin accounts and permissions

### Key Fields for Duplicate Prevention
- `registrations.aadhaar_number` - Links to student Aadhaar
- `registrations.course_code` - Course identifier
- Unique constraint on (aadhaar_number, course_code) combination

## 🔐 Security Features

- ✅ Session-based authentication
- ✅ SQL injection prevention with prepared statements
- ✅ Input validation and sanitization
- ✅ Secure password handling
- ✅ Protected admin routes
- ✅ Aadhaar data protection and masking

## 🧪 Testing & Quality Assurance

### Test Pages Available
- **`test_duplicate_check.php`** - Test duplicate enrollment prevention
  - View all registrations (masked Aadhaar)
  - See duplicate reports
  - Test registration with various scenarios
- **`test_registration_system.php`** - Test registration flow
- **`test_login_flow.php`** - Test authentication systems

### Testing Scenarios
1. **Valid Registration**: New Aadhaar + new course = ✅ Success
2. **Duplicate Prevention**: Same Aadhaar + same course = ❌ Error
3. **Multiple Courses**: Same Aadhaar + different course = ✅ Success

## 🔧 Development Workflow

### Branch Structure
- `main` - Production-ready code
- `feature/duplicate-prevention-and-ui-improvements` - Latest enhancements

### Recent Commits
- Enhanced duplicate prevention system
- Improved student dashboard navigation
- Added comprehensive testing suite
- Updated UI/UX elements

## 🚀 Deployment

### Production Checklist
- [ ] Update database credentials in `db.php`
- [ ] Configure proper file permissions
- [ ] Enable HTTPS
- [ ] Set up regular database backups
- [ ] Configure error logging
- [ ] Test all registration flows

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature-name`
3. Make your changes
4. Test thoroughly using provided test pages
5. Submit a pull request

### Coding Standards
- Use prepared statements for all database queries
- Sanitize all user inputs
- Follow PSR coding standards
- Include comments for complex logic
- Test all new features

## 🆘 Troubleshooting

### Common Issues

**Database Connection Error**
- Check `db.php` configuration
- Ensure MySQL service is running
- Verify database name and credentials

**Duplicate Check Not Working**
- Run `test_duplicate_check.php` to verify
- Check database constraints
- Verify Aadhaar field population

**Login Issues**
- Clear browser cache and cookies
- Check session configuration
- Verify user credentials in database

## 📄 License

This project is developed for NIELIT Chhattisgarh and is intended for educational and administrative purposes.

## 📞 Support

For technical support or queries:
- **Email**: support@nielit.gov.in
- **Phone**: +91 XXX-XXX-XXXX
- **Hours**: Mon-Fri, 9 AM - 6 PM

## 📋 Changelog

### v1.1 (Current)
- Added duplicate enrollment prevention
- Enhanced student dashboard navigation
- Improved user experience flow
- Added comprehensive test suite
- Updated documentation

### v1.0
- Initial release
- Basic registration system
- Admin panel
- Student portal

---

**Last Updated**: January 2025  
**Version**: 1.1.0  
**Repository**: [github.com/krompx/nielit-chhattisgarh](https://github.com/krompx/nielit-chhattisgarh)

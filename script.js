// Dynamic year
document.getElementById('year').textContent = new Date().getFullYear();

// Typewriter effect
const words = ["Shaping Future Innovators 🚀", "AI Experts 🤖", "Cyber Defenders 🔐", "Cloud Builders ☁️"];
let wordIndex = 0, charIndex = 0;
const typewriterEl = document.getElementById('typewriter');
function typeWriter() {
  if (charIndex < words[wordIndex].length) {
    typewriterEl.textContent += words[wordIndex].charAt(charIndex);
    charIndex++;
    setTimeout(typeWriter, 100);
  } else {
    setTimeout(() => {
      typewriterEl.textContent = "";
      charIndex = 0;
      wordIndex = (wordIndex + 1) % words.length;
      typeWriter();
    }, 2000);
  }
}
typeWriter();

// AOS init
AOS.init({ duration: 1000, once: true });

// Counter Animation
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
  const animate = () => {
    const target = +counter.getAttribute('data-target');
    const count = +counter.innerText;
    const increment = target / 200;
    if (count < target) {
      counter.innerText = Math.ceil(count + increment);
      setTimeout(animate, 30);
    } else counter.innerText = target;
  };
  animate();
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', e => {
    e.preventDefault();
    document.querySelector(anchor.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
  });
});

// Navbar scroll effect
window.addEventListener("scroll", () => {
  const navbar = document.querySelector(".glass-navbar");
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});

// Particles.js
function initParticles() {
  particlesJS("particles-js", {
    "particles": {
      "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
      "color": { "value": "#ffffff" },
      "shape": { "type": "circle" },
      "opacity": { "value": 0.5 },
      "size": { "value": 3, "random": true },
      "line_linked": { "enable": true, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1 },
      "move": { "enable": true, "speed": 2, "out_mode": "out" }
    },
    "interactivity": {
      "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" } },
      "modes": { "grab": { "distance": 140, "line_linked": { "opacity": 1 } }, "push": { "particles_nb": 4 } }
    },
    "retina_detect": true
  });
}
initParticles();
const schools = [
  {
    name: "Swami Atmanand Govt. English School Pipariya",
    folder: "images/schools/pipariya/",
    status: "completed"
  },
  {
    name: "Swami Atmanand Govt. English School Bodla",
    folder: "images/schools/bodla/",
    status: "ongoing"
  }
];

const container = document.querySelector("#schools .row");

schools.forEach(school => {
  const col = document.createElement("div");
  col.className = "col-md-3 col-sm-6 text-center";
  col.innerHTML = `
    <a href="${school.folder}" target="_blank">
      <img src="school-icon.png" alt="${school.name}" class="img-fluid mb-2" style="max-width:80px;">
      <h6>${school.name}</h6>
    </a>
    <span class="badge ${school.status === "completed" ? "bg-success" : "bg-warning"} mt-2">
      ${school.status === "completed" ? "Training Completed" : "Ongoing"}
    </span>
  `;
  container.appendChild(col);
});

// ---------- NIELIT Chhattisgarh — Main Script ----------

document.addEventListener("DOMContentLoaded", () => {
  setYear();
  initTypewriter();
  initAOS();
  initCounters();
  initSmoothScroll();
  initNavbarScroll();
  initParticles();
  renderSchools();
});

// ---------- Utilities ----------

function setYear() {
  const y = document.getElementById("year");
  if (y) y.textContent = new Date().getFullYear();
}

function initTypewriter() {
  const words = [
    "Shaping Future Innovators 🚀",
    "AI Experts 🤖",
    "Cyber Defenders 🔐",
    "Cloud Builders ☁️"
  ];
  const el = document.getElementById("typewriter");
  if (!el) return;

  el.classList.add("cursor"); // 👈 add blinking effect

  let wi = 0, ci = 0;

  (function type() {
    if (ci < words[wi].length) {
      el.textContent += words[wi].charAt(ci++);
      setTimeout(type, 100);
    } else {
      setTimeout(() => {
        el.textContent = "";
        ci = 0;
        wi = (wi + 1) % words.length;
        type();
      }, 2000);
    }
  })();
}


// AOS animations
function initAOS() {
  if (typeof AOS !== "undefined") {
    AOS.init({ duration: 1000, once: true });
  }
}

// Counters
function initCounters() {
  const counters = document.querySelectorAll(".counter");
  counters.forEach(counter => {
    const animate = () => {
      const target = +counter.getAttribute("data-target");
      const count = +counter.innerText;
      const inc = Math.max(1, Math.floor(target / 200));
      if (count < target) {
        counter.innerText = Math.min(target, count + inc);
        setTimeout(animate, 30);
      } else {
        counter.innerText = target;
      }
    };
    animate();
  });
}

// Smooth scroll (only for real in-page anchors)
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    const href = a.getAttribute("href");
    if (href && href.length > 1) {
      a.addEventListener("click", e => {
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: "smooth" });
        }
      });
    }
  });
}

// Navbar style on scroll
function initNavbarScroll() {
  const navbar = document.querySelector(".glass-navbar");
  if (!navbar) return;
  const onScroll = () =>
    window.scrollY > 50
      ? navbar.classList.add("scrolled")
      : navbar.classList.remove("scrolled");
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });
}

// Particles background
function initParticles() {
  if (typeof particlesJS === "undefined") return;
  const holder = document.getElementById("particles-js");
  if (!holder) return;

  particlesJS("particles-js", {
    particles: {
      number: { value: 80, density: { enable: true, value_area: 800 } },
      color: { value: "#ffffff" },
      shape: { type: "circle" },
      opacity: { value: 0.5 },
      size: { value: 3, random: true },
      line_linked: {
        enable: true,
        distance: 150,
        color: "#ffffff",
        opacity: 0.4,
        width: 1
      },
      move: { enable: true, speed: 2, out_mode: "out" }
    },
    interactivity: {
      events: {
        onhover: { enable: true, mode: "grab" },
        onclick: { enable: true, mode: "push" }
      },
      modes: {
        grab: { distance: 140, line_linked: { opacity: 1 } },
        push: { particles_nb: 4 }
      }
    },
    retina_detect: true
  });
}

// Schools grid
function renderSchools() {
  const container = document.querySelector("#schools .row");
  if (!container) return;

  const schools = [
    { name: "Swami Atmanand Govt. English School Pipariya", status: "completed" },
    { name: "Swami Atmanand Govt. English School Bodla", status: "ongoing" },
    { name: "Swami Atmanand Govt. English Medium School Basna", status: "completed" },
    { name: "Swami Atmanand Utkrisht English School Lawan", status: "ongoing" },
    { name: "Swami Atmanand Utkrisht School Tumgaon", status: "completed" },
    { name: "Swami Atmanand Utkrisht School Kharod", status: "ongoing" },
    { name: "Swami Atmanand Govt. English Medium School Pithora", status: "completed" },
    { name: "Swami Atmanand Govt. English Medium School Kasdol", status: "ongoing" },
    { name: "Swami Atmanand Govt. English Medium School Simga", status: "completed" },
    { name: "Swami Atmanand Govt. English School D.K.P Kota", status: "ongoing" }
  ];

  const visibleInitial = 15; // 3 rows of 5
  const makeBadge = s =>
    `<span class="badge ${s === "completed" ? "bg-success" : "bg-warning"}">
       ${s === "completed" ? "Training Completed" : "Ongoing"}
     </span>`;

  const draw = limit => {
    container.innerHTML = "";
    schools.slice(0, limit).forEach(s => {
      const col = document.createElement("div");
      col.className = "col-5th";
      col.innerHTML = `
        <i class="fas fa-school fa-3x text-primary mb-2"></i>
        <h6 class="fw-bold">${s.name}</h6>
        ${makeBadge(s.status)}
      `;
      container.appendChild(col);
    });
  };

  draw(visibleInitial);

  if (schools.length > visibleInitial) {
    const btn = document.createElement("button");
    btn.className = "show-more-btn";
    btn.textContent = "Show More";
    container.parentElement.appendChild(btn);
    btn.addEventListener("click", () => {
      draw(schools.length);
      btn.remove();
    });
  }
}
// Initialize AOS for scroll animations
document.addEventListener("DOMContentLoaded", () => {
  AOS.init({
    duration: 1000, // Animation duration in milliseconds
    once: true, // Trigger animation only once when it comes into view
  });
});

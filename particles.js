const canvas = document.getElementById("particlesCanvas");
const ctx = canvas.getContext("2d");

let width = canvas.width = window.innerWidth;
let height = canvas.height = window.innerHeight;

const particles = [];
const PARTICLE_COUNT = 150;

// initialize particles
for (let i = 0; i < PARTICLE_COUNT; i++) {
  particles.push({
    x: Math.random() * width,
    y: Math.random() * height,
    r: Math.random() * 2 + 1.5,
    dx: (Math.random() - 0.5) * 0.8,
    dy: (Math.random() - 0.5) * 0.8
  });
}

// handle resize without losing particles
window.addEventListener("resize", () => {
  const oldWidth = width;
  const oldHeight = height;
  width = canvas.width = window.innerWidth;
  height = canvas.height = window.innerHeight;

  // scale particles to new canvas size
  const scaleX = width / oldWidth;
  const scaleY = height / oldHeight;
  particles.forEach(p => {
    p.x *= scaleX;
    p.y *= scaleY;
  });
});

function animate() {
  ctx.clearRect(0, 0, width, height);

  // draw particles
  particles.forEach(p => {
    p.x += p.dx;
    p.y += p.dy;

    if (p.x < 0) p.x = width;
    if (p.x > width) p.x = 0;
    if (p.y < 0) p.y = height;
    if (p.y > height) p.y = 0;

    ctx.beginPath();
    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2, false);
    ctx.fillStyle = "rgba(255,255,255,0.85)";
    ctx.shadowColor = "rgba(255,255,255,0.8)";
    ctx.shadowBlur = 12;
    ctx.fill();
  });

  // draw connecting lines
  for (let i = 0; i < particles.length; i++) {
    for (let j = i + 1; j < particles.length; j++) {
      const dx = particles[i].x - particles[j].x;
      const dy = particles[i].y - particles[j].y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 120) {
        ctx.beginPath();
        ctx.moveTo(particles[i].x, particles[i].y);
        ctx.lineTo(particles[j].x, particles[j].y);
        ctx.strokeStyle = `rgba(255,255,255,${0.4 - dist / 300})`;
        ctx.lineWidth = 0.6;
        ctx.stroke();
      }
    }
  }

  requestAnimationFrame(animate);
}

animate();
const activeClass = new URLSearchParams(window.location.search).get('class') || 'all';

const RANK_COLORS = {
  'Xuất sắc':   '#0073ff',
  'Giỏi':       '#00c365',
  'Khá':        '#eda600',
  'Trung bình': '#e61515',
  'Yếu':        '#8a8e94',
};

let chartRanking = null;
let chartGpaBar  = null;

async function loadCards() {
  try {
    const res  = await fetch(`../reports/average.php?class=${activeClass}`);
    const data = await res.json();
    document.getElementById('valTotal').textContent = Number(data.total).toLocaleString('vi');
    document.getElementById('valPass').textContent  = (data.pass_rate ?? 0) + '%';
    document.getElementById('valGpa').textContent   = data.gpa ?? '—';
    document.querySelectorAll('.loading-card').forEach(c => c.classList.remove('loading-card'));
  } catch (e) { console.error('loadCards:', e); }
}

async function loadRanking() {
  try {
    const res    = await fetch(`../reports/ranking.php?class=${activeClass}`);
    const data   = await res.json();
    const labels = Object.keys(data);
    const values = Object.values(data).map(Number);
    const total  = values.reduce((a, b) => a + b, 0);
    const colors = labels.map(l => RANK_COLORS[l] ?? '#ccc');

    document.getElementById('chartCenterPct').textContent = total > 0 ? '100%' : '0%';
    document.getElementById('chartLegend').innerHTML = labels.map((l, i) => `
      <div class="legend-item">
        <span class="legend-dot" style="background:${colors[i]}"></span>
        <span class="legend-label">${l}</span>
        <span class="legend-pct">${total > 0 ? Math.round(values[i] / total * 100) : 0}%</span>
      </div>`).join('');

    if (chartRanking) chartRanking.destroy();
    chartRanking = new Chart(document.getElementById('chartRanking'), {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data: values.length ? values : [1],
          backgroundColor: values.length ? colors : ['#f0f2f7'],
          borderWidth: 0,
          hoverOffset: 6,
        }]
      },
      options: {
        cutout: '72%',
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} SV` } }
        }
      }
    });
  } catch (e) { console.error('loadRanking:', e); }
}

async function loadGpaBar() {
  try {
    const res  = await fetch(`../reports/result.php?class=${activeClass}`);
    const data = await res.json();

    // Gom GPA theo môn
    const map = {};
    data.forEach(row => {
      if (!map[row.subject_name]) map[row.subject_name] = [];
      map[row.subject_name].push(parseFloat(row.gpa));
    });
    const labels = Object.keys(map);
    const values = labels.map(k => {
      const arr = map[k];
      return +(arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(2);
    });

    if (chartGpaBar) chartGpaBar.destroy();
    chartGpaBar = new Chart(document.getElementById('chartGpaBar'), {
      type: 'bar',
      data: {
        labels: labels.length ? labels : ['Chưa có dữ liệu'],
        datasets: [{
          label: 'GPA TB',
          data: values.length ? values : [0],
          backgroundColor: '#7994ea',
          borderRadius: 8,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: { min: 0, max: 10, grid: { color: '#f0f2f7' } },
          x: { grid: { display: false } }
        }
      }
    });
  } catch (e) { console.error('loadGpaBar:', e); }
}

document.addEventListener('DOMContentLoaded', () => {
  loadCards();
  loadRanking();
  loadGpaBar();
});
// ===== BAR CHART =====
const ctx1 = document.getElementById('myChart1').getContext('2d');
new Chart(ctx1, {
  type: 'bar',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    datasets: [{
      data: [120000, 30000, 175000, 160000, 120000, 85000, 95000, 140000, 100000, 90000, 105000, 60000],
      backgroundColor: 'rgba(30, 144, 255, 0.8)',
      borderRadius: 6
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { color: '#333' } },
      x: { ticks: { color: '#333' } }
    }
  }
});

// ===== DOUGHNUT CHART =====
const ctx2 = document.getElementById('myChart2').getContext('2d');
new Chart(ctx2, {
  type: 'doughnut',
  data: {
    labels: ['Wedding', 'Nilame', 'Business', 'Indian', 'Dinner'],
    datasets: [{
      data: [40, 25, 15, 10, 10],
      backgroundColor: ['red','orange','limegreen','dodgerblue','magenta'],
      borderWidth: 1
    }]
  },
  options: {
    cutout: '65%',
    plugins: {
      legend: { display: false },
      tooltip: { enabled: true }
    }
  }
});

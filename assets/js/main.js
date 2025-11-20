const revenueData = window.dashboardData?.revenue ?? [];
const categoryCounts = window.dashboardData?.categories ?? [];
const categoryLabels = window.dashboardData?.labels ?? [];


// BAR CHART
const ctx1 = document.getElementById('myChart1').getContext('2d');
new Chart(ctx1, {
  type: 'bar',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    datasets: [{
      data: revenueData,
      backgroundColor: 'rgba(30, 144, 255, 0.8)',
      borderRadius: 6
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true },
      x: {}
    }
  }
});


// PIE CHART
const ctx2 = document.getElementById('myChart2').getContext('2d');

new Chart(ctx2, {
  type: 'doughnut',
  data: {
    labels: categoryLabels,
    datasets: [{
      data: categoryCounts,
      backgroundColor: ['red','orange','limegreen','dodgerblue','magenta'],
      borderWidth: 1
    }]
  },
  options: {
    cutout: '65%',
    plugins: { legend: { display: false } }
  }
});

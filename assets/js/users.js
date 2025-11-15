// assets/js/users.js
document.addEventListener('DOMContentLoaded', () => {

  // Attach status toggle buttons (class .toggle-status)
  document.querySelectorAll('.toggle-status').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const userId = btn.dataset.id;
      const newStatus = btn.dataset.target;
      if (!confirm(`Change user status to "${newStatus}"?`)) return;

      fetch('/FitSphere/src/admin/users/process_user_update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'toggle_status', user_id: userId, status: newStatus })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          // update badge + button text
          const badge = document.querySelector(`#status-badge-${userId}`);
          badge.textContent = data.newStatus;
          badge.className = ''; // reset classes
          if (data.newStatus === 'Active') badge.classList.add('badge-active');
          else if (data.newStatus === 'Suspended') badge.classList.add('badge-suspended');
          else badge.classList.add('badge-blocked');

          // update toggle button dataset & label
          btn.dataset.target = data.nextAction;
          btn.textContent = data.nextAction === 'Active' ? 'Activate' : 'Suspend';
          alert('Status updated');
        } else {
          alert('Update failed: ' + (data.error || 'Unknown'));
        }
      })
      .catch(err => {
        console.error(err);
        alert('Request failed');
      });
    });
  });

});

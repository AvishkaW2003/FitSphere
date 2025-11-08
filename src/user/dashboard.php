<?php
require_once __DIR__ . '/../../includes/auth/auth_user.php';

$user = Auth::user();
?>
<h2>User Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($user['email']); ?> (<?= $user['role']; ?>)</p>
<a href="/FitSphere/src/logout.php">Logout</a>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');
?>
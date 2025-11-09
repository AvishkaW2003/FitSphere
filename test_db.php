<?php
require_once __DIR__ . '/includes/db.php';

use FitSphere\Database\Database; 
use FitSphere\Core\Session; 

$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "✅ Database connected successfully!";
} else {
    echo "❌ Database connection failed!";
}

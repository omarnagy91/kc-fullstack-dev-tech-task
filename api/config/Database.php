<?php

// Database configuration
$host = 'db';
$port = 3306;
$dbname = 'course_catalog';
$username = 'test_user';
$password = 'test_password';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection errors
    die("Database connection failed: " . $e->getMessage());
}

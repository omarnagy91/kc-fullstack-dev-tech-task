<?php
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

if ($db === null) {
    echo "Failed to connect to the database." . PHP_EOL;
    exit;
}

// List of migration files
$migrations = [
    'database/migrations/202304191000_create_categories_table.sql',
    'database/migrations/202304191010_create_courses_table.sql',
    'database/migrations/202304191020_insert_mock_data.sql'
];

foreach ($migrations as $migration) {
    try {
        $sql = file_get_contents($migration);
        if ($sql === false) {
            throw new Exception("Error reading file: $migration");
        }
        $db->exec($sql);
        echo "Executed migration: $migration" . PHP_EOL;
    } catch (Exception $e) {
        echo "Error executing migration $migration: " . $e->getMessage() . PHP_EOL;
        exit;
    }
}
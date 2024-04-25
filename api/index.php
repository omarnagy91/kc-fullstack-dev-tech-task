<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Include required files
require_once 'config/database.php';
require_once 'routes/categories.php';
require_once 'routes/courses.php';

// Set content type header to JSON
header('Content-Type: application/json');

// Get the request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove trailing slash from request URI
$request_uri = rtrim($request_uri, '/');

// Define the base path for the API
$base_path = '/';

// Remove base path from request URI
$path = substr($request_uri, strlen($base_path));

// Split the path into segments
$segments = explode('/', $path);

// Get the resource and ID from the segments
$resource = $segments[0] ?? '';
$id = $segments[1] ?? null;

// Define routes
$routes = [
    'categories' => 'handleCategoryRequest',
    'courses' => 'handleCourseRequest',
];

// Check if the resource exists in the routes
if (array_key_exists($resource, $routes)) {
    // Call the corresponding handler function
    $handler = $routes[$resource];
    $handler($method, $id);
} else {
    // Return 404 if the resource is not found
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
}

<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if (isset($_GET['url'])) {
    $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));

    // Route the request to the correct controller and method
    switch ($url[0]) {
        case 'categories':
            require_once 'controllers/CategoryController.php';
            break;
        case 'courses':
            require_once 'controllers/CourseController.php';
            break;
        default:
            // Invalid URL
            echo json_encode(array('message' => 'Invalid Endpoint'));
            break;
    }
} else {
    // No URL set
    echo json_encode(array('message' => 'No Endpoint Specified'));
}

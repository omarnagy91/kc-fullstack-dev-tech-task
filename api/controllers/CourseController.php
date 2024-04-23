<?php

require_once '../config/Database.php';
require_once '../models/Course.php';

$database = new Database();
$db = $database->connect();

$course = new Course($db);

$request_method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['id'])) {
    $course->id = $_GET['id'];
    $course->read_single();

    $course_arr = [
        'id' => $course->id,
        'name' => $course->name,
        'description' => $course->description,
        'preview' => $course->preview,
        'main_category_name' => $course->main_category_name,
        'created_at' => $course->created_at,
        'updated_at' => $course->updated_at
    ];

    echo json_encode($course_arr);
} else {
    $result = $course->read();
    $num = $result->rowCount();

    if ($num > 0) {
        $courses_arr = ['data' => []];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $course_item = [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'preview' => $preview,
                'main_category_name' => $main_category_name,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ];

            array_push($courses_arr['data'], $course_item);
        }

        echo json_encode($courses_arr);
    } else {
        echo json_encode(['message' => 'No Courses Found']);
    }
}

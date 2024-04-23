<?php

// Correctly ensure usage of 'require_once' instead of 'include_once'
require_once '../config/Database.php';
require_once '../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$request_method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['id'])) {
    $category->id = $_GET['id'];
    $category->read_single();

    $category_arr = [
        'id' => $category->id,
        'name' => $category->name,
        'parent_id' => $category->parent_id,
        'count_of_courses' => $category->count_of_courses,
        'created_at' => $category->created_at,
        'updated_at' => $category->updated_at
    ];

    echo json_encode($category_arr);
} else {
    $result = $category->read();
    $num = $result->rowCount();

    if ($num > 0) {
        $categories_arr = ['data' => []];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = [
                'id' => $id,
                'name' => $name,
                'parent_id' => $parent_id,
                'count_of_courses' => $count_of_courses,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ];

            array_push($categories_arr['data'], $category_item);
        }

        echo json_encode($categories_arr);
    } else {
        echo json_encode(['message' => 'No Categories Found']);
    }
}

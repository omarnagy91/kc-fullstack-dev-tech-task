<?php

function handleCourseRequest($method, $id)
{
    global $pdo;

    switch ($method) {
        case 'GET':
            if ($id) {
                // Get a single course by ID
                $stmt = $pdo->prepare('
                    SELECT courses.*, categories.name AS main_category_name 
                    FROM courses
                    JOIN categories ON courses.category_id = categories.id
                    WHERE courses.id = ?
                ');
                $stmt->execute([$id]);
                $course = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($course) {
                    echo json_encode($course);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Course not found']);
                }
            } else {
                // Get all courses with optional category filter
                $category_id = $_GET['category_id'] ?? null;

                $sql = 'SELECT courses.*, categories.name AS main_category_name 
                        FROM courses
                        JOIN categories ON courses.category_id = categories.id';

                if ($category_id) {
                    $sql .= ' WHERE courses.category_id = ?';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$category_id]);
                } else {
                    $stmt = $pdo->query($sql);
                }

                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($courses);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
            break;
    }
}

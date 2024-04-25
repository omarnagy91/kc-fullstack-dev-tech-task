<?php

function handleCategoryRequest($method, $id)
{
    global $pdo;

    switch ($method) {
        case 'GET':
            if ($id) {
                // Get a single category by ID with count of courses including subcategories up to 4 levels deep
                $stmt = $pdo->prepare('
                    WITH RECURSIVE subcategories AS (
                        SELECT id
                        FROM categories
                        WHERE id = ?
                        UNION ALL
                        SELECT c.id
                        FROM categories c
                        INNER JOIN subcategories sc ON c.parent_id = sc.id
                    )
                    SELECT c.*, (
                        SELECT COUNT(*)
                        FROM courses
                        WHERE courses.category_id IN (SELECT id FROM subcategories)
                    ) AS count_of_courses
                    FROM categories c
                    WHERE c.id = ?
                ');
                $stmt->execute([$id, $id]);
                $category = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($category) {
                    echo json_encode($category);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Category not found']);
                }
            } else {
                // Get all categories with count of courses including subcategories up to 4 levels deep
                $stmt = $pdo->query('
                    WITH RECURSIVE subcategories AS (
                        SELECT id, parent_id
                        FROM categories
                        UNION ALL
                        SELECT c.id, c.parent_id
                        FROM categories c
                        INNER JOIN subcategories sc ON c.parent_id = sc.id
                    )
                    SELECT c.*, (
                        SELECT COUNT(*)
                        FROM courses
                        WHERE courses.category_id IN (SELECT id FROM subcategories WHERE subcategories.id = c.id)
                    ) AS count_of_courses
                    FROM categories c
                ');
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($categories);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
            break;
    }
}

-- up
CREATE TABLE `courses` (
    `id` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `preview` VARCHAR(255) NOT NULL, -- Changed from image_preview to preview
    `main_category_name` VARCHAR(255) NOT NULL, -- Added main_category_name
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`main_category_name`) REFERENCES `categories`(`name`) ON DELETE CASCADE -- Changed from category_id to main_category_name
);

-- down
DROP TABLE `courses`;
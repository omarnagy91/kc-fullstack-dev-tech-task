<?php
class Category
{
    // DB stuff
    private $conn;
    private $table = 'categories';

    // Category properties
    public $id;
    public $name;
    public $parent_id;
    public $count_of_courses;
    public $created_at;
    public $updated_at;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Categories
    public function read()
    {
        // Create query
        $query = 'SELECT * FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Single Category
    public function read_single()
    {
        // Create query
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->name = $row['name'];
        $this->parent_id = $row['parent_id'];
        $this->count_of_courses = $row['count_of_courses'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
}
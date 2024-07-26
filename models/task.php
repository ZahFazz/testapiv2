<?php
class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $project_id;
    public $user_id;
    public $name;
    public $description;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $project_query = "SELECT id FROM projects WHERE id = :project_id";
        $user_query = "SELECT id FROM users WHERE id = :user_id";

        $stmt = $this->conn->prepare($project_query);
        $stmt->bindParam(":project_id", $this->project_id);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return false; 
        }

        $stmt = $this->conn->prepare($user_query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return false; 
        }
    
  
        $query = "INSERT INTO " . $this->table_name . " SET project_id=:project_id, user_id=:user_id, name=:name, description=:description";
        $stmt = $this->conn->prepare($query);
    
        $this->project_id=htmlspecialchars(strip_tags($this->project_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
    
        $stmt->bindParam(":project_id", $this->project_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
    
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    
    

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET project_id=:project_id, user_id=:user_id, name=:name, description=:description WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->project_id=htmlspecialchars(strip_tags($this->project_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":project_id", $this->project_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>

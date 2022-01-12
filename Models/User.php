<?php
/**
 * Class User
 * 
 */
Class User {
	//DB Conn
	private $conn;
	private $table = 'users';

	//User Properties
	public int $id = 0;
	public ?string $name = null;
	public int $age = 0;
	public ?string $address = null;
	public int $points = 0;

	public function __construct(){
		$database = new Database();
		$this->conn = $database->connect();
	}

	/**
	 * Get all users
	 * 
	 * @return object 
	 */
	public function getAll(){
		$query = 'SELECT * FROM ' . $this->table . ' ORDER BY points DESC';
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		return $stmt;
	}

	/**
	 * Get user 
	 * 
	 * @return object 
	 */
	public function get(){
		$query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
		$stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(1, $this->id);

		$stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row;
	}

	/**
	 * Save user 
	 * 
	 * @return int|bool 
	 */
	public function save(){
		$query = 'INSERT INTO ' . $this->table . ' 
													SET 
													name=:name, age=:age, address=:address, points=:points';
		$stmt = $this->conn->prepare($query);

		$this->name = htmlspecialchars(strip_tags($this->name));
    $this->age = (int) htmlspecialchars(strip_tags($this->age));
    $this->address = htmlspecialchars(strip_tags($this->address));
    $this->points = (int) htmlspecialchars(strip_tags($this->points));

    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':age', $this->age);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':points', $this->points);

    // Execute query
    if($stmt->execute()) {
      return $this->conn->lastInsertId();
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;

	}


	/**
	 * Update user 
	 * 
	 * @return bool 
	 */
	public function update(){

		$this->name = htmlspecialchars(strip_tags($this->name));
    $this->age = (int) htmlspecialchars(strip_tags($this->age));
    $this->address = htmlspecialchars(strip_tags($this->address));
    $this->points = (int) htmlspecialchars(strip_tags($this->points));
    $this->id = (int) htmlspecialchars(strip_tags($this->id));

    $fields = array();
    if(!empty($this->name))
    $fields[] = ' name=:name';
    if(!empty($this->age))
    $fields[] = ' age=:age';
    if(!empty($this->address))
    $fields[] = ' address=:address';
    if(!empty($this->points))
    $fields[] = ' points=:points';
    
    $query = 'UPDATE ' . $this->table . ' SET '
                          . join(',', $fields)
                          . ' WHERE id=:id';
    $stmt = $this->conn->prepare($query);

    if(!empty($this->name))
    	$stmt->bindParam(':name', $this->name);
   	if(!empty($this->age))
      $stmt->bindParam(':age', $this->age);
    if(!empty($this->address))
    $stmt->bindParam(':address', $this->address);
    if(!empty($this->points))
      $stmt->bindParam(':points', $this->points);
    $stmt->bindParam(':id', $this->id);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;

	}

	/**
	 * Delete user 
	 * 
	 * @return bool 
	 */
	public function delete(){
		$query = 'DELETE FROM ' . $this->table . ' WHERE id=:id LIMIT 1';
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		
		$stmt->bindParam(':id', $this->id);

		if($stmt->execute()){
			return true;
		} else {
			printf("Error: %s.\n", $stmt->error);
			return false;
		}
	}

	/**
	 * Update user points
	 * 
	 * @return bool 
	 */
	public function updatePoints($action){

		if($action == "increment"){			
			$query = 'UPDATE ' . $this->table . ' SET points = points + 1 WHERE id=:id LIMIT 1';
		} else {
			$query = 'UPDATE ' . $this->table . ' SET points = points - 1 WHERE id=:id AND points > 0 LIMIT 1';
		}
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		
		$stmt->bindParam(':id', $this->id);

		if($stmt->execute()){
			return true;
		} else {
			printf("Error: %s.\n", $stmt->error);
			return false;
		}
	}
}

?>

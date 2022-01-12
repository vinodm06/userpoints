<?php
 /**
	* Class UserController 
	* 
	*/
Class UserController{
	private $userModel;
	private $error;

	public function __construct(){
		
		$this->userModel = new User();
	}

	/**
	 * Give JSON list of Users. 
	 * 
	 * @return void 
	 */
	public function getUsers(){

		$result = $this->userModel->getAll();
		$num = $result->rowCount();

		if($num>0){
		  $userArr = array();
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		      // Push to "data"
		      array_push($userArr, $row);
		    }

		    // Turn to JSON & output
		    http_response_code(HttpStatusCode::HTTP_OK);
		    echo json_encode($userArr);
		} else {
		    // No Users Found
				http_response_code(HttpStatusCode::HTTP_NOT_FOUND);
		    echo json_encode(
		      array('message' => 'No Users Found')
		    );
		}
	}

	/**
	 * Get specific user 
	 * 
	 * @param int $id
	 * 
	 * @return void
	 */
	public function getUser($id){

		if(!empty($id)){
			$this->userModel->id = $id;
			$row = $this->userModel->get();
			$userArr = array();

			if(!empty($row)){
			
			// Turn to JSON & output
				http_response_code(HttpStatusCode::HTTP_OK);
				echo json_encode($row);
				} else {
				  // No Users Found
					http_response_code(HttpStatusCode::HTTP_NOT_FOUND);
				  echo json_encode(
				  array('message' => 'No Users Found')
				  );
				}
			} else {
				http_response_code(HTTPStatusCode::HTTP_BAD_REQUEST);
				echo json_encode(
				  array('message' => 'Invalid User id')
				  );
			}
	}

	/**
	 * Create user 
	 * 
	 * @param object $userData
	 * 
	 * @return void
	 */
	public function createUser($userData){
		
		if(trim($userData->name))
			$this->userModel->name = $userData->name;
		if(trim($userData->age))
			$this->userModel->age = $userData->age;
		if(trim($userData->address))
		  $this->userModel->address = $userData->address;
		if(trim($userData->points))
		  $this->userModel->points = $userData->points;
		  
		$userId = $this->userModel->save();

		if(!empty($userId)){
			$this->getUser($userId);
		} else {
			// Not Created
			echo json_encode(
			  array('message' => 'No Users Found')
			);
		}
	}	

	/**
	 * Update user 
	 * 
	 * @param object $userData
	 * 
	 * @return void
	 */
	public function updateUser($userData){

		if(!empty($userData->id)){
			$this->userModel->id = $userData->id;
			if(trim($userData->name))
				$this->userModel->name = $userData->name;
			if(trim($userData->age))
				$this->userModel->age = $userData->age;
			if(trim($userData->address))
			  $this->userModel->address = $userData->address;
			if(trim($userData->points))
			  $this->userModel->points = $userData->points;

		  $flag = $this->userModel->update();

			if($flag){
				$this->getUser($userData->id);
			} else {
				  // No Posts
				echo json_encode(
				  array('message' => 'No Users Found')
				);
			}
		} else {
			http_response_code(HTTPStatusCode::HTTP_BAD_REQUEST);
			echo json_encode(
				array('message' => 'Invalid User id')
			);
		}
	}

	/**
	 * Patch user 
	 * 
	 * @param object $userData
	 * 
	 * @return void
	 */
	public function patchUser($userData){

		if(!empty($userData->id)){
			$this->userModel->id = $userData->id;
			if(isset($this->name))
				$this->userModel->name = $userData->name;
			if(isset($this->age))
				$this->userModel->age = $userData->age;
			if(isset($this->address))
			  $this->userModel->address = $userData->address;
			if(isset($this->points))
			  $this->userModel->points = $userData->points;
		  

		  $flag = $this->userModel->update();

			if($flag){
				$this->getUser($userData->id);
				} else {
				    // No Posts
				echo json_encode(
				  array('message' => 'No Users Found')
				);
			}
		} else {
			http_response_code(HTTPStatusCode::HTTP_BAD_REQUEST);
			echo json_encode(
				array('message' => 'Invalid User id')
			);
		}
	}

	/**
	 * Delete user 
	 * 
	 * @param int id
	 * 
	 * @return void
	 */
	public function deleteUser($id){

		$this->userModel->id = $id;
		$response = $this->userModel->delete();
	}

	/**
	 * Update points 
	 * 
	 * @param string $action 
	 * @param object $userData 
	 * 
	 * @return void
	 */
	public function updatePoints($id, $action, $userData){
		if(!empty($userData->id)){	
			$this->userModel->id = $userData->id;
			$this->userModel->updatePoints($action);
			
			if(!empty($userData->id)){
				$this->getUser($userData->id);
			} else {
				// Not Created
				echo json_encode(
				  array('message' => 'No Users Found')
				);
			}
		} else {
			http_response_code(HTTPStatusCode::HTTP_BAD_REQUEST);
			echo json_encode(
				array('message' => 'Invalid User id')
			);
		}	
	}
}

?>


<?php
	Class Database{
		//DB Params
		private $host = "Localhost";
		private $db_name = "userpoints";
		private $username = "indriyam_dbuser";
		private $password = "9utC20L}8I~Y";
		private $conn;

		//DB connect
		public function connect(){
			$this->conn = null;

			try {
				$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, 
					$this->password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e ) {
				echo "Connection Error: " . $e->getMessage();
			}

			return $this->conn;
		}

	}
?>
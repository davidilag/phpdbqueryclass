<?php

/*
 * This class handles creation of database connection and has functions to query the database with the connection. 
 * In order to use this class you have to write the following in your php-script:
 * 
 * require_once 'db_connection.php';
 * $instance = new Connection();
 * 
 * Now you can use the '$instance'-object to call on the public functions in this class like so:
 * $result = $instance->generic_query('SELECT', 'person', NULL, NULL, NULL, NULL, array('name' => 'ASC'));
 * 
 * The '$result' object holds the result of the query, and depending on what type of query you are 
 * using the '$result' can be used to eighter print out a result set (SELECT), get number of rows 
 * affected (UPDATE and DELETE) or the last inserted id (INSERT INTO).  
 * 
 * */

class Connection {
	
	private $db = array();
	private $connection;

	/*
	 * Change the database parameters to fit your database.
	 * */
	function __construct(){
		$this->db['host'] 		= 'localhost'; 	// database IP address
		$this->db['user'] 		= 'root'; 		// database user
		$this->db['password'] 	= '';			// database password
		$this->db['db'] 		= 'connector';	// name of database
		
		$this->connect();
	}
	
	/*
	 * This private function is called from the above constructor and creates the actual database connection.
	 * */
	private function connect(){
		$db = $this->db;
		$mysqli = new mysqli($db['host'], $db['user'], $db['password'], $db['db']);
		
		if ($mysqli->connect_error) {
    		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
		}
		
		$this->connection = $mysqli;
	}
	
	
	/*
	 * This function can take SELECT, INSERT INTO, UPDATE and DELETE. 
	 * It also handles WHERE, AND, OR and ORDER BY (ASC or DESC) where it is relevant. 
	 * 
	 * Examples 
	 * SELECT: 		generic_query('SELECT', 'person', NULL, array('name' => 'David'), NULL, NULL, array('name' => 'ASC'));
	 * INSERT INTO: generic_query('INSERT INTO', 'person', array('name' => 'David', 'email' => 'test@test.dk', 'date_created' => '2013-09-16', 'active' => 0));
	 * UPDATE: 		generic_query('update', 'person', array('name' => 'David i Lag', 'email' => 'test@david.dk'), array('name' => 'David'));
	 * DELETE: 		generic_query('Delete', 'person', NULL, array('name' => 'Hans'));
	 * 
	 * NB!: The order in which you set the parameters is important! If you fx. don't need to set $where but $order_by you will need to 
	 * set all the parameters in between to NULL, like so: generic_query('SELECT', 'person', NULL, NULL, NULL, NULL, array('name' => 'ASC'));
	 * 
	 * */
	public function generic_query($query_type, $table_name, $values = NULL, $where = NULL, $and = NULL, $or = NULL, $order_by = NULL) {
		
		$query_type 	= trim (strtoupper ($query_type));
		$table_name 	= trim ($table_name);
		
		$post_qry = '';
		if ($where) { 	$post_qry .= ' WHERE ' . 	key($where) . 	'="' . 	$where[key($where)] . 	'"';}
		if ($and) { 	$post_qry .= ' AND ' . 		key($and) . 	'="' . 	$and[key($and)] . 		'"';}
		if ($or) { 		$post_qry .= ' OR ' . 		key($or) . 		'="' . 	$or[key($or)] . 		'"';}
		if ($order_by) {$post_qry .= ' ORDER BY ' . key($order_by) . ' ' . 	$order_by[key($order_by)];	}

		$qry = '';
		$qry .= $query_type;
		
		switch ($query_type) {
			
			case 'SELECT':
				if ($values) { $qry .= ' ' . implode(', ', array_keys($values)) . ' FROM ';} 
				else { $qry .= ' * FROM ';}
				
				$qry .= $table_name;
				$qry .= $post_qry;
				
				$result = $this->connection->query($qry);
				$rows = array();
		    	while ($row = $result->fetch_assoc()) { $rows[] = $row;}
				$result->free();
		    	return $rows;
				
				break;
			
			case 'INSERT INTO':
				$qry .= ' ' . $table_name;
				$qry .= ' (' . implode(', ', array_keys($values)) . ') ';
				$qry .= ' VALUES ';
				
				$val = array_values($values);
				foreach ($val as &$value) { $value = '"' . $value . '"';}
				$qry .= '(' . implode(', ', array_values($val)) . ')';

				$this->connection->query($qry);
				return $this->connection->insert_id;
				
				break;
			
			case 'UPDATE':
				$qry .= ' ' . $table_name . ' SET ';

				$str = '';
				foreach ($values as $key => $value) { $str .= $key . '=' . '"' . $value . '", ';}
				$qry .= substr($str, 0, strlen($str) - 2);
				$qry .= $post_qry;

				$this->connection->query($qry);
				return $this->connection->affected_rows; 
				
				break;
			
			case 'DELETE':
				$qry .= ' FROM ';
				$qry .= $table_name;
				$qry .= $post_qry;
				
				$this->connection->query($qry);
				return $this->connection->affected_rows;
				
				break;
		}
		
		$this->connection->close();
	}


	/*
	 * This function takes a SQL query as a string.
	 * 
	 * Example: 'SELECT * FROM person WHERE id=1'
	 * */
	public function run_query($query){
		return $this->connection->query($query);
	}
	
	
	/*
	 * Function takes a SQL query as a string and returns an array
	 * 
	 * Example: 'SELECT * FROM person';
	 * */
	public function run_query_return_array($query){
		$result = $this->connection->query($query);
		$rows = array();
	    while ($row = $result->fetch_assoc()) {
	        $rows[] = $row;
	    }
	    return $rows;
	}
	
	/*
	 * This function takes an SQL query as a string and returns the last inserted id
	 * 
	 * Example: 'INSERT INTO person VALUES ('David', 'test@david.dk', '2013-09-15', 1)'
	 * */
	public function run_query_retur_last_inserted_id($query){
		$result = $this->connection->query($query);
		return $this->connection->insert_id;
	}
	
	
	public function free_result($result){
		$result->free();
	}
	public function close_connection($con){
		$con->close();
	}
	
}


?>
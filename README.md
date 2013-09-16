phpdbqueryclass
===============

Generic PHP class for SELECT, INSERT, UPDATE and DELETE including WHERE, AND, OR and ORDER BY.

I have tried to keep this project simple, and kept all in one class so that it is transparrent and easy to modify.

Directions
/ To test the connector class I have included a SQL script which creates a table called person
/ The test.php script shows some examples of how the connector class can be used. 

All you do to use the class is write the following code in your PHP script:
require_once 'db_connection.php';
$instance = new Connection();

Now you can use the '$instance'-object to call on the public functions in this class like so:
$result = $instance->generic_query('SELECT', 'person', NULL, NULL, NULL, NULL, array('name' => 'ASC'));

The '$result' object holds the result of the query, and depending on what type of query you are 
using the '$result' can be used to eighter print out a result set (SELECT), get number of rows 
affected (UPDATE and DELETE) or the last inserted id (INSERT INTO).  


	 * Examples 
	 * SELECT: 		generic_query('SELECT', 'person', NULL, array('name' => 'David'), NULL, NULL, array('name' => 'ASC'));
	 * INSERT INTO: generic_query('INSERT INTO', 'person', array('name' => 'David', 'email' => 'test@test.dk', 'date_created' => '2013-09-16', 'active' => 0));
	 * UPDATE: 		generic_query('update', 'person', array('name' => 'David i Lag', 'email' => 'test@david.dk'), array('name' => 'David'));
	 * DELETE: 		generic_query('Delete', 'person', NULL, array('name' => 'Hans'));
	 * 
	 * NB!: The order in which you set the parameters is important! If you fx. don't need to set $where but $order_by you will need to 
	 * set all the parameters in between to NULL, like so: generic_query('SELECT', 'person', NULL, NULL, NULL, NULL, array('name' => 'ASC'));

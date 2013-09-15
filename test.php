<?php
require_once 'db_connection.php';

$instance = new Connection();

$result = $instance->generic_query('INSERT INTO', 'person', array('name' => 'David', 
	'email' => 'test@test.dk', 'date_created' => '2013-09-16', 'active' => 0));
echo '<br>';
echo 'Last inserted id: ' . $result;
echo '<br><br>';


$result = $instance->generic_query('SELECT', 'person', NULL, NULL, NULL, NULL, array('name' => 'ASC'));
var_dump($result);
echo '<br><br>';

$result = $instance->generic_query('SELECT', 'person', array('name' => '', 'email' => ''), 
	array('name' => 'David'), NULL, array('active' => '1'), array('name' => 'DESC'));
var_dump($result);
echo '<br><br>';


$result = $instance->generic_query('update', 'person', array('name' => 'David i Lag', 'email' => 'david@david.fo'), array('name' => 'David'));
echo '<br>';
echo 'Number of affected rows (UPDATE): ' . $result;
echo '<br><br>';

$result = $instance->generic_query('SELECT', 'person', NULL, NULL, NULL, NULL, array('name' => 'ASC'));
var_dump($result);
echo '<br><br>';

$result = $instance->generic_query('delete', 'person', NULL, array('name' => 'Hans'));
echo '<br>';
echo 'Number of affected rows (DELETE): ' . $result;
echo '<br><br>';


$result = $instance->generic_query('SELECT', 'person', NULL, NULL, NULL, NULL, array('name' => 'ASC'));
var_dump($result);
echo '<br><br>';

?>
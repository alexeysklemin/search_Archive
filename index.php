<?php
ini_set('display_errors', 1);
ini_set('diplay_startup_errors', 1);
error_reporting(E_ALL);


$DB_HOST = 'localhost';
$DB_USER = 'www-data'; 
$DB_PASSWORD = 'some_password';
$DB_NAME = 'test';

$connection = new mysqli ($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if($connection->connect_error){
	die("Error connection" . $connection->connect_error);
}else{

$sql = "SELECT * FROM `test`";

$result = $connection->query($sql);

while($row = $result->fetch_assoc()){

echo  nl2br($row['name']);
echo   "<a href = '".$row['site']."'>  Homepage  </a>";
echo	'<br>';
}

}
$connection->close();
?>



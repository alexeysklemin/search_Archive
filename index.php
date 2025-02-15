<?php

ini_set('display_errors', 1);
ini_set('diplay_startup_errors', 1);
error_reporting(E_ALL);

$DB_HOST = 'localhost';
$DB_USER = 'www-data'; 
$DB_PASSWORD = 'some_password';
$DB_NAME = 'archive';

$connection = new mysqli ($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if($connection->connect_error){
    die("Error connection" . $connection->connect_error);
}else{
    if(isset($_GET['query'])){
        $query = $_GET['query'];
        $sql = "SELECT * FROM `area` WHERE name_area LIKE '%$query%'";

        $result = $connection->query($sql);

        while($row = $result->fetch_assoc()){
            echo nl2br($row['name_area']);
            echo "<a href='".$row['ste_address']."'> '".$row['ste_description']."'  </a>";
            echo '<br>';
        }
        
        // Дополнительный блок кода
        $result = $connection->query($sql); // Выполняем запрос еще раз

        while($row = $result->fetch_assoc()){
            echo "<div>";
            echo "<h3>" . $row["name_area"] . "</h3>";
            echo "<p>" . $row["ste_description"] . "</p>";
            echo "</div>";
        }
    }
}
$connection->close();
?>

<form action="" method="get">
    <input type="text" name="query" placeholder="Введите запрос...">
    <button type="submit">Искать</button>
</form>

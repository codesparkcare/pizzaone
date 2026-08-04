<?php
$host='localhost';
$user='root';
$pass='';
$db='pizzaone';
$mysqli=new mysqli($host,$user,$pass,$db);
if($mysqli->connect_error){die('Connect Error ('.$mysqli->connect_errno.') '.$mysqli->connect_error);}
$res=$mysqli->query('SHOW COLUMNS FROM `orders`');
if(!$res){echo "Error: ".$mysqli->error; exit;}
while($row=$res->fetch_assoc()){
    echo $row['Field']." ".$row['Type']." ".$row['Null']." ".$row['Key']."\n";
}
$mysqli->close();
?>

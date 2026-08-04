<?php
$mysqli = new mysqli('127.0.0.1', 'root', '');
if($mysqli->connect_error) {
    echo 'Error: ' . $mysqli->connect_error;
    exit;
}
if($mysqli->query('CREATE DATABASE IF NOT EXISTS pizzaone')) {
    echo 'DB created';
} else {
    echo 'Error: ' . $mysqli->error;
}

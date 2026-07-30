<?php

$host = '127.0.0.1';
$port = 3307;
$database = 'mental_wellness_db';
$username = 'root';
$password = 'Mkiatg0517';

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database,
    $port
);

$conn->set_charset('utf8mb4');
<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'itp_data';

// Establish database connection
$connection = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die('Database connection error: ' . mysqli_connect_error());
}

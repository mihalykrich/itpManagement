<?php
include 'db.php';

// Get the component ID from the query string
$componentId = $_GET['component_id'];

// Fetch the stock quantity from the components table
$query = "SELECT stock_quantity FROM components WHERE component_id = " . $componentId;
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);

// Check if the component exists
if (!$row) {
  die("Component not found.");
}

// Return the stock quantity as the response
echo $row['stock_quantity'];
?>

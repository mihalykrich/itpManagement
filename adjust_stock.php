<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Extract and sanitize the form inputs
  $componentId = mysqli_real_escape_string($connection, $_POST['componentId']);
  $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);

  // Retrieve the current stock quantity of the component
  $query = "SELECT stock_quantity FROM components WHERE component_id = '$componentId'";
  $result = mysqli_query($connection, $query);

  if ($row = mysqli_fetch_assoc($result)) {
    $currentQuantity = $row['stock_quantity'];

    // Calculate the new stock quantity
    $newQuantity = $currentQuantity - $quantity;

    if ($newQuantity >= 0) {
      // Update the stock quantity in the database
      $updateQuery = "UPDATE components SET stock_quantity = '$newQuantity' WHERE component_id = '$componentId'";
      if (mysqli_query($connection, $updateQuery)) {
        // Stock quantity adjusted successfully
        echo "Stock quantity adjusted successfully.";
      } else {
        // Error message
        echo "Error adjusting stock quantity: " . mysqli_error($connection);
      }
    } else {
      // Insufficient stock quantity
      echo "Insufficient stock quantity.";
    }
  } else {
    // Component not found
    echo "Component not found.";
  }
} else {
  // Invalid request method
  echo "Invalid request method.";
}
?>

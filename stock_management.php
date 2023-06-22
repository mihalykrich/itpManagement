<?php
include 'db.php';

// Handle inline editing and update the component quantity and location
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_component'])) {
  $componentId = mysqli_real_escape_string($connection, $_POST['component_id']);
  $newQuantity = mysqli_real_escape_string($connection, $_POST['new_quantity']);
  $newLocation = mysqli_real_escape_string($connection, $_POST['new_location']);

  $query = "UPDATE components SET stock_quantity = '$newQuantity', location = '$newLocation' WHERE component_id = '$componentId'";

  if (mysqli_query($connection, $query)) {
    // Success message
    echo "success";
    exit;
  } else {
    // Error message
    echo "error";
    exit;
  }
}

// Handle component deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_component'])) {
  $componentId = mysqli_real_escape_string($connection, $_POST['component_id']);

  $query = "DELETE FROM components WHERE component_id = '$componentId'";

  if (mysqli_query($connection, $query)) {
    // Success message
    echo "success";
    exit;
  } else {
    // Error message
    echo "error";
    exit;
  }
}

// Query the database to fetch all components
$query = "SELECT * FROM components";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Stock Management</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

  <!-- Include Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container">
    <h1>Stock Management</h1>

    <h2>Current Stock</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Part Number</th>
          <th>Part Name</th>
          <th>Stock Quantity</th>
          <th>Model Number</th>
          <th>Component ID</th>
          <th>Component Name</th>
          <th>Component Code</th>
          <th>Minimum Stock Level</th>
          <th>Manufacturer</th>
          <th>Supplier Part Number</th>
          <th>Unit Cost</th>
          <th>Location</th>
          <th>Last Restocked Date</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['part_number'] . "</td>";
            echo "<td>" . $row['part_name'] . "</td>";
            echo "<td class='quantity' data-component-id='" . $row['component_id'] . "' contenteditable='true'>" . $row['stock_quantity'] . "</td>";
            echo "<td>" . $row['model_number'] . "</td>";
            echo "<td>" . $row['component_id'] . "</td>";
            echo "<td>" . $row['component_name'] . "</td>";
            echo "<td>" . $row['component_code'] . "</td>";
            echo "<td>" . $row['min_stock_level'] . "</td>";
            echo "<td>" . $row['manufacturer'] . "</td>";
            echo "<td>" . $row['supplier_part_number'] . "</td>";
            echo "<td>" . $row['unit_cost'] . "</td>";
            echo "<td class='location' data-component-id='" . $row['component_id'] . "' contenteditable='true'>" . $row['location'] . "</td>";
            echo "<td>" . $row['last_restocked_date'] . "</td>";
            echo "<td>" . $row['notes'] . "</td>";
            echo "<td>";
            echo '<button class="btn btn-primary save-btn btn-sm"><i class="fas fa-save"></i></button>';
            echo '<button class="btn btn-danger delete-btn btn-sm"><i class="fas fa-trash-alt"></i></button>';
            echo "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='15'>No components found in stock.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Success modal -->
    <div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="success-modal-label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="success-modal-label">Success!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Component updated successfully!
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Include Bootstrap JS and jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      // Save button click event
      $('.save-btn').click(function() {
        var $row = $(this).closest('tr');
        var componentId = $row.find('.quantity').data('component-id');
        var newQuantity = $row.find('.quantity').text();
        var newLocation = $row.find('.location').text();

        // Send AJAX request to update the quantity and location
        $.ajax({
          type: 'POST',
          url: 'stock_management.php',
          data: {
            update_component: true,
            component_id: componentId,
            new_quantity: newQuantity,
            new_location: newLocation
          },
          success: function(response) {
            if (response === 'success') {
              // Show success modal
              $('#success-modal').modal('show');
              setTimeout(function() {
                $('#success-modal').modal('hide');
              }, 3000); // Hide after 3 seconds
            } else {
              // Error message or perform any other action
              console.log('Failed to update component.');
            }
          },
          error: function() {
            // Error message or perform any other action
            console.log('An error occurred while updating component.');
          }
        });
      });

      // Delete button click event
      $('.delete-btn').click(function() {
        var $row = $(this).closest('tr');
        var componentId = $row.find('.quantity').data('component-id');

        // Send AJAX request to delete the component
        $.ajax({
          type: 'POST',
          url: 'stock_management.php',
          data: {
            delete_component: true,
            component_id: componentId
          },
          success: function(response) {
            if (response === 'success') {
              // Remove the deleted row from the table
              $row.remove();
              console.log('Component deleted successfully!');
            } else {
              // Error message or perform any other action
              console.log('Failed to delete component.');
            }
          },
          error: function() {
            // Error message or perform any other action
            console.log('An error occurred while deleting component.');
          }
        });
      });
    });
  </script>
</body>
</html>

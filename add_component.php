<?php
include 'db.php';

// Handle form submission to add new components
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Extract and sanitize the form inputs
  $partNumber = mysqli_real_escape_string($connection, $_POST['part_number']);
  $partName = mysqli_real_escape_string($connection, $_POST['part_name']);
  $stockQuantity = mysqli_real_escape_string($connection, $_POST['stock_quantity']);

  // Additional fields
  $modelNumber = mysqli_real_escape_string($connection, $_POST['model_number']);
  $componentId = mysqli_real_escape_string($connection, $_POST['component_id']);
  $componentName = mysqli_real_escape_string($connection, $_POST['component_name']);
  $componentCode = mysqli_real_escape_string($connection, $_POST['component_code']);
  $minStockLevel = mysqli_real_escape_string($connection, $_POST['min_stock_level']);
  $manufacturer = mysqli_real_escape_string($connection, $_POST['manufacturer']);
  $supplierPartNumber = mysqli_real_escape_string($connection, $_POST['supplier_part_number']);
  $unitCost = mysqli_real_escape_string($connection, $_POST['unit_cost']);
  $location = mysqli_real_escape_string($connection, $_POST['location']);
  $lastRestockedDate = mysqli_real_escape_string($connection, $_POST['last_restocked_date']);
  $notes = mysqli_real_escape_string($connection, $_POST['notes']);

  // Insert the new component into the "components" table
  $query = "INSERT INTO components (part_number, part_name, stock_quantity, model_number, component_id, component_name, component_code, min_stock_level, manufacturer, supplier_part_number, unit_cost, location, last_restocked_date, notes) VALUES ('$partNumber', '$partName', '$stockQuantity', '$modelNumber', '$componentId', '$componentName', '$componentCode', '$minStockLevel', '$manufacturer', '$supplierPartNumber', '$unitCost', '$location', '$lastRestockedDate', '$notes')";

  if (mysqli_query($connection, $query)) {
    // Success message or redirection
    header('Location: stock_management.php');
    exit;
  } else {
    // Error message
    $error = "Error: " . mysqli_error($connection);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Component</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1>Add Component</h1>

    <form method="POST" action="add_component.php">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="part_number" class="form-label">Part Number</label>
          <input type="text" class="form-control" id="part_number" name="part_number" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="part_name" class="form-label">Part Name</label>
          <input type="text" class="form-control" id="part_name" name="part_name" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="stock_quantity" class="form-label">Stock Quantity</label>
          <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="model_number" class="form-label">Model Number</label>
          <input type="text" class="form-control" id="model_number" name="model_number" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="component_id" class="form-label">Component ID</label>
          <input type="text" class="form-control" id="component_id" name="component_id" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="component_name" class="form-label">Component Name</label>
          <input type="text" class="form-control" id="component_name" name="component_name" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="component_code" class="form-label">Component Code</label>
          <input type="text" class="form-control" id="component_code" name="component_code" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="min_stock_level" class="form-label">Minimum Stock Level</label>
          <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="manufacturer" class="form-label">Manufacturer</label>
          <input type="text" class="form-control" id="manufacturer" name="manufacturer" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="supplier_part_number" class="form-label">Supplier Part Number</label>
          <input type="text" class="form-control" id="supplier_part_number" name="supplier_part_number" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="unit_cost" class="form-label">Unit Cost</label>
          <input type="number" class="form-control" id="unit_cost" name="unit_cost" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="location" class="form-label">Location</label>
          <input type="text" class="form-control" id="location" name="location" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="last_restocked_date" class="form-label">Last Restocked Date</label>
          <input type="date" class="form-control" id="last_restocked_date" name="last_restocked_date" required>
        </div>
        <div class="col-md-8 mb-3">
          <label for="notes" class="form-label">Notes</label>
          <textarea class="form-control" id="notes" name="notes" rows="3" required></textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Add Component</button>
    </form>
  </div>

  <!-- Include Bootstrap JS and jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

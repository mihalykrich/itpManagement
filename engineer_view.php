<?php
include 'db.php';

// Retrieve the repair_id from the query string
$repairId = $_GET['repair_id'];

// Fetch the repair details from the database
$query = "SELECT * FROM repairs WHERE repair_id = " . $repairId;
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);

// Check if the repair exists
if (!$row) {
  die("Repair not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_component'])) {
    $componentId = $_POST['component'];
    $quantity = $_POST['quantity'];

    // Insert the selected component into the required_components table
    $insertQuery = "INSERT INTO required_components (repair_id, component_id, quantity) VALUES ($repairId, $componentId, $quantity)";
    mysqli_query($connection, $insertQuery);
  } elseif (isset($_POST['save'])) {
    $primaRepair = $_POST['prima_repair'];
    //$partsRequired = $_POST['parts_required'];
    $levelOfRepair = $_POST['level_of_repair'];
    $comments = $_POST['comments'];

    // Update the repair details in the database
    $updateQuery = "UPDATE repairs SET prima_repair = '$primaRepair', level_of_repair = '$levelOfRepair', comments = '$comments' WHERE repair_id = " . $repairId;
    mysqli_query($connection, $updateQuery);
  } elseif (isset($_POST['delete_component'])) {
    $componentId = $_POST['delete_component_id'];

    // Delete the selected component from the required_components table
    $deleteQuery = "DELETE FROM required_components WHERE id = $componentId";
    mysqli_query($connection, $deleteQuery);
  }
}

// Fetch components for the dropdown
$componentQuery = "SELECT * FROM components WHERE model_number = '" . $row['model_number'] . "'";
$componentResult = mysqli_query($connection, $componentQuery);
$components = mysqli_fetch_all($componentResult, MYSQLI_ASSOC);

// Fetch selected components for the table
$selectedComponentsQuery = "SELECT required_components.id, components.component_name, required_components.quantity FROM required_components JOIN components ON required_components.component_id = components.component_id WHERE required_components.repair_id = $repairId";
$selectedComponentsResult = mysqli_query($connection, $selectedComponentsQuery);
$selectedComponents = mysqli_fetch_all($selectedComponentsResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Engineer View - Repair Details</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

  <!-- Include Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container">
    <h1>Engineer View - Repair Details</h1>

    <div class="row">
      <div class="col-md-6">
        <!-- Column 1 -->
        <form method="POST" action="">
          <div class="form-group">
            <label for="sales_order">Sales Order:</label>
            <input type="text" class="form-control" id="sales_order" value="<?php echo $row['sales_order']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="model_number">Model Number:</label>
            <input type="text" class="form-control" id="model_number" value="<?php echo $row['model_number']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="unique_sn">Unique SN:</label>
            <input type="text" class="form-control" id="unique_sn" value="<?php echo $row['unique_sn']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="received_date">Received Date:</label>
            <input type="text" class="form-control" id="received_date" value="<?php echo $row['received_date']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="comments">Comments:</label>
            <textarea class="form-control" id="comments" name="comments" rows="3"><?php echo $row['comments']; ?></textarea>
          </div>
        </div>
        <div class="col-md-6">
          <!-- Column 2 -->
          <div class="form-group">
            <label for="job_number">Job Number:</label>
            <input type="text" class="form-control" id="job_number" value="<?php echo $row['job_number']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="model_type">Model Type:</label>
            <input type="text" class="form-control" id="model_type" value="<?php echo $row['model_type']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="reported_fault">Reported Fault:</label>
            <input type="text" class="form-control" id="reported_fault" value="<?php echo $row['reported_fault']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" class="form-control" id="status" value="<?php echo $row['status']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="shipped_date">Shipped Date:</label>
            <input type="text" class="form-control" id="shipped_date" value="<?php echo $row['shipped_date']; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="level_of_repair">Level of Repair:</label>
            <select class="form-control" id="level_of_repair" name="level_of_repair">
              <option value="Basic" <?php if ($row['level_of_repair'] === 'Basic') echo 'selected'; ?>>Basic</option>
              <option value="Intermediate" <?php if ($row['level_of_repair'] === 'Intermediate') echo 'selected'; ?>>Intermediate</option>
              <option value="Advanced" <?php if ($row['level_of_repair'] === 'Advanced') echo 'selected'; ?>>Advanced</option>
            </select>
          </div>
        </div>
      </div>

      <hr>

      <h3>Add Component</h3>
      <form method="POST" action="">
        <div class="form-row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="component">Component:</label>
              <select class="form-control" id="component" name="component" onchange="updateQuantityMax()">
                <?php foreach ($components as $component): ?>
                <option value="<?php echo $component['component_id']; ?>"><?php echo $component['component_name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="quantity">Quantity:</label>
              <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <button type="submit" name="add_component" class="btn btn-primary">Add Component</button>
            </div>
          </div>
        </div>
      
        <hr>

      <h3>Selected Components</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Component Name</th>
          <th>Quantity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($selectedComponents as $component): ?>
          <tr <?php if(isset($_POST['delete_component_id']) && $_POST['delete_component_id'] == $component['id']) echo 'class="table-danger"'; ?>>
            <td><?php echo $component['component_name']; ?></td>
            <td><?php echo $component['quantity']; ?></td>
            <td>
              <?php if(isset($_POST['delete_component_id']) && $_POST['delete_component_id'] == $component['id']): ?>
                <span class="badge bg-danger">Marked for Delete</span>
              <?php else: ?>
                <form method="POST" action="">
                  <!-- Hidden input field for component ID -->
                  <input type="hidden" name="delete_component_id" value="<?php echo $component['id']; ?>">
                  <button type="submit" name="delete_component" class="btn btn-danger delete-component">Delete</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

      <hr>

      <div class="form-group">
        <button type="submit" name="save" class="btn btn-primary" onclick="return confirm('Are you sure you want to save?')">Save</button>
      </div>
    </form>
  </div>

  <!-- Include Bootstrap JS and jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.delete-component').click(function() {
        $(this).closest('tr').toggleClass('table-danger');
      });
    });
  </script>
  <script>
  function updateQuantityMax() {
    var componentId = document.getElementById("component").value;

    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open("GET", "get_stock_quantity.php?component_id=" + componentId, true);

    // Set up the onload callback function
    xhr.onload = function() {
      if (xhr.status === 200) {
        var stockQuantity = parseInt(xhr.responseText);
        var quantityInput = document.getElementById("quantity");

        // Set the max attribute of the quantity input
        quantityInput.max = stockQuantity;

        // Reset the quantity value if it exceeds the new max value
        if (parseInt(quantityInput.value) > stockQuantity) {
          quantityInput.value = stockQuantity;
        }
      }
    };

    // Send the request
    xhr.send();
  }
</script>

</body>
</html>

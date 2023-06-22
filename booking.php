<?php
include 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Extract and sanitize the form inputs
  $salesOrder = mysqli_real_escape_string($connection, $_POST['sales_order']);
  $jobNumber = mysqli_real_escape_string($connection, $_POST['job_number']);
  $modelNumber = mysqli_real_escape_string($connection, $_POST['model_number']);
  $modelType = mysqli_real_escape_string($connection, $_POST['model_type']);
  $uniqueSN = mysqli_real_escape_string($connection, $_POST['unique_sn']);
  $reportedFault = mysqli_real_escape_string($connection, $_POST['reported_fault']);
  $receivedDate = mysqli_real_escape_string($connection, $_POST['received_date']);
  $shippedDate = mysqli_real_escape_string($connection, $_POST['shipped_date']);

  // Insert the data into the repairs table
  $query = "INSERT INTO repairs (sales_order, job_number, model_number, model_type, unique_sn, reported_fault, received_date, shipped_date) 
            VALUES ('$salesOrder', '$jobNumber', '$modelNumber', '$modelType', '$uniqueSN', '$reportedFault', '$receivedDate', '$shippedDate')";

  if (mysqli_query($connection, $query)) {
    // Success message or redirection
    header('Location: index.php');
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
  <title>Booking In - New Repair</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

  <!-- Include Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container">
  <h1>Booking In - New Repair</h1>
  <form method="POST" action="booking.php">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3">
          <label for="sales_order" class="form-label">Sales Order</label>
          <input type="text" class="form-control" id="sales_order" name="sales_order" required>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="job_number" class="form-label">Job Number</label>
          <input type="text" class="form-control" id="job_number" name="job_number" required>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="model_number" class="form-label">Model Number</label>
          <input type="text" class="form-control" id="model_number" name="model_number" required>
        </div>
      </div>
    </div>
    <!-- Remaining fields -->
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3">
          <label for="model_type" class="form-label">Model Type</label>
          <input type="text" class="form-control" id="model_type" name="model_type" required>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="unique_sn" class="form-label">Unique S/N</label>
          <input type="text" class="form-control" id="unique_sn" name="unique_sn" required>
        </div>
      </div>
      <div class="col-md-8">
        <div class="mb-3">
          <label for="reported_fault" class="form-label">Reported Fault By Customer</label>
          <textarea class="form-control" id="reported_fault" name="reported_fault" rows="3" required></textarea>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="received_date" class="form-label">Received Date</label>
          <input type="date" class="form-control" id="received_date" name="received_date">
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="shipped_date" class="form-label">Shipped Date</label>
          <input type="date" class="form-control" id="shipped_date" name="shipped_date">
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>


    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

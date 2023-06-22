<?php
include 'db.php';

// Check if the repair ID parameter is provided
if (isset($_GET['id'])) {
  $repairId = $_GET['id'];

  // Delete the row from the repairs table
  $query = "DELETE FROM repairs WHERE repair_id = '$repairId'";
  
  if (mysqli_query($connection, $query)) {
    // Deletion successful, set success message
    $successMessage = "Row deleted successfully.";
  } else {
    // Deletion failed
    $errorMessage = "Error: " . mysqli_error($connection);
  }
} else {
  // Invalid request, repair ID parameter is missing
  $errorMessage = "Invalid request. Repair ID parameter is missing.";
}

// Define the target page to redirect to
$targetPage = "index.php"; // Change this to the desired page

// Redirect after a short delay
echo "<script>
setTimeout(function() {
  window.location.href = '$targetPage';
}, 3000); // Redirect after 3 seconds
</script>";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Delete Row - Result</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container">
    <h1>Delete Row - Result</h1>
    <?php if (isset($successMessage)) : ?>
      <div class="alert alert-success" role="alert">
        <?php echo $successMessage; ?>
      </div>
    <?php elseif (isset($errorMessage)) : ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $errorMessage; ?>
      </div>
    <?php endif; ?>
    <p>Redirecting to <a href="<?php echo $targetPage; ?>"><?php echo $targetPage; ?></a>...</p>
  </div>

  <!-- Include Bootstrap JS and jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

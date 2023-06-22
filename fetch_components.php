<?php
include 'db.php';

if (isset($_POST['modelNumberFilter'])) {
  $modelNumberFilter = mysqli_real_escape_string($connection, $_POST['modelNumberFilter']);

  // Query the components table to fetch components based on the model number filter
  $query = "SELECT * FROM components WHERE model_number = '$modelNumberFilter'";

  $result = mysqli_query($connection, $query);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      // Generate the dropdown options with component names
      echo "<option value='" . $row['component_name'] . "'>" . $row['component_name'] . "</option>";
    }
  } else {
    echo "<option value=''>No components found</option>";
  }
}
?>

<?php
include 'db.php';

// Include MPDF library
require_once 'vendor/autoload.php';

// Function to generate a unique ID for the delete button
function generateButtonId($rowId) {
  return 'deleteButton' . $rowId;
}

// Function to generate PDF using MPDF
function generatePDF($row) {
  $mpdf = new \Mpdf\Mpdf();
  $html = "<h1>Repair Details - Job Number: " . $row['job_number'] . "</h1>";
  $html .= "<p>Sales Order: " . $row['sales_order'] . "</p>";
  $html .= "<p>Model Number: " . $row['model_number'] . "</p>";
  $html .= "<p>Unique SN: " . $row['unique_sn'] . "</p>";
  $html .= "<p>Received Date: " . $row['received_date'] . "</p>";
  $html .= "<p>Required Repair: " . $row['prima_repair'] . "</p>";
  $html .= "<p>Comments: " . $row['comments'] . "</p>";
  $html .= "<p>Job Number: " . $row['job_number'] . "</p>";
  $html .= "<p>Model Type: " . $row['model_type'] . "</p>";
  $html .= "<p>Reported Fault: " . $row['reported_fault'] . "</p>";
  $html .= "<p>Status: " . $row['status'] . "</p>";
  $html .= "<p>Shipped Date: " . $row['shipped_date'] . "</p>";
  $html .= "<p>Parts Required: " . $row['parts_required'] . "</p>";
  $html .= "<p>Level Of Repair: " . $row['level_of_repair'] . "</p>";
  
  $mpdf->WriteHTML($html);
  $mpdf->Output('repair_details_' . $row['job_number'] . '.pdf', \Mpdf\Output\Destination::INLINE);
}

// Function to build the WHERE clause for filtering
function buildFilterQuery($filters) {
  $conditions = array();

  if (!empty($filters['job_number'])) {
    $conditions[] = "job_number LIKE '%" . $filters['job_number'] . "%'";
  }
  if (!empty($filters['model_number'])) {
    $conditions[] = "model_number LIKE '%" . $filters['model_number'] . "%'";
  }
  if (!empty($filters['model_type'])) {
    $conditions[] = "model_type LIKE '%" . $filters['model_type'] . "%'";
  }
  if (!empty($filters['unique_sn'])) {
    $conditions[] = "unique_sn LIKE '%" . $filters['unique_sn'] . "%'";
  }
  if (!empty($filters['reported_fault'])) {
    $conditions[] = "reported_fault LIKE '%" . $filters['reported_fault'] . "%'";
  }
  if (!empty($filters['received_date'])) {
    $conditions[] = "received_date LIKE '%" . $filters['received_date'] . "%'";
  }
  if (!empty($filters['status'])) {
    $conditions[] = "status LIKE '%" . $filters['status'] . "%'";
  }
  if (!empty($filters['shipped_date'])) {
    $conditions[] = "shipped_date LIKE '%" . $filters['shipped_date'] . "%'";
  }

  if (count($conditions) > 0) {
    $query = "SELECT * FROM repairs WHERE " . implode(" AND ", $conditions);
  } else {
    $query = "SELECT * FROM repairs";
  }

  return $query;
}

// Initialize filters
$filters = array(
  'job_number' => '',
  'model_number' => '',
  'model_type' => '',
  'unique_sn' => '',
  'reported_fault' => '',
  'received_date' => '',
  'status' => '',
  'shipped_date' => '',
);

// Process filter form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($filters as $filterName => $filterValue) {
    $filters[$filterName] = $_POST[$filterName] ?? '';
  }
}

// Get filtered data
$query = buildFilterQuery($filters);
$result = mysqli_query($connection, $query);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Main View - Repairs</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <!-- Include Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Include custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container">
    <h1>Main View</h1>

    <!-- Filter form -->
    <form method="POST" class="mb-3">
      <div class="row g-3">
        <div class="col-md-2">
          <input type="text" class="form-control" placeholder="Job Number" name="job_number" value="<?php echo $filters['job_number']; ?>">
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control" placeholder="Model Number" name="model_number" value="<?php echo $filters['model_number']; ?>">
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control" placeholder="Model Type" name="model_type" value="<?php echo $filters['model_type']; ?>">
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control" placeholder="Unique S/N" name="unique_sn" value="<?php echo $filters['unique_sn']; ?>">
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control" placeholder="Reported Fault" name="reported_fault" value="<?php echo $filters['reported_fault']; ?>">
        </div>
        <div class="col-md-2">
          <input type="date" class="form-control" placeholder="Received Date" name="received_date" value="<?php echo $filters['received_date']; ?>">
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control" placeholder="Status" name="status" value="<?php echo $filters['status']; ?>">
        </div>
        <div class="col-md-2">
          <input type="date" class="form-control" placeholder="Shipped Date" name="shipped_date" value="<?php echo $filters['shipped_date']; ?>">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Filter</button>
        </div>
        <div class="col-auto">
          <a href="index.php" class="btn btn-secondary">Reset</a>
        </div>
      </div>
    </form>

    <table class="table">
      <thead>
        <tr>
          <th>Job Number</th>
          <th>Model Number</th>
          <th>Model Type</th>
          <th>Unique S/N</th>
          <th>Reported Fault By Customer</th>
          <th>Received Date</th>
          <th>Status</th>
          <th>Shipped Date</th>
          <th>Actions</th> <!-- New column for the buttons -->
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['job_number'] . "</td>";
            echo "<td>" . $row['model_number'] . "</td>";
            echo "<td>" . $row['model_type'] . "</td>";
            echo "<td>" . $row['unique_sn'] . "</td>";
            echo "<td>" . $row['reported_fault'] . "</td>";
            echo "<td>" . $row['received_date'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['shipped_date'] . "</td>";
            echo "<td>";
            echo "<div class='button-group'>"; // Add a container for the buttons
            echo "<a href='engineer_view.php?repair_id=" . $row['repair_id'] . "' class='btn btn-primary btn-sm' target='_blank'><i class='fa fa-wrench'></i></a>"; // Button for opening the engineer view
            echo "<button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#detailsModal" . $row['job_number'] . "'><i class='fa fa-eye'></i></button>"; // Button for opening the modal
            echo "<button class='btn btn-success btn-sm ms-1' onclick='generatePDF(" . json_encode($row) . ")'><i class='fa fa-file-pdf'></i></button>"; // Button for generating PDF
            echo "<button id='" . generateButtonId($row['repair_id']) . "' class='btn btn-danger btn-sm ms-1'><i class='fa fa-trash'></i></button>"; // Button for deletion
            echo "</div>"; // Close the container

            echo "</td>";
            echo "</tr>";

            // Modal for each row
            echo "<div class='modal fade' id='detailsModal" . $row['job_number'] . "' tabindex='-1' aria-labelledby='detailsModalLabel" . $row['job_number'] . "' aria-hidden='true'>";
            echo "<div class='modal-dialog'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='detailsModalLabel" . $row['job_number'] . "'>Details - Job Number: " . $row['job_number'] . "</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<div class='row'>";
            echo "<div class='col-6'>";
            // Column 1
            echo "<p>Sales Order: " . $row['sales_order'] . "</p>";
            echo "<p>Model Number: " . $row['model_number'] . "</p>";
            echo "<p>Unique SN: " . $row['unique_sn'] . "</p>";
            echo "<p>Received Date: " . $row['received_date'] . "</p>";
            echo "<p>Required Repair: " . $row['prima_repair'] . "</p>";
            echo "<p>Comments: " . $row['comments'] . "</p>";
            echo "</div>";
            echo "<div class='col-6'>";
            // Column 2
            echo "<p>Job Number: " . $row['job_number'] . "</p>";
            echo "<p>Model Type: " . $row['model_type'] . "</p>";
            echo "<p>Reported Fault: " . $row['reported_fault'] . "</p>";
            echo "<p>Status: " . $row['status'] . "</p>";
            echo "<p>Shipped Date: " . $row['shipped_date'] . "</p>";
            echo "<p>Level Of Repair: " . $row['level_of_repair'] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
            echo "<button class='btn btn-success' onclick='generatePDF(" . json_encode($row) . ")'>Generate PDF</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";

            // JavaScript for handling deletion
            echo "<script>
            document.getElementById('" . generateButtonId($row['repair_id']) . "').addEventListener('click', function() {
              if (confirm('Are you sure you want to delete this row?')) {
                window.location.href = 'delete.php?id=" . $row['repair_id'] . "';
              }
            });
            function generatePDF(row) {
              const data = JSON.stringify(row);
              fetch('generate_pdf.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: data,
              })
                .then(response => response.blob())
                .then(blob => {
                  const url = URL.createObjectURL(blob);
                  const a = document.createElement('a');
                  a.href = url;
                  a.download = 'repair_details_' + row['job_number'] + '.pdf';
                  a.click();
                })
                .catch(error => {
                  console.error('Error:', error);
                });
            }
            </script>";
          }
        } else {
          echo "<tr><td colspan='9'>No repairs found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Include Bootstrap JS and jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
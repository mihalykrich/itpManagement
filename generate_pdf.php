<?php
// Include MPDF library
require_once 'vendor/autoload.php';

// Get row data from the request
$row = json_decode(file_get_contents('php://input'), true);

// Generate PDF using MPDF
$mpdf = new \Mpdf\Mpdf();

// Define CSS styles
$css = "
    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }
    p {
        font-size: 12px;
        margin-bottom: 5px;
    }
    .logo {
        width: 200px;
        margin: 20px auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: left;
    }
";

// Add CSS styles to the PDF
$mpdf->WriteHTML("<style>{$css}</style>");

// Generate HTML content with styling
$html = '<img class="logo" src="images/logo.svg" alt="Logo">';
$html .= "<h1>Repair Details - Job Number: " . $row['job_number'] . "</h1>";
$html .= '<table>';
$html .= '<tr><th>Sales Order</th><td>' . $row['sales_order'] . '</td></tr>';
$html .= '<tr><th>Model Number</th><td>' . $row['model_number'] . '</td></tr>';
$html .= '<tr><th>Unique SN</th><td>' . $row['unique_sn'] . '</td></tr>';
$html .= '<tr><th>Received Date</th><td>' . $row['received_date'] . '</td></tr>';
$html .= '<tr><th>Required Repair</th><td>' . $row['prima_repair'] . '</td></tr>';
$html .= '<tr><th>Comments</th><td>' . $row['comments'] . '</td></tr>';
$html .= '<tr><th>Job Number</th><td>' . $row['job_number'] . '</td></tr>';
$html .= '<tr><th>Model Type</th><td>' . $row['model_type'] . '</td></tr>';
$html .= '<tr><th>Reported Fault</th><td>' . $row['reported_fault'] . '</td></tr>';
$html .= '<tr><th>Status</th><td>' . $row['status'] . '</td></tr>';
$html .= '<tr><th>Shipped Date</th><td>' . $row['shipped_date'] . '</td></tr>';
$html .= '<tr><th>Parts Required</th><td>' . $row['parts_required'] . '</td></tr>';
$html .= '<tr><th>Level Of Repair</th><td>' . $row['level_of_repair'] . '</td></tr>';
$html .= '</table>';

// Add HTML content to the PDF
$mpdf->WriteHTML($html);

// Output the PDF
$mpdf->Output('repair_details_' . $row['job_number'] . '.pdf', \Mpdf\Output\Destination::INLINE);
?>

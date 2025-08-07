<?php
session_start();
require_once("../includes/fpdf.php");

// ✅ Check if receipt data exists in session
if (!isset($_SESSION['receipt_data'])) {
    echo "No receipt data found.";
    exit;
}

// ✅ Fetch data from session
$data = $_SESSION['receipt_data'];

$patientName = $data['patient_name'] ?? 'N/A';
$appointmentDate = $data['appointment_date'] ?? 'N/A';
$doctorName = $data['doctor_name'] ?? 'N/A';
$paymentMethod = $data['payment_method'] ?? 'N/A';

// ✅ Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 16);
$pdf->Cell(0, 10, "Appointment Receipt", 0, 1, "C");

$pdf->SetFont("Arial", "", 12);
$pdf->Ln(10);
$pdf->Cell(0, 10, "Patient Name: $patientName", 0, 1);
$pdf->Cell(0, 10, "Doctor: $doctorName", 0, 1);
$pdf->Cell(0, 10, "Date: $appointmentDate", 0, 1);
$pdf->Cell(0, 10, "Payment Method: $paymentMethod", 0, 1);
$pdf->Ln(10);
$pdf->Cell(0, 10, "Thank you for using Sehat Guardian!", 0, 1);

// ✅ Output PDF to browser (force download)
$pdf->Output("D", "appointment_receipt.pdf");
?>

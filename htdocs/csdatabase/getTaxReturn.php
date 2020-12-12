<?php
session_start();
ob_start();
require('fpdf.php');

class PDF extends FPDF
{

    function Header()
    {
        // Select Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Framed title
        $this->Cell(30,10,'Tax Return',0,0,'C');
        // Line break
        $this->Ln(20);
    }   

    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial','I',8);
        // Print centered page number
        $this->Cell(0,10,'mytax3.kb-projects.com',0,0,'C');
    }
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
foreach($_SESSION['completedTR'] as $row_curr){
	$count = 0;
foreach ($row_curr as $key => &$value) {
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,12, $key . ": " . $value,1);
$count++;
if($count == 2){
$pdf->Ln(12);
$count = 0;
}
}
}
$pdf->output();
ob_end_flush();
?>
<?php
require('cellfit.php');

$txt_short = 'This text is short enough.';
$txt_long = 'This text is way too long.';
for ($i = 1; $i <= 2; $i++)
	$txt_long.=' '.$txt_long;

$pdf = new FPDF_CellFit();
$pdf->AddPage();
$pdf->SetFillColor(0xff,0xff,0x99);

$pdf->SetFont('Arial','B',16);
$pdf->Write(10,'Cell');
$pdf->SetFont('');
$pdf->Ln();
$pdf->Cell(0,10,$txt_short,1,1);
$pdf->Cell(0,10,$txt_long,1,1);
$pdf->Ln();

$pdf->Line($pdf->x,$pdf->y,$pdf->w-$pdf->x-$pdf->rMargin,$pdf->y);
$pdf->Ln();

$pdf->SetFont('','B');
$pdf->Write(10,'CellFitScale');
$pdf->SetFont('');
$pdf->Write(10,' (horizontal scaling only if necessary)');
$pdf->Ln();
$pdf->CellFitScale(0,10,$txt_short,1,1);
$pdf->CellFitScale(0,10,$txt_long,1,1,'',1);
$pdf->Ln();

$pdf->SetFont('','B');
$pdf->Write(10,'CellFitScaleForce');
$pdf->SetFont('');
$pdf->Write(10,' (horizontal scaling always)');
$pdf->Ln();
$pdf->CellFitScaleForce(0,10,$txt_short,1,1,'',1);
$pdf->CellFitScaleForce(0,10,$txt_long,1,1,'',1);
$pdf->Ln();

$pdf->Line($pdf->x,$pdf->y,$pdf->w-$pdf->x-$pdf->rMargin,$pdf->y);
$pdf->Ln();

$pdf->SetFont('','B');
$pdf->Write(10,'CellFitSpace');
$pdf->SetFont('');
$pdf->Write(10,' (character spacing only if necessary)');
$pdf->Ln();
$pdf->CellFitSpace(0,10,$txt_short,1,1);
$pdf->CellFitSpace(0,10,$txt_long,1,1,'',1);
$pdf->Ln();

$pdf->SetFont('','B');
$pdf->Write(10,'CellFitSpaceForce');
$pdf->SetFont('');
$pdf->Write(10,' (character spacing always)');
$pdf->Ln();
$pdf->CellFitSpaceForce(0,10,$txt_short,1,1,'',1);
$pdf->CellFitSpaceForce(0,10,$txt_long,1,1,'',1);

$pdf->Output();
?>

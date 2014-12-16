<?php
define("RELATIVE_PATH", "../");
include_once '../include/header.php';

$result = $db->Query("SELECT Naam, Adres, Gemeente, Tel, GSM, Email FROM speler s WHERE Gestopt IS NULL ORDER BY Naam");

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("TTC Erembodegem")
               ->setTitle("Ledenlijst TTC Erembodegem")
               ->setSubject("Ledenlijst TTC Erembodegem");

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Naam')
            ->setCellValue('B1', 'Adres')
            ->setCellValue('C1', 'Stad')
            ->setCellValue('D1', 'Tel')
            ->setCellValue('E1', 'GSM')
            ->setCellValue('F1', 'E-mail');

//$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

$rowIndex = 1;
while ($record = mysql_fetch_array($result))
{
  $rowIndex++;
  $objPHPExcel->getActiveSheet()
            ->setCellValue('A' . $rowIndex, $record['Naam'])
            ->setCellValue('B' . $rowIndex, $record['Adres'])
            ->setCellValue('C' . $rowIndex, $record['Gemeente'])
            ->setCellValue('D' . $rowIndex, $record['Tel'])
            ->setCellValue('E' . $rowIndex, $record['GSM'])
            ->setCellValue('F' . $rowIndex, $record['Email']);

  if ($record['Email'] != "")
  {
    $objPHPExcel->getActiveSheet()->getCell('F' . $rowIndex)->getHyperlink()->setUrl('mailto:' . $record['Email']);
  }
}

// Email color & underline
$objPHPExcel->getActiveSheet()->getStyle('F2:F' . $rowIndex)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
$objPHPExcel->getActiveSheet()->getStyle('F2:F' . $rowIndex)->getFont()->setUnderline(true);

// Set good column widths
foreach(range('A','F') as $columnID)
{
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Show grid
$styleArray = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
  );
$objPHPExcel->getActiveSheet()->getStyle('A1:F' . $rowIndex)->applyFromArray($styleArray);



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Ledenlijst');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->setActiveSheetIndex(0)->setSelectedCell("A1");

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ttcerembodegem_ledenlijst_'.date("d-m-Y").'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
//header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
/*header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
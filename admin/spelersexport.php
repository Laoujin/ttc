<?php
define("RELATIVE_PATH", "../");
include_once '../include/header.php';

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

function setHeader($objPHPExcel, $range)
{
  //$objPHPExcel->getActiveSheet()->getStyle($range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle($range)->getFont()->setBold(true);
}

function setAutoSize($objPHPExcel, $endColumn)
{
  foreach (range('A', $endColumn) as $columnID)
  {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }
}

function setGrid($objPHPExcel, $range)
{
  $styleArray = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
  );
  $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($styleArray);
}

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("TTC Erembodegem")
               ->setTitle("Ledenlijst TTC Erembodegem")
               ->setSubject("Ledenlijst TTC Erembodegem");

$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'Naam')
            ->setCellValue('B1', 'Adres')
            ->setCellValue('C1', 'Stad')
            ->setCellValue('D1', 'GSM')
            ->setCellValue('E1', 'E-mail');

setHeader($objPHPExcel, 'A1:E1');

$result = $db->Query("SELECT Naam, Adres, Gemeente, Tel, GSM, Email FROM speler s WHERE Gestopt IS NULL ORDER BY Naam");
$rowIndex = 1;
while ($record = mysql_fetch_array($result))
{
  $rowIndex++;
  $objPHPExcel->getActiveSheet()
            ->setCellValue('A' . $rowIndex, $record['Naam'])
            ->setCellValue('B' . $rowIndex, $record['Adres'])
            ->setCellValue('C' . $rowIndex, $record['Gemeente'])
            ->setCellValue('D' . $rowIndex, $record['GSM'])
            ->setCellValue('E' . $rowIndex, trim($record['Email']));

  if ($record['Email'] != "")
  {
    $objPHPExcel->getActiveSheet()->getCell('E' . $rowIndex)->getHyperlink()->setUrl('mailto:' . $record['Email']);
  }
}

// Email color & underline
$objPHPExcel->getActiveSheet()->getStyle('E2:E' . $rowIndex)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
$objPHPExcel->getActiveSheet()->getStyle('E2:E' . $rowIndex)->getFont()->setUnderline(true);

// Set good column widths
setAutoSize($objPHPExcel, 'E');

// Show grid
setGrid($objPHPExcel, 'A1:E' . $rowIndex);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Ledenlijst');


// -------------------------------------------------------------------------------------------------------- SHEET VTTL / Sporta

function createSterktelijst($objPHPExcel, $competitie, $result)
{
  $objPHPExcel->getActiveSheet()->setTitle($competitie);

  $objPHPExcel->getActiveSheet()
              ->setCellValue('A1', 'Volgnummer')
              ->setCellValue('B1', 'Index')
              ->setCellValue('C1', 'Lidnummer')
              ->setCellValue('D1', 'Naam')
              ->setCellValue('E1', 'Klassement');

  setHeader($objPHPExcel, 'A1:E1');

  
  $rowIndex = 1;
  while ($record = mysql_fetch_array($result))
  {
    $rowIndex++;
    $objPHPExcel->getActiveSheet()
              ->setCellValue('A' . $rowIndex, $record['Volgnummer'])
              ->setCellValue('B' . $rowIndex, $record['Indexy'])
              ->setCellValue('C' . $rowIndex, $record['ComputerNummer'])
              ->setCellValue('D' . $rowIndex, $record['Naam'])
              ->setCellValue('E' . $rowIndex, $record['Klassement']);
  }

  setAutoSize($objPHPExcel, 'E');
  setGrid($objPHPExcel, 'A1:E' . $rowIndex);

  $objPHPExcel->getActiveSheet()->setSelectedCell("A1");
}


$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);

$result = $db->Query("SELECT Naam, KlassementVTTL AS Klassement, ComputerNummerVTTL AS ComputerNummer, VolgnummerVTTL AS Volgnummer, IndexVTTL AS Indexy
                      FROM speler s WHERE ClubIdVTTL=".CLUB_ID." AND Gestopt IS NULL ORDER BY VolgnummerVTTL");

createSterktelijst($objPHPExcel, "VTTL", $result);

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);

$result = $db->Query("SELECT Naam, KlassementSporta AS Klassement, LidNummerSporta AS ComputerNummer, VolgnummerSporta AS Volgnummer, IndexSporta AS Indexy
                      FROM speler s WHERE ClubIdSporta=".CLUB_ID." AND Gestopt IS NULL ORDER BY VolgnummerSporta");

createSterktelijst($objPHPExcel, "Sporta", $result);


// -------------------------------------------------------------------------------------------------------- WRITE EXCEL

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
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
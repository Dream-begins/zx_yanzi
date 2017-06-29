<?php 
/** PHPExcel */  
require_once  'Classes/PHPExcel.php';
require_once  'Classes/PHPExcel/Writer/Excel2007.php';  
function export($headers,$datas,$fn) 
{
	ini_set ('memory_limit', '512M');
	date_default_timezone_set('PRC');
	$objPHPExcel = new PHPExcel();   
	$sheet = $objPHPExcel->getActiveSheet();
	for($i=0;$i<count($headers);$i++)
	{
		if($i>=26)
		{
			$j = intval($i/26)-1;
			$k = $i%26;
			$col = chr(ord('A')+$j).chr(ord('A')+$k);
		}
		else
			$col=chr(ord('A')+$i);
		$sheet->setCellValue($col."1",$headers[$i]); 
		for($j=0;$j<count($datas);$j++)
		{
			@$sheet->setCellValue($col.($j+2),$datas[$j][$i]);  
		}
	}
	//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);  
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$fn.'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

	
}

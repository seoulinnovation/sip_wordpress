<?php
define('ROOT', $_SERVER['DOCUMENT_ROOT']);	
require_once ROOT .'/wp-load.php';
require_once plugin_dir_path(__FILE__) . '/Classes/PHPExcel.php';

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Seoul Innovation Research Lab")
							 ->setLastModifiedBy("Seoul Innovation Research Lab")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Newsletter Order file");

// Get Newsletter form Wordpress Custom Post Type
$args = array(
	'post_type' => 'newsletter',
	'posts_per_page' => -1,
	'orderby' => 'date', 
	'order' => 'DESC'
);
$newsletter = new WP_Query($args);
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '번호')
            ->setCellValue('B1', 'E-mail')
            ->setCellValue('C1', '신청 날짜');
$i=1;
$j=2;
while($newsletter->have_posts()): $newsletter->the_post();
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j, $i)
				->setCellValue('B'.$j, get_the_title())
				->setCellValue('C'.$j, get_the_date('Y년 m월 d일 H:i:s'));
	$i++;
	$j++;
endwhile;
// Rename worksheet
foreach(range('A','C') as $columnID){
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->setTitle('Newsletter Order');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$filename = '뉴스레터'.date('Ymdhis', time()).'.xls';
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'"');
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
exit;
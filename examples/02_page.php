<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pdfPath = __DIR__ . '/' . basename(__FILE__, '.php') . '-output.pdf';

use trogon\otuspdf\Document;
use trogon\otuspdf\Page;

use trogon\otuspdf\io\DocumentWriter;

use trogon\otuspdf\meta\PageOrientationInfo;
use trogon\otuspdf\meta\PageSizeInfo;


$doc = new Document();
$page = $doc->pages->add();
$page->addParagraph()->addRun("A5, Portrait");

$page = $doc->pages->add([
    'orientation' => PageOrientationInfo::getLandscape(),
    'size' => PageSizeInfo::getA5()
]);
$page->addText("A5, Landscape");

$page = $doc->pages->add([
    'orientation' => PageOrientationInfo::getLandscape()
]);
$page->addText("Landscape");

$page = $doc->pages->add([
    'size' => PageSizeInfo::getA4()
]);
$page->addText("A4");

$page = $doc->pages[] = new Page([
    'orientation' => PageOrientationInfo::getPortrait()
]);
$page->addText("Portrait");

$writer = new DocumentWriter($doc);

//echo $writer->transcode($pdfPath);
$writer->save($pdfPath);
$content = $writer->toString('pdf');

// [RFC6266] Direct the UA to display PDF document, with a filename of "example document.pdf" if not supported to display:
header('Content-Disposition: inline; filename="example document.pdf"');
// [RFC7233] Indicates the media type
header('Content-Type: application/pdf');
//header('Cache-Control: private, max-age=0, must-revalidate');
//header('Pragma: public');

echo $content;

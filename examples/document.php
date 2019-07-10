<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pdfPath = __DIR__ . '/document-output.pdf';

use trogon\otuspdf\Document;
use trogon\otuspdf\Page;
use trogon\otuspdf\Text;
use trogon\otuspdf\io\DocumentWriter;

use trogon\otuspdf\meta\PageOrientationInfo;
use trogon\otuspdf\meta\PageSizeInfo;

use trogon\otuspdf\io\FontReader;

$doc = new Document([
    'title' => 'A1',
    'author' => 'B2',
    'subject' => 'C3',
    'keywords' => 'D4'
]);
$page = $doc->pages->add();
$page->addText("ABC very example text1\nTesting text in new line2");
$page->addText("ABC very example text3\nTesting text in new line4");
$page->addText("ABC very example text5\nTesting text in new line6");
$page->addText("This is a bit too long text. Anyway, the formatter should wrap this line to new line. So the reader can see entire text.");
$page->addText("This is font TEST Helvetica", [
    'fontFamily' => 'Helvetica'
]);
$page->addText("This is font TEST Times", [
    'fontFamily' => 'Times-Roman'
]);
$page->addText("This is font TEST Courier", [
    'fontFamily' => 'Courier'
]);
$page->addText("ABC very example text1\nTesting text in new line2", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 10
]);
$page->addText("ABC very example text1\nTesting text in new line2", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 16
]);
$page->addText("ABC very example text1\nTesting text in new line2", [
    'fontFamily' => 'Courier',
    'fontSize' => 12
]);
$page = $doc->pages->add([
    'orientation' => PageOrientationInfo::getPortrait(),
    'size' => PageSizeInfo::getA5()
]);
$page->addText("A5, Portrait");
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
$page = $doc->pages->add();
$page = $doc->pages->add();

$writer = new DocumentWriter($doc);

//echo $writer->transcode($pdfPath);
$writer->save($pdfPath);
$content = $writer->toString('pdf');

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="doc.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

echo $content;

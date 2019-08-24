<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pdfPath = __DIR__ . '/' . basename(__FILE__, '.php') . '-output.pdf';

use trogon\otuspdf\Document;

use trogon\otuspdf\io\DocumentWriter;

$doc = new Document([
    'title' => 'A1',
    'author' => 'B2',
    'subject' => 'C3',
    'keywords' => 'D4'
]);
$page = $doc->pages->add();

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

<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pdfPath = __DIR__ . '/' . basename(__FILE__, '.php') . '-output.pdf';

use trogon\otuspdf\Document;
use trogon\otuspdf\Paragraph;

use trogon\otuspdf\Table;
use trogon\otuspdf\TableColumn;
use trogon\otuspdf\TableRowGroup;
use trogon\otuspdf\TableRow;
use trogon\otuspdf\TableCell;

use trogon\otuspdf\io\DocumentWriter;

use trogon\otuspdf\meta\PageOrientationInfo;
use trogon\otuspdf\meta\PageSizeInfo;

$doc = new Document([
    'title' => 'A1',
    'author' => 'B2',
    'subject' => 'C3',
    'keywords' => 'D4'
]);
$page = $doc->pages->add([
    'orientation' => PageOrientationInfo::getPortrait(),
    'size' => PageSizeInfo::getA5()
]);
$page->addParagraph()->addRun("A5, Portrait");

$page->addParagraph()->addRun("Under this text is table.");

for ($i=0; $i < 1; $i++) {
    $page->addParagraph()->addRun("Text before table block. Should be visible before table is written.");

    // Table
    $table = new Table();
    $page->blocks->add($table);

    $table->addColumn();
    $table->addColumn();

    $rowGroup = $table->addRowGroup();

    $row = $rowGroup->addRow();

    $par = new Paragraph();
    $par->addRun('Table content G1 A1');
    $cell = $row->addCell()->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G1 B1');
    $cell = $row->addCell()->blocks->add($par);

    $row = $rowGroup->addRow();

    $cell = $row->addCell();

    $par = new Paragraph();
    $par->addRun('Table content G1 A2');
    $cell->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Next par content');
    $cell->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G1 B2');
    $par->addRun('More more more more more more more more more more content');
    $par->addRun(' e '); $par->addRun(' more more more more more more more more content');
    $cell = $row->addCell()->blocks->add($par);

    $row = $rowGroup->addRow();

    $cell = $row->addCell();

    $par = new Paragraph();
    $par->addRun('Table content G1 A3');
    $cell->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Next par content with a lot of content terramanificientelshooperationABCDEFGHIJKLMNOPRSTUVWYXZ');
    $cell->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G1 B3');
    $cell = $row->addCell()->blocks->add($par);

    $rowGroup = $table->addRowGroup();

    $row = $rowGroup->addRow();

    $par = new Paragraph();
    $par->addRun('Table content G2 A1');
    $cell = $row->addCell()->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G2 B1');
    $cell = $row->addCell()->blocks->add($par);

    $row = $rowGroup->addRow();

    $par = new Paragraph();
    $par->addRun('Table content G2 A2');
    $cell = $row->addCell()->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G2 B2');
    $cell = $row->addCell()->blocks->add($par);

    $row = $rowGroup->addRow();

    $par = new Paragraph();
    $par->addRun('Table content G2 A3');
    $cell = $row->addCell()->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G2 B3');
    $cell = $row->addCell()->blocks->add($par);

    $page->addParagraph()->addRun("Text after table block. Should be visible before table is written.");
}

// Table
$table = new Table();
$page->blocks->add($table);

$table->columns->add(new TableColumn());
$table->columns->add(new TableColumn());

$rowGroup = $table->rowGroups->add(new TableRowGroup());

$row = $rowGroup->rows->add(new TableRow());

$paragraph = new Paragraph();
$paragraph->addRun("Single cell table");
$cell = $row->cells->add(new TableCell());
$cell->blocks->add($paragraph);
// End Table

$page->addPagebreak();

for ($i=0; $i < 2; $i++) {
    $page->addParagraph()->addRun("Text before table block. Should be visible before table is written.");

    // Table
    $table = new Table();
    $page->blocks->add($table);

    $table->addColumn();
    $table->addColumn();

    $rowGroup = $table->addRowGroup();

    $row = $rowGroup->addRow();

    $par = new Paragraph();
    $par->addRun('Table content G1 A1', [
        'fontSize' => 9,
    ]);
    $cell = $row->addCell()->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G1 B1', [
        'fontSize' => 9,
    ]);
    $cell = $row->addCell()->blocks->add($par);

    $row = $rowGroup->addRow();

    $cell = $row->addCell();

    $par = new Paragraph();
    $par->addRun('Table content G1 A2', [
        'fontSize' => 9,
    ]);
    $cell->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Next par content', [
        'fontSize' => 9,
    ]);
    $cell->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G1 B2', [
        'fontSize' => 9,
    ]);
    $par->addRun('More more more more more more more more more more content', [
        'fontSize' => 9,
    ]);
    $par->addRun(' e '); $par->addRun(' more more more more more more more more content', [
        'fontSize' => 9,
    ]);
    $cell = $row->addCell()->blocks->add($par);

    $row = $rowGroup->addRow();

    $cell = $row->addCell();

    $par = new Paragraph();
    $par->addRun('Table content G1 A3');
    $cell->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Next par content with a lot of content terramanificientelshooperationABCDEFGHIJKLMNOPRSTUVWYXZ');
    $cell->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G1 B3');
    $cell = $row->addCell()->blocks->add($par);

    $rowGroup = $table->addRowGroup();

    $row = $rowGroup->addRow();

    $par = new Paragraph();
    $par->addRun('Table content G2 A1');
    $cell = $row->addCell()->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G2 B1');
    $cell = $row->addCell()->blocks->add($par);

    $row = $rowGroup->addRow();

    $par = new Paragraph();
    $par->addRun('Table content G2 A2');
    $cell = $row->addCell()->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G2 B2');
    $cell = $row->addCell()->blocks->add($par);

    $row = $rowGroup->addRow();

    $par = new Paragraph();
    $par->addRun('Table content G2 A3');
    $cell = $row->addCell()->blocks->add($par);

    $par = new Paragraph();
    $par->addRun('Table content G2 B3');
    $cell = $row->addCell()->blocks->add($par);

    $page->addParagraph()->addRun("Text after table block. Should be visible before table is written.");
}

$page = $doc->pages->add([
    'orientation' => PageOrientationInfo::getLandscape(),
    'size' => PageSizeInfo::getA5()
]);
$page->addText("A5, Landscape");

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

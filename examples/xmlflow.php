<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pdfPath = __DIR__ . '/xmlflow-output.pdf';

use trogon\otuspdf\Document;
use trogon\otuspdf\io\DocumentParserXmlFlow;
use trogon\otuspdf\io\DocumentWriter;

$parster = new DocumentParserXmlFlow();
$doc = $parster->processDocument('<?xml version="1.0" encoding="UTF-8"?>
<FlowDocument xmlns="http://schemas.microsoft.com/winfx/2006/xaml/presentation"
    xmlns:x="http://schemas.microsoft.com/winfx/2006/xaml">

    <Paragraph>
        <Bold>Some bold text in the paragraph.</Bold>
        Some text that is not bold.
    </Paragraph>

    <List>
        <ListItem>
            <Paragraph>ListItem 1</Paragraph>
        </ListItem>
        <ListItem>
            <Paragraph>ListItem 2</Paragraph>
        </ListItem>
        <ListItem>
            <Paragraph>ListItem 3</Paragraph>
        </ListItem>
    </List>

    <Section Background="Red">
        <Paragraph>
            Paragraph 1
        </Paragraph>
        <Paragraph>
            Paragraph 2
        </Paragraph>
        <Paragraph>
            Paragraph 3
        </Paragraph>
    </Section>

    <Paragraph>
        <Run>Paragraph that explicitly uses the Run element.</Run>
    </Paragraph>

    <Paragraph>
        Text before the Span. <Span Background="Red">Text within the Span is
        red and <Bold>this text is inside the Span-derived element Bold.</Bold>
        A Span can contain more then text, it can contain any inline content. For
        example, it can contain a or other UIElement, a Floater, a Figure, etc.</Span>
    </Paragraph>

    <Paragraph>
        Before the LineBreak in Paragraph.
        <LineBreak />
        After the LineBreak in Paragraph.
        <LineBreak/><LineBreak/>
        After two LineBreaks in Paragraph.
    </Paragraph>

    <!-- Normally a table would have multiple rows and multiple
    cells but this code is for demonstration purposes.-->
    <Table>
        <TableRowGroup>
            <TableRow>
                <TableCell>
                    <Paragraph>
                        <!-- The schema does not actually require
                            explicit use of the Run tag in markup. It 
                            is only included here for clarity. -->
                        <Run>Paragraph in a Table Cell.</Run>
                    </Paragraph>
                </TableCell>
            </TableRow>
        </TableRowGroup>
    </Table>

</FlowDocument>
');

$writer = new DocumentWriter($doc);
$content = $writer->toString();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="doc.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

echo $content;

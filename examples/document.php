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
$page->addText("This is a bit too long text. Anyway, the formatter should wrap this line to new line. So the reader can see entire text.\nThis is a bit too long text. Anyway, the formatter should wrap this line to new line. So the reader can see entire text.");
$page->addText("ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY ILNY");
$page->addText("I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY");

$fontFamily = FontReader::load('tahomabd.ttf');

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

$page->addText("       Ala    ma,     kota, . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , . . , .  \n ... ale nie ma psa");
$page->addText("   This is a bit too long text. Anyway, the formatter should wrap this line to new       line. So the reader can see entire text.\nThis forcet new line and it is a bit too long text. Anyway, the formatter should wrap this line to new line. So the reader can see entire text. Anyway,   the   formatter   should   wrap   this   line   to   new   line.   So   the   reader   can   see   entire   text.");
$page->addText("       Ala    ma  \t   kota  \n ale nie ma psa  的的的的\n    I♥NY I♥NY I♥NY\t I♥NY      I♥NY I♥NY I♥NY\nI♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY I♥NY     ");

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
$page->addText("This is simple text before page break.\nThis is simple text before page break.\nThis is simple text before page break.\nThis is simple text before page break.");
$page->addPageBreak();
$page->addText("This is simple text after page break.\nThis is simple text after page break.\nThis is simple text after page break.\nThis is simple text after page break.");

$page = $doc->pages->add([
    'orientation' => PageOrientationInfo::getLandscape()
]);
$page->addText("Landscape");
$page->addText("10 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Courier',
    'fontSize' => 10
]);
$page->addText("11 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Courier',
    'fontSize' => 11
]);
$page->addText("12 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Courier',
    'fontSize' => 12
]);
$page->addText("14 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Courier',
    'fontSize' => 14
]);
$page->addText("16 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Courier',
    'fontSize' => 16
]);
$page->addText("18 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Courier',
    'fontSize' => 18
]);
$page->addText("20 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Courier',
    'fontSize' => 20
]);
$page = $doc->pages->add([
    'size' => PageSizeInfo::getA4()
]);
$page->addText("A4");
$page->addText("10 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 10
]);
$page->addText("11 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 11
]);
$page->addText("12 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 12
]);
$page->addText("14 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 14
]);
$page->addText("16 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 16
]);
$page->addText("18 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 18
]);
$page->addText("20 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 20
]);
$page->addText("24 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica',
    'fontSize' => 24
]);
$page = $doc->pages[] = new Page([
    'orientation' => PageOrientationInfo::getPortrait()
]);
$page->addText("Portrait");
$page = $doc->pages->add();
$page->addText("10 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 10
]);
$page->addText("11 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 11
]);
$page->addText("12 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 12
]);
$page->addText("14 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 14
]);
$page->addText("16 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 16
]);
$page->addText("18 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 18
]);
$page->addText("20 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 20
]);
$page->addText("24 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 24
]);
$page = $doc->pages->add();
$page->addText("10 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica-Bold',
    'fontSize' => 10
]);
$page->addText("11 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica-Bold',
    'fontSize' => 11
]);
$page->addText("12 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica-Bold',
    'fontSize' => 12
]);
$page->addText("14 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica-Bold',
    'fontSize' => 14
]);
$page->addText("16 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica-Bold',
    'fontSize' => 16
]);
$page->addText("18 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica-Bold',
    'fontSize' => 18
]);
$page->addText("20 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica-Bold',
    'fontSize' => 20
]);
$page->addText("24 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Helvetica-Bold',
    'fontSize' => 24
]);
$page = $doc->pages->add();
$page->addText("Landscape");
$page->addText("10 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Italic',
    'fontSize' => 10
]);
$page->addText("11 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Italic',
    'fontSize' => 11
]);
$page->addText("12 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Italic',
    'fontSize' => 12
]);
$page->addText("14 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Italic',
    'fontSize' => 14
]);
$page->addText("16 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Italic',
    'fontSize' => 16
]);
$page->addText("18 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Italic',
    'fontSize' => 18
]);
$page->addText("20 a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z a b c d e f g h i j k l m n o p r s t u w y z A B C D E F G H I J K L M N O P R S T U W Y Z", [
    'fontFamily' => 'Times-Italic',
    'fontSize' => 20
]);
$page = $doc->pages->add();
$page->addText("Landscape");
$page->addText("Lorem text ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed sapien convallis, sollicitudin massa accumsan, sodales massa. Pellentesque ac pellentesque lorem, a pellentesque mauris. Vivamus neque urna, semper pharetra neque nec, dictum volutpat justo. Sed tempus congue ex, eget ornare mauris pellentesque at. Duis at mattis dui. Integer suscipit arcu a efficitur luctus. Morbi at dui eu neque aliquam dapibus. Aliquam quis bibendum ante. Vestibulum nec eleifend nunc. Morbi ac leo non quam convallis mattis sed scelerisque risus. Donec sem erat, convallis facilisis vehicula sit amet, gravida ut ex. Aliquam accumsan libero justo, vel facilisis est ornare ac.

Aenean gravida sed elit vel ultricies. Nulla mauris odio, fermentum in accumsan sit amet, commodo non est. Sed ac interdum sapien, sit amet luctus lorem. Aenean ut lorem dapibus, pretium mi sed, tincidunt turpis. Aenean accumsan neque metus, sit amet lobortis metus tristique sed. Fusce at semper enim. Nullam sed velit a mi vestibulum luctus sit amet consequat nisi. Nulla eget nunc sollicitudin nulla finibus consequat.

Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper finibus ultrices. Ut ut suscipit enim. Nunc lobortis diam ornare metus sollicitudin ornare. Etiam ultricies eu nulla sit amet aliquet. Phasellus ultricies lorem enim, auctor malesuada purus pretium in. Proin ultricies leo ac facilisis posuere. Nulla rhoncus laoreet lectus sed vulputate. Fusce aliquam elit ac varius interdum. Vestibulum ut tristique diam, vel lobortis arcu. Quisque et sapien id diam tincidunt dapibus. Nulla consectetur, est eget sodales egestas, orci nisl posuere eros, vel dignissim turpis felis vel mauris.

Nunc ac ligula elementum, cursus urna eu, efficitur nulla. Fusce gravida tempor semper. Maecenas nec risus massa. Mauris sem quam, placerat eget felis non, posuere tincidunt est. Praesent purus nibh, elementum a facilisis eget, tristique maximus lorem. Maecenas felis nisi, laoreet non rutrum non, hendrerit in nisl. Donec volutpat tempus lectus, in malesuada felis. Etiam scelerisque dictum lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare elit id est gravida, sit amet ultricies dolor ornare. Quisque tincidunt felis at malesuada varius. Aliquam erat volutpat. Maecenas pellentesque justo eu justo tristique, ac sagittis nulla sollicitudin. Proin elementum magna sed nunc hendrerit, eu porttitor dolor sodales. Morbi massa urna, consequat id velit in, suscipit gravida tortor. In fringilla nisl nec massa eleifend, vitae fringilla dolor viverra.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec viverra elit a auctor venenatis. Morbi fringilla nisl sit amet ex dictum, eu eleifend urna convallis. Suspendisse ut tellus id ex ornare sagittis a ut risus. Sed sollicitudin ipsum erat, vel venenatis magna iaculis eu. Integer faucibus ipsum massa, sit amet imperdiet nibh sagittis non. Integer vehicula, massa ac pellentesque luctus, sapien justo luctus justo, ac vehicula sapien enim eget lectus. Duis bibendum vitae velit vitae tempor. Quisque feugiat faucibus varius. Morbi elementum rutrum massa vel dignissim. Mauris ex risus, volutpat nec nisi et, porta molestie velit. Ut quis consequat elit.", [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 12
]);

$page = $doc->pages->add();
$paragraphStyle = [
    'textIndent' => '1'
];
$runStyle = [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 12
];
$par = $page->addParagraph($paragraphStyle);
$par->addRun(
    "Lorem paragraph ",
    $runStyle
);
$par->addRun(
    "ipsum dolor sit ",
    $runStyle
);
$par->addRun(
    "amet, consectetur ",
    $runStyle
);
$par->addRun(
    "adipiscing elit. ",
    $runStyle
);
$par->addRun(
    "Lorem paragraph ",
    $runStyle
);
$par->addRun(
    "ipsum dolor sit ",
    $runStyle
);
$par->addRun(
    "amet, consectetur ",
    $runStyle
);
$par->addRun(
    "adipiscing elit.\n",
    $runStyle
);
$page->addParagraph()->addRun("Landscape");
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Lorem paragraph ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed sapien convallis, sollicitudin massa accumsan, sodales massa. Pellentesque ac pellentesque lorem, a pellentesque mauris. Vivamus neque urna, semper pharetra neque nec, dictum volutpat justo. Sed tempus congue ex, eget ornare mauris pellentesque at. Duis at mattis dui. Integer suscipit arcu a efficitur luctus. Morbi at dui eu neque aliquam dapibus. Aliquam quis bibendum ante. Vestibulum nec eleifend nunc. Morbi ac leo non quam convallis mattis sed scelerisque risus. Donec sem erat, convallis facilisis vehicula sit amet, gravida ut ex. Aliquam accumsan libero justo, vel facilisis est ornare ac.\n",
        $runStyle
    );
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Aenean gravida sed elit vel ultricies. Nulla mauris odio, fermentum in accumsan sit amet, commodo non est. Sed ac interdum sapien, sit amet luctus lorem. Aenean ut lorem dapibus, pretium mi sed, tincidunt turpis. Aenean accumsan neque metus, sit amet lobortis metus tristique sed. Fusce at semper enim. Nullam sed velit a mi vestibulum luctus sit amet consequat nisi. Nulla eget nunc sollicitudin nulla finibus consequat.\n",
        $runStyle
    );
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper finibus ultrices. Ut ut suscipit enim. Nunc lobortis diam ornare metus sollicitudin ornare. Etiam ultricies eu nulla sit amet aliquet. Phasellus ultricies lorem enim, auctor malesuada purus pretium in. Proin ultricies leo ac facilisis posuere. Nulla rhoncus laoreet lectus sed vulputate. Fusce aliquam elit ac varius interdum. Vestibulum ut tristique diam, vel lobortis arcu. Quisque et sapien id diam tincidunt dapibus. Nulla consectetur, est eget sodales egestas, orci nisl posuere eros, vel dignissim turpis felis vel mauris.\n",
        $runStyle
    );
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Nunc ac ligula elementum, cursus urna eu, efficitur nulla. Fusce gravida tempor semper. Maecenas nec risus massa. Mauris sem quam, placerat eget felis non, posuere tincidunt est. Praesent purus nibh, elementum a facilisis eget, tristique maximus lorem. Maecenas felis nisi, laoreet non rutrum non, hendrerit in nisl. Donec volutpat tempus lectus, in malesuada felis. Etiam scelerisque dictum lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare elit id est gravida, sit amet ultricies dolor ornare. Quisque tincidunt felis at malesuada varius. Aliquam erat volutpat. Maecenas pellentesque justo eu justo tristique, ac sagittis nulla sollicitudin. Proin elementum magna sed nunc hendrerit, eu porttitor dolor sodales. Morbi massa urna, consequat id velit in, suscipit gravida tortor. In fringilla nisl nec massa eleifend, vitae fringilla dolor viverra.\n",
        $runStyle
    );
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec viverra elit a auctor venenatis. Morbi fringilla nisl sit amet ex dictum, eu eleifend urna convallis. Suspendisse ut tellus id ex ornare sagittis a ut risus. Sed sollicitudin ipsum erat, vel venenatis magna iaculis eu. Integer faucibus ipsum massa, sit amet imperdiet nibh sagittis non. Integer vehicula, massa ac pellentesque luctus, sapien justo luctus justo, ac vehicula sapien enim eget lectus. Duis bibendum vitae velit vitae tempor. Quisque feugiat faucibus varius. Morbi elementum rutrum massa vel dignissim. Mauris ex risus, volutpat nec nisi et, porta molestie velit. Ut quis consequat elit.\n",
        $runStyle
    );

$page = $doc->pages->add();
$paragraphStyle = [];
$runStyle = [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 12
];
$par = $page->addParagraph($paragraphStyle);
$par->addRun(
    "Lorem paragraph ",
    $runStyle
);
$par->addRun(
    "ipsum dolor sit ",
    $runStyle
);
$par->addRun(
    "amet, consectetur ",
    $runStyle
);
$par->addRun(
    "adipiscing elit. ",
    $runStyle
);
$par->addRun(
    "Lorem paragraph ",
    $runStyle
);
$par->addRun(
    "ipsum dolor sit ",
    $runStyle
);
$par->addRun(
    "amet, consectetur ",
    $runStyle
);
$par->addRun(
    "adipiscing elit.\n",
    $runStyle
);
$page->addParagraph()->addRun("Landscape");
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Lorem paragraph ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed sapien convallis, sollicitudin massa accumsan, sodales massa. Pellentesque ac pellentesque lorem, a pellentesque mauris. Vivamus neque urna, semper pharetra neque nec, dictum volutpat justo. Sed tempus congue ex, eget ornare mauris pellentesque at. Duis at mattis dui. Integer suscipit arcu a efficitur luctus. Morbi at dui eu neque aliquam dapibus. Aliquam quis bibendum ante. Vestibulum nec eleifend nunc. Morbi ac leo non quam convallis mattis sed scelerisque risus. Donec sem erat, convallis facilisis vehicula sit amet, gravida ut ex. Aliquam accumsan libero justo, vel facilisis est ornare ac.\n",
        $runStyle
    );
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Aenean gravida sed elit vel ultricies. Nulla mauris odio, fermentum in accumsan sit amet, commodo non est. Sed ac interdum sapien, sit amet luctus lorem. Aenean ut lorem dapibus, pretium mi sed, tincidunt turpis. Aenean accumsan neque metus, sit amet lobortis metus tristique sed. Fusce at semper enim. Nullam sed velit a mi vestibulum luctus sit amet consequat nisi. Nulla eget nunc sollicitudin nulla finibus consequat.\n",
        $runStyle
    );
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper finibus ultrices. Ut ut suscipit enim. Nunc lobortis diam ornare metus sollicitudin ornare. Etiam ultricies eu nulla sit amet aliquet. Phasellus ultricies lorem enim, auctor malesuada purus pretium in. Proin ultricies leo ac facilisis posuere. Nulla rhoncus laoreet lectus sed vulputate. Fusce aliquam elit ac varius interdum. Vestibulum ut tristique diam, vel lobortis arcu. Quisque et sapien id diam tincidunt dapibus. Nulla consectetur, est eget sodales egestas, orci nisl posuere eros, vel dignissim turpis felis vel mauris.\n",
        $runStyle
    );
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Nunc ac ligula elementum, cursus urna eu, efficitur nulla. Fusce gravida tempor semper. Maecenas nec risus massa. Mauris sem quam, placerat eget felis non, posuere tincidunt est. Praesent purus nibh, elementum a facilisis eget, tristique maximus lorem. Maecenas felis nisi, laoreet non rutrum non, hendrerit in nisl. Donec volutpat tempus lectus, in malesuada felis. Etiam scelerisque dictum lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare elit id est gravida, sit amet ultricies dolor ornare. Quisque tincidunt felis at malesuada varius. Aliquam erat volutpat. Maecenas pellentesque justo eu justo tristique, ac sagittis nulla sollicitudin. Proin elementum magna sed nunc hendrerit, eu porttitor dolor sodales. Morbi massa urna, consequat id velit in, suscipit gravida tortor. In fringilla nisl nec massa eleifend, vitae fringilla dolor viverra.\n",
        $runStyle
    );
$page->addParagraph($paragraphStyle)
    ->addRun(
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec viverra elit a auctor venenatis. Morbi fringilla nisl sit amet ex dictum, eu eleifend urna convallis. Suspendisse ut tellus id ex ornare sagittis a ut risus. Sed sollicitudin ipsum erat, vel venenatis magna iaculis eu. Integer faucibus ipsum massa, sit amet imperdiet nibh sagittis non. Integer vehicula, massa ac pellentesque luctus, sapien justo luctus justo, ac vehicula sapien enim eget lectus. Duis bibendum vitae velit vitae tempor. Quisque feugiat faucibus varius. Morbi elementum rutrum massa vel dignissim. Mauris ex risus, volutpat nec nisi et, porta molestie velit. Ut quis consequat elit.\n",
        $runStyle
    );

$writer = new DocumentWriter($doc);

//echo $writer->transcode($pdfPath);
$writer->save($pdfPath);
$content = $writer->toString('pdf');

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="doc.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

echo $content;

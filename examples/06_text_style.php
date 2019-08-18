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
$paragraphStyle = [
    'textIndent' => '1'
];
$runStyle = [
    'fontFamily' => 'Times-Roman',
    'fontSize' => 12
];
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

$par = $page->addParagraph($paragraphStyle);
$par->addRun(
    "Lorem paragraph ", [
        'fontFamily' => 'Times-Roman',
        'fontSize' => 10
    ]
);
$par->addRun(
    "ipsum dolor sit ",
    $runStyle
);
$par->addRun(
    "amet, consectetur ", [
        'fontFamily' => 'Times-Roman',
        'fontSize' => 13
    ]
);
$par->addRun(
    "adipiscing elit. ", [
        'fontFamily' => 'Times-Roman',
        'fontSize' => 12
    ]
);
$par->addRun(
    "Lorem paragraph ", [
        'fontFamily' => 'Times-Roman',
        'fontSize' => 9
    ]
);
$par->addRun(
    "ipsum dolor sit ", [
        'fontFamily' => 'Times-Roman',
        'fontSize' => 10
    ]
);
$par->addRun(
    "amet, consectetur ", [
        'fontFamily' => 'Times-Roman',
        'fontSize' => 22
    ]
);
$par->addRun(
    "adipiscing elit.\n",
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

// [RFC6266] Direct the UA to display PDF document, with a filename of "example document.pdf" if not supported to display:
header('Content-Disposition: inline; filename="example document.pdf"');
// [RFC7233] Indicates the media type
header('Content-Type: application/pdf');
//header('Cache-Control: private, max-age=0, must-revalidate');
//header('Pragma: public');

echo $content;

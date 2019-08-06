---
layout: page
title: Generate PDF
parent: Guide
nav_order: 3 Generate PDF
---

<h1>{{ page.title }}</h1>

## Prerequisites

The composed document available as an object in variable ```php $document```.

## Generate PDF as the file

ThTis the prefered method to generate PDF documents. It should be used to prevent regeneration of the PDF content with each request. The website should control if it is needed to generate a PDF document or a previously generated PDF document can be used.

```php
// Create render instance
$render = new PdfDocumentWriter();
$render->save('example document.pdf');
```

The full path can be utilised to save the file.

```php
// Create render instance
$render = new PdfDocumentWriter();
$render->save('example document.pdf');
```

## Generate PDF as HTTP response

There are two approaches to HTTP response for PDF content on the website. The first approach is to render the PDF document in-browser PDF view. Usually, this is a default action taken by the browser. Other browser actions were to show the "Open file prompt" for taking action with the unknown file type, saving the file in downloads folder or open the Adobe Reader embedded plugin for displaying the PDF content in a web browser. Another approach that is still supported is to force the web browser to save PDF document as a file in the Downloads folder (Note that we can not ensure if it is downloaded folder, the folder can be chosen by the web browser user).

### Show PDF view embedded in the browser

Embedded PDF viewer is supported by most web browsers on the market. In case the PDF viewer is not available in the web browser, the web browser tries to save the file in local storage.

```php
// Create render instance
$render = new PdfDocumentWriter();
$pdfContent = $render->toString();

// [RFC6266] Direct the UA to display PDF document, with a filename of "example document.pdf" if not supported to display:
header('Content-Disposition: inline; filename="example document.pdf"');
// [RFC7233] Indicates the media type
header('Content-Type: application/pdf');

// Set output for responce with generated PDF content
echo $pdfContent;
```

It is recommended to execute all complicated instructions before defining the content type. It enables you to see HTML content in case of any problem. Any text printed after the content type is treated as PDF content. It means in most cases; the PDF render error is displayed by browser and HTML content is ignored.

Using header instruction need extra caution; it must be called before any actual output is sent. That means no echo or print instruction should be executed earlier in the code. For more information refer to the [PHP documentation](https://www.php.net/manual/en/function.header.php).

### PDF document as the file to download

PDF document as the file to download is supported by all web browsers that support downloading files from the Internet. Notice that in this case, the HTTP request is used only for transmitting the PDF file content. This page is displayed in the web browser.

```php
// Create render instance
$render = new PdfDocumentWriter();
$pdfContent = $render->toString();

// [RFC6266] Direct the UA to show "save as" dialog, with a filename of "example document.pdf":
header('Content-Disposition: attachment; filename="example document.pdf"');
// [RFC7233] Indicates the media type
header('Content-Type: application/pdf');

// Set output for responce with generated PDF content
echo $pdfContent;
```

It is recommended to execute all complicated instructions before defining the content type. It enables you to see HTML content in case of any problem. Any text printed after the content type is treated as PDF content. It means in most cases; the PDF render error is displayed by browser and HTML content is ignored.

Using header instruction need extra caution; it must be called before any actual output is sent. That means no echo or print instruction should be executed earlier in the code. For more information refer to the [PHP documentation](https://www.php.net/manual/en/function.header.php).

---
layout: page
title: Installation
parent: Guide
nav_order: 1
---

<h1>{{ page.title }}</h1>

Page describes installation and basic usage of library.

## Installation with composer

Official installation method is via composer and its packagist package [trogon/otus-pdf](https://packagist.org/packages/trogon/otus-pdf).

```sh
$ composer require trogon/otus-pdf
```

PDF generation requires font informations. The package should initialize font data. In case the package is required in other package, the folowing command should be executed.

```sh
$ composer run-script install-fonts
```

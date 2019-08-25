<?php
/**
 * Otus PDF - PDF document generation library
 * Copyright(C) 2019 Maciej Klemarczyk
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */
namespace trogon\otuspdf\io;

use ReflectionClass;
use trogon\otuspdf\base\InvalidOperationException;

class DocumentWriter extends \trogon\otuspdf\base\DependencyObject
{
    static $providers = [
        'pdf' => '\trogon\otuspdf\io\PdfDocumentWriter',
    ];

    private $document;

    public function __construct(\trogon\otuspdf\Document $document)
    {
        $this->document = $document;
        parent::__construct();
    }

    public function save($filepath, $format = null)
    {
        if (empty($format)) {
            $format = pathinfo($filepath, PATHINFO_EXTENSION);
        }
        if (array_key_exists($format, self::$providers)) {
            $class = self::$providers[$format];
            if (class_exists($class)) {
                $reflection = new ReflectionClass($class);
                $provider = $reflection->newInstance($this->document);
                return $provider->save($filepath);
            } else {
                throw new UnknownFormatException("Provider '$class' not found. Please make sure the provider is installed.");
            }
        } else {
            throw new UnknownFormatException("Format '$format' does not have any providers.");
        }
    }

    public function toString($format)
    {
        if (array_key_exists($format, self::$providers)) {
            $class = self::$providers[$format];
            if (class_exists($class)) {
                $reflection = new ReflectionClass($class);
                $provider = $reflection->newInstance($this->document);
                return $provider->toString();
            } else {
                throw new UnknownFormatException("Provider '$class' not found. Please make sure the provider is installed.");
            }
        } else {
            throw new UnknownFormatException("Format '$format' does not have any providers.");
        }
    }
}

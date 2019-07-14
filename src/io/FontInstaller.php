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

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

use trogon\fontscore14\AfmLoader;
use trogon\otuspdf\meta\FontFamilyInfo;

class FontInstaller extends \trogon\otuspdf\base\BaseObject
{
    public static function postPackageInstall(PackageEvent $event)
    {
        $installedPackage = $event->getOperation()->getPackage();
        if ($installedPackage->getName() == 'trogon/adobe-fonts-core14') {
            self::buildFontMetrics();
        }
    }

    public static function getDataPath()
    {
        return join(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'data', 'fonts']);
    }

    public static function getPdfCoreFonts()
    {
        return [
            'Courier',
            'Courier-Bold',
            'Courier-Oblique',
            'Courier-BoldOblique',
            'Helvetica',
            'Helvetica-Bold',
            'Helvetica-Oblique',
            'Helvetica-BoldOblique',
            'Times-Roman',
            'Times-Bold',
            'Times-Italic',
            'Times-BoldItalic',
            'Symbol',
            'ZapfDingbats'
        ];
    }

    public static function buildFontMetrics()
    {
        $dataPath = self::getDataPath();
        self::createDirIfNotExists($dataPath);
        $fonts = AfmLoader::getFiles();
        foreach ($fonts as $font) {
            $content = AfmLoader::getFileContent($font);
            $data = self::serializeFont($content);
            $name = pathinfo($font, PATHINFO_FILENAME) . '.php';
            $fontInfoFile = fopen(join(DIRECTORY_SEPARATOR, [$dataPath, $name]), "w");
            fwrite($fontInfoFile, $data);
            fclose($fontInfoFile);
        }
        return null;
    }

    private static function createDirIfNotExists($path)
    {
        if (!realpath($path)) {
            self::createDirIfNotExists(dirname($path));
            mkdir($path);
        }
    }

    private static function serializeFont($content)
    {
        $font = new FontFamilyInfo();
        $codeMetrics = false;
        foreach ($content as $line) {
            if (strpos($line, 'FamilyName') === 0) {
                $data = explode(' ', trim($line));
                $font->name = strval($data[1]);
            } elseif (strpos($line, 'Weight') === 0) {
                $data = explode(' ', trim($line));
                $font->weight = strval($data[1]);
            } elseif (strpos($line, 'IsFixedPitch') === 0) {
                $data = explode(' ', trim($line));
                $font->isFixedPitch = ($data[1] == 'true');
            } elseif (strpos($line, 'StartCharMetrics') === 0) {
                $codeMetrics = true;
            } elseif (strpos($line, 'EndCharMetrics') === 0) {
                $codeMetrics = false;
            } elseif ($codeMetrics) {
                $data = explode(';', $line);
                $charData = explode(' ', trim($data[0]));
                $metricsKey = intval($charData[1]);
                $metricsData = explode(' ', trim($data[1]));
                $metricsValue = intval($metricsData[1]);
                if ($metricsKey != -1) {
                    $font->metrics[$metricsKey] = $metricsValue;
                }
            }
        }
        return serialize($font);
    }
}

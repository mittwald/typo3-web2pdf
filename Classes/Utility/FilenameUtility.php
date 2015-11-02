<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Kevin Purrmann <entwicklung@purrmann-websolutions.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

namespace Mittwald\Web2pdf\Utility;


/**
 * Class provides functionality to convert strings into appreciable filename
 *
 * @auto Kevin Purrmann <entwicklung@purrmann-websolutions.de>, Purrmann Websolutions
 * @package Mittwald
 * @subpackage Web2Pdf\Utility
 */
class FilenameUtility
{

    /**
     * @param string $fileName
     * @return string
     * @throws \InvalidArgumentException
     */
    public function convert($fileName)
    {

        if (!is_string($fileName)) {
            throw new \InvalidArgumentException('String needed as argument');
        }

        return preg_replace(array('/\s/', '/\.[\.]+/', '/[^a-zA-Z0-9-_]+/'), array('_', '_', ''), $this->replaceSpecialChars($fileName));
    }


    /**
     * @param $string
     * @return string
     */
    protected function replaceSpecialChars($string)
    {
        $string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');

        $oldLocale = setlocale(LC_CTYPE, '0');

        setlocale(LC_CTYPE, 'en_US.UTF-8');
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

        setlocale(LC_CTYPE, $oldLocale);

        return $string;

    }


}

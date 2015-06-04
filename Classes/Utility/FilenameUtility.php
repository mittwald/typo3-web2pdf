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
class FilenameUtility {


    /**
     * @param string $fileName
     * @return string
     * @throws \InvalidArgumentException
     */
    public function convert($fileName) {

        if (!is_string($fileName)) {
            throw new \InvalidArgumentException('String needed as argument');
        }

        $fileName = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-üäö]/'), array('_', '.', ''), $fileName);


        return $fileName;
    }
}

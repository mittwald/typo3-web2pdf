<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (C) 2015 Mittwald CM Service GmbH & Co. KG <opensource@mittwald.de>
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

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Web2PDF',
    'description' => 'This Extension renders the pagecontent to a PDF file. It supports css and uses the library mPDF. This extension is based on the pdf_generator2.',
    'category' => 'plugin',
    'author' => 'Mittwald CM Service',
    'author_company' => 'Mittwald CM Service',
    'author_email' => 'opensource@mittwald.de',
    'dependencies' => 'extbase,fluid',
    'state' => 'stable',
    'clearCacheOnLoad' => '1',
    'version' => '1.2-dev',
    'constraints' => array(
        'depends' => array(
            'typo3' => '8.7.0-9.5.99',
            'extbase' => '8.7.0-9.5.99',
            'fluid' => '8.7.0-9.5.99',
            'php' => '7.0.0-7.3.99',
        ),
    ),
    'autoload' => array(
        'psr-4' => array(
            'Mittwald\\Web2pdf\\' => 'Classes',
        ),
    ),
);

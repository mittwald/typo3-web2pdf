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

if (! defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// Include the Composer autoloader from "Resources/Private/Libraries". This is
// only necessary when this extension is installed from TER; when using
// Composer, the MPDF dependency is pulled via Composer like any sane person
// would.
$mpdfAutoload = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('web2pdf') . 'Resources/Private/Libraries/vendor/autoload.php';
if (file_exists($mpdfAutoload)) {
    require_once($mpdfAutoload);
}

TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'web2pdf',
    'Pi1',
    [\Mittwald\Web2pdf\Controller\PdfController::class => 'generatePdfLink'],
    [\Mittwald\Web2pdf\Controller\PdfController::class => 'generatePdfLink']
);

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

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

if (!class_exists(\Mpdf\Mpdf::class)) {
    include 'phar://' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('web2pdf') . 'Libraries/mpdf.phar/vendor/autoload.php';
}

TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Mittwald.' . $_EXTKEY,
        'Pi1',
        array(
                'Pdf' => 'generatePdfLink',
        ),
        array(
                'Pdf' => 'generatePdfLink',
        )
);

// Add default real url config
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['web2pdf'] =
            'EXT:web2pdf/Classes/Service/RealurlService.php:Mittwald\\Web2pdf\\Service\\RealurlService->addAutoConfig';
}

// Add hook for PDF generation
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = 'Mittwald\Web2pdf\Service\PdfRenderService->onFrontendOutput';

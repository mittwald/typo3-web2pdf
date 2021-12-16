<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

// Include the Composer autoloader from "Resources/Private/Libraries". This is
// only necessary when this extension is installed from TER; when using
// Composer, the MPDF dependency is pulled via Composer like any sane person
// would.
$mpdfAutoload = ExtensionManagementUtility::extPath('web2pdf') . 'Resources/Private/Libraries/vendor/autoload.php';
if (file_exists($mpdfAutoload)) {
    require_once($mpdfAutoload);
}

ExtensionUtility::configurePlugin(
    'web2pdf',
    'Pi1',
    [\Mittwald\Web2pdf\Controller\PdfController::class => 'generatePdfLink'],
    [\Mittwald\Web2pdf\Controller\PdfController::class => 'generatePdfLink']
);

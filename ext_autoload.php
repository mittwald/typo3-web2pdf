<?php

$dir = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('web2pdf') . 'Vendor/mpdf/mpdf';


$default = array(
        'mPDF' => $dir . '/mpdf.php'
);

return $default;
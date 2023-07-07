<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile(
    'web2pdf',
    'Configuration/TypoScript',
    'Web2PDF Generator'
);

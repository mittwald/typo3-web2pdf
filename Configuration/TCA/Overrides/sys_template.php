<?php

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'web2pdf',
        'Configuration/TypoScript',
        'Web2PDF Generator'
    );
})();

<?php

return [
    'frontend' => [
        'mittwald/web2pdf/pdf-handler' => [
            'target' => \Mittwald\Web2pdf\Middleware\PdfHandler::class,
            'after' => [
                'typo3/cms-frontend/tsfe',                   // TYPO3 v13
                'typo3/cms-frontend/prepare-tsfe-rendering', // TYPO3 v14
            ],
        ],
    ],
];

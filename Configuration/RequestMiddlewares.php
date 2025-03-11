<?php

return [
    'frontend' => [
        'mittwald/web2pdf/pdf-handler' => [
            'target' => \Mittwald\Web2pdf\Middleware\PdfHandler::class,
            'after' => [
                'typo3/cms-frontend/tsfe',
            ],
        ],
    ],
];

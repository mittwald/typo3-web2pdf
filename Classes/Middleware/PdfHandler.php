<?php

/****************************************************************
 *  Copyright notice
 *
 *  (C) Mittwald CM Service GmbH & Co. KG <opensource@mittwald.de>
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
 ***************************************************************/

namespace Mittwald\Web2pdf\Middleware;

use Mittwald\Web2pdf\Options\ModuleOptions;
use Mittwald\Web2pdf\View\PdfView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PdfHandler implements MiddlewareInterface
{
    public function __construct(private readonly PdfView $pdfView) {}

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $pluginParams = $request->getQueryParams()['tx_web2pdf_pi1'] ?? null;

        if ($pluginParams === null) {
            return $handler->handle($request);
        }

        if (($pluginParams['argument'] !== ModuleOptions::QUERY_PARAMETER)) {
            return $handler->handle($request);
        }

        $output = $handler->handle($request);

        ob_clean();

        $moduleOptions = GeneralUtility::makeInstance(ModuleOptions::class);
        $frontendController = $request->getAttribute('frontend.controller');
        $response = new Response();
        $file = $this->pdfView->renderHtmlOutput(
            $request,
            $output->getBody(),
            $frontendController->generatePageTitle($request)
        );

        $destination = $moduleOptions->getPdfDestination() ?? 'attachment';

        $response = $response->withHeader('Content-Transfer-Encoding', 'binary');
        $response->getBody()->write(file_get_contents($file));
        $response = $response->withHeader('Content-Type', 'application/pdf');
        return $response->withHeader('Content-Disposition', $destination . '; filename="' . basename($file) . '"');
    }
}

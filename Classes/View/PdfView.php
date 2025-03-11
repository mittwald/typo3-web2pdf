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

namespace Mittwald\Web2pdf\View;

use Mittwald\Web2pdf\Options\ModuleOptions;
use Mittwald\Web2pdf\Utility\FilenameUtility;
use Mittwald\Web2pdf\Utility\PdfLinkUtility;
use Mpdf\Mpdf;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;

class PdfView
{
    public const PREG_REPLACEMENT_KEY = 'pregReplacements';
    public const STR_REPLACEMENT_KEY = 'strReplacements';

    public function __construct(
        protected readonly FilenameUtility $fileNameUtility,
        protected readonly PdfLinkUtility $pdfLinkUtility,
        protected readonly ViewFactoryInterface $viewFactory
    ) {}

    /**
     * Renders the PDF view
     */
    public function renderHtmlOutput(ServerRequestInterface $request, string $content, string $pageTitle): string
    {
        $fileName = $this->fileNameUtility->convert($pageTitle) . '.pdf';
        $filePath = Environment::getVarPath() . '/web2pdf/' . $fileName;

        $options = GeneralUtility::makeInstance(ModuleOptions::class);
        $content = $this->replaceStrings($options, $content);
        $pdf = $this->getPdfObject($options);

        // Add Header if configured
        if ($options->getUseCustomHeader()) {
            $pdf->SetHTMLHeader($this->getPartial($request, $options, 'Header', ['title' => $pageTitle]));
        }
        // Add Footer if configured
        if ($options->getUseCustomFooter()) {
            $pdf->SetHTMLFooter($this->getPartial($request, $options, 'Footer', ['title' => $pageTitle]));
        }

        $pdf->WriteHTML($content);
        $pdf->Output($filePath, 'F');

        return $filePath;
    }

    /**
     * Replacements of configured strings
     */
    private function replaceStrings(ModuleOptions $options, string $content): string
    {
        if (is_array($options->getStrReplacements())) {
            foreach ($options->getStrReplacements() as $searchString => $replacement) {
                $content = str_replace($searchString, $replacement, $content);
            }
        }

        if (is_array($options->getPregReplacements())) {
            foreach ($options->getPregReplacements() as $pattern => $patternReplacement) {
                $content = preg_replace($pattern, $patternReplacement, $content);
            }
        }

        return $this->pdfLinkUtility->replace($content);
    }

    /**
     * Returns configured mPDF object
     */
    protected function getPdfObject(ModuleOptions $options): Mpdf
    {
        // Get options from TypoScript
        $pageFormat = $options->getPdfPageFormat() ?? 'A4';
        $pageOrientation = $options->getPdfPageOrientation() ?? 'L';
        $leftMargin = $options->getPdfLeftMargin() ?? '15';
        $rightMargin = $options->getPdfRightMargin() ?? '15';
        $bottomMargin = $options->getPdfBottomMargin() ?? '15';
        $topMargin = $options->getPdfTopMargin() ?? '15';
        $styleSheet =  $options->getPdfStyleSheet() ?? 'print';

        $pdf = new Mpdf([
            'format' => $pageFormat,
            'default_font_size' => 12,
            'margin_left' => $leftMargin,
            'margin_right' => $rightMargin,
            'margin_top' => $topMargin,
            'margin_bottom' => $bottomMargin,
            'orientation' => $pageOrientation,
            'tempDir' => Environment::getVarPath() . '/web2pdf',
            'fontDir' => ExtensionManagementUtility::extPath('web2pdf') . 'Resources/Public/Fonts',
        ]);

        $pdf->SetMargins($leftMargin, $rightMargin, $topMargin);

        if ($styleSheet === 'print' || $styleSheet === 'screen') {
            $pdf->CSSselectMedia = $styleSheet;
        }

        return $pdf;
    }

    /**
     * Renders the given templateName. Note, that the template must actually reside in Partials/ folder.
     */
    protected function getPartial(
        ServerRequestInterface $request,
        ModuleOptions $options,
        string $template,
        array $arguments = []
    ): string {
        $partialPaths = $options->getPartialRootPaths();
        $template = GeneralUtility::getFileAbsFileName(end($partialPaths)) . 'Pdf/' . ucfirst($template) . '.html';

        $viewFactoryData = new ViewFactoryData(
            partialRootPaths: $partialPaths,
            layoutRootPaths: $options->getLayoutRootPaths(),
            templatePathAndFilename: $template,
            request: $request,
        );
        $view = $this->viewFactory->create($viewFactoryData);
        $view->assign('data', $arguments);

        return $view->render();
    }
}

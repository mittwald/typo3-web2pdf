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
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class PdfView
{
    const PREG_REPLACEMENT_KEY = 'pregReplacements';
    const STR_REPLACEMENT_KEY = 'strReplacements';

    protected ModuleOptions $options;
    protected FilenameUtility $fileNameUtility;
    protected PdfLinkUtility $pdfLinkUtility;

    public function __construct(
        ModuleOptions $options,
        FilenameUtility $fileNameUtility,
        PdfLinkUtility $pdfLinkUtility
    ) {
        $this->options = $options;
        $this->fileNameUtility = $fileNameUtility;
        $this->pdfLinkUtility = $pdfLinkUtility;
    }

    /**
     * Renders the PDF view
     *
     * @param string $content The HTML Code to convert
     * @param string $pageTitle
     * @return string $filePath
     * @throws \Mpdf\MpdfException
     */
    public function renderHtmlOutput(string $content, string $pageTitle): string
    {
        $fileName = $this->fileNameUtility->convert($pageTitle) . '.pdf';
        $filePath = Environment::getVarPath() . '/web2pdf/' . $fileName;

        $content = $this->replaceStrings($content);
        $pdf = $this->getPdfObject();

        // Add Header if configured
        if ($this->options->getUseCustomHeader()) {
            $pdf->SetHTMLHeader($this->getPartial('Header', ['title' => $pageTitle]));
        }
        // Add Footer if configured
        if ($this->options->getUseCustomFooter()) {
            $pdf->SetHTMLFooter($this->getPartial('Footer', ['title' => $pageTitle]));
        }

        $pdf->WriteHTML($content);
        $pdf->Output($filePath, 'F');

        return $filePath;
    }

    /**
     * Replacements of configured strings
     *
     * @param string $content
     * @return string
     */
    private function replaceStrings(string $content): string
    {
        if (is_array($this->options->getStrReplacements())) {
            foreach ($this->options->getStrReplacements() as $searchString => $replacement) {
                $content = str_replace($searchString, $replacement, $content);
            }
        }

        if (is_array($this->options->getPregReplacements())) {
            foreach ($this->options->getPregReplacements() as $pattern => $patternReplacement) {
                $content = preg_replace($pattern, $patternReplacement, $content);
            }
        }

        return $this->pdfLinkUtility->replace($content);
    }

    /**
     * Returns configured mPDF object
     *
     * @return Mpdf
     */
    protected function getPdfObject(): Mpdf
    {
        // Get options from TypoScript
        $pageFormat = $this->options->getPdfPageFormat() ?? 'A4';
        $pageOrientation = $this->options->getPdfPageOrientation() ?? 'L';
        $leftMargin = $this->options->getPdfLeftMargin() ?? '15';
        $rightMargin = $this->options->getPdfRightMargin() ?? '15';
        $bottomMargin = $this->options->getPdfBottomMargin() ?? '15';
        $topMargin = $this->options->getPdfTopMargin() ?? '15';
        $styleSheet =  $this->options->getPdfStyleSheet() ?? 'print';

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
     *
     * @param string $templateName
     * @param array $arguments
     * @return string
     */
    protected function getPartial(string $templateName, array $arguments = []): string
    {
        $partial = GeneralUtility::makeInstance(StandaloneView::class);
        $partial->setLayoutRootPaths($this->options->getLayoutRootPaths());
        $partial->setPartialRootPaths($this->options->getPartialRootPaths());
        $partialsPaths = $partial->getPartialRootPaths();
        $partial->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName(end($partialsPaths)) . 'Pdf/' . ucfirst($templateName) . '.html'
        );
        $partial->assign('data', $arguments);

        return $partial->render();
    }
}

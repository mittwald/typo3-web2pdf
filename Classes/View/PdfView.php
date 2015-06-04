<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Kevin Purrmann <entwicklung@purrmann-websolutions.de>
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

namespace Mittwald\Web2pdf\View;

use TYPO3\CMS\Fluid\View\TemplateView;


/**
 * PDFView
 *
 * @author Kevin Purrmann <entwicklung@purrmann-websolutions.de>, Purrmann Websolutions
 * @package Mittwald
 * @subpackage Web2Pdf\View
 */
class PdfView extends TemplateView {


    /**
     * @var \Mittwald\Web2pdf\Options\ModuleOptions
     * @inject
     */
    protected $options;

    /**
     * @var \Mittwald\Web2pdf\Utility\FilenameUtility
     * @inject
     */
    protected $fileNameUtility;

    /**
     * Renders the view
     *
     * @param string $content The HTML Code to convert
     * @param string $pageTitle
     * @return \TYPO3\CMS\Extbase\Mvc\Web\Response The rendered view
     */
    public function renderHtmlOutput($content, $pageTitle) {
        $pdf = $this->getPdfObject();
        $pdf->WriteHTML($content);

        return $pdf->Output($this->fileNameUtility->convert($pageTitle) . '.pdf', 'D'); // Parameter D=>Display; F=>SaveAsFile

    }

    /**
     *
     * @return \mPDF
     */
    protected function getPdfObject() {

        // Get options from TypoScript
        $pageFormat = ($this->options->getPdfPageFormat()) ? $this->options->getPdfPageFormat() : 'A4';
        $pageOrientation = ($orientation = $this->options->getPdfPageOrientation()) ? $orientation : 'L';
        $font = ($this->options->getPdfFont()) ? $this->options->getPdfFont() : 'helvetica';
        $fontSize = ($this->options->getPdfFontSize()) ? $this->options->getPdfFontSize() : '11';
        $leftMargin = ($this->options->getPdfLeftMargin()) ? $this->options->getPdfLeftMargin() : '15';
        $rightMargin = ($this->options->getPdfRightMargin()) ? $this->options->getPdfRightMargin() : '15';
        $topMargin = ($this->options->getPdfTopMargin()) ? $this->options->getPdfTopMargin() : '15';
        $styleSheet = ($this->options->getPdfStyleSheet()) ? $this->options->getPdfStyleSheet() : 'print';

        /* @var $pdf \mPDF */
        $pdf = $this->objectManager->get('mPDF', '', $pageFormat . '-' . $pageOrientation);
        $pdf->SetMargins($leftMargin, $rightMargin, $topMargin);
        $pdf->SetFont($font);
        $pdf->CSSselectMedia = $styleSheet;
        $pdf->SetFontSize($fontSize);
        $pdf->AddPage();
        return $pdf;
    }
}
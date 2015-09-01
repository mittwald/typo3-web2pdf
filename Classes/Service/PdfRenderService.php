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

namespace Mittwald\Web2pdf\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Service provides generation of full html page as PDF
 * Uses TYPO3 core hook `contentPostProc-output`
 *
 * @author Kevin Purrmann <entwicklung@purrmann-websolutions.de>, Purrmann Websolutions
 * @package Mittwald
 * @subpackage Web2Pdf\Service
 */
class PdfRenderService {

    /**
     * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected $frontendController;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Mittwald\Web2pdf\View\PdfView
     */
    protected $view;

    /**
     * @var \Mittwald\Web2pdf\Options\ModuleOptions
     */
    protected $options;

    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Request
     */
    private $request;

    /**
     * Instantiates necessary objects, because hook does not handle dependency injections
     *
     */
    public function __construct() {
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->options = $this->objectManager->get('Mittwald\\Web2pdf\\Options\\ModuleOptions');
        $this->view = $this->objectManager->get('Mittwald\\Web2pdf\View\\PdfView');
    }

    /**
     * Called by hook
     * renders PDF
     *
     * @hook contentPostProc-output
     * @see ext_localconf.php
     * @param array $data
     * @return string
     */
    public function onFrontendOutput(array $data) {
        if ($this->renderAsPdf()) {
            $this->frontendController = $data['pObj'];

            return $this->view->renderHtmlOutput(
                    $this->frontendController->content,
                    $this->getPageTitle()
            );
        }
    }

    /**
     * @return string
     */
    protected function getPageTitle() {
        $title = $this->frontendController->altPageTitle ?
                $this->frontendController->altPageTitle :
                $this->frontendController->indexedDocTitle;

        /**
         * Use backend page title if no other page title is found
         * @see https://github.com/mittwald/typo3-web2pdf/issues/20
         */
        if (is_null($title)) {
            $title = $this->frontendController->page['title'];
        }
        return $title;
    }

    /**
     * Returns true if generation as PDF is forced
     *
     * @todo argument should be handles by ModuleOptions but not yet possible
     * @todo because fluid does not accept arguments:{settings.pdfQueryParameter:1} in link view helper
     * @return bool
     */
    protected function renderAsPdf() {
        return ($this->getRequest()->hasArgument('argument') &&
                ($query = $this->getRequest()->getArgument('argument')) &&
                ($query == \Mittwald\Web2pdf\Options\ModuleOptions::QUERY_PARAMETER)
        );
    }

    /**
     * Get current request and set request arguments of extension
     *
     * @todo should handle request automatically
     * @return \TYPO3\CMS\Extbase\Mvc\Web\Request
     */
    protected function getRequest() {
        if (is_null($this->request)) {
            $this->request = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Request');
            if (($arguments = GeneralUtility::_GP('tx_web2pdf_pi1'))) {
                $this->request->setArguments($arguments);
            }
        }
        return $this->request;
    }
}

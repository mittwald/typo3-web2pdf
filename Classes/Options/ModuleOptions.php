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

namespace Mittwald\Web2pdf\Options;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;


/**
 * Class provides access to all typoscript settings
 * which are set in Configuration/TypoScript/setup.txt
 *
 */
class ModuleOptions implements \TYPO3\CMS\Core\SingletonInterface {

    const QUERY_PARAMETER = 'printPage';

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     * @inject
     */
    protected $configurationManager;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * Fills typoscript settings into options
     *
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function initializeObject() {
        if ($this->options = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'web2pdf', 'settings')) {

            if (is_array($this->options['pdfPregSearch']) && is_array($this->options['pdfPregReplace'])) {
                $this->mergeReplaceConfiguration($this->options['pdfPregSearch'], $this->options['pdfPregReplace'], \Mittwald\Web2pdf\View\PdfView::PREG_REPLACEMENT_KEY);
                unset($this->options['pdfPregSearch'], $this->options['pdfPregReplace']);
            }

            if (is_array($this->options['pdfStrSearch']) && is_array($this->options['pdfStrReplace'])) {
                $this->mergeReplaceConfiguration($this->options['pdfStrSearch'], $this->options['pdfStrReplace'], \Mittwald\Web2pdf\View\PdfView::STR_REPLACEMENT_KEY);
                unset($this->options['pdfStrSearch'], $this->options['pdfStrReplace']);
            }
        }

    }

    /**
     * Merge replacement config
     *
     * @param $searchArray
     * @param $replaceArray
     * @param $newKey
     */
    private function mergeReplaceConfiguration($searchArray, $replaceArray, $newKey) {

        foreach ($searchArray as $key => $searchString) {
            if (isset($replaceArray[$key]) && $searchString !== '' && !empty($replaceArray[$key])) {
                $this->options[$newKey][$searchString] = $replaceArray[$key];
            }
        }

    }

    /**
     * Split up $methodName after "get"
     * Then tries to return config value in $this->options
     *
     * @param string $methodName Full method name
     * @param array $arguments
     * @return mixed|null
     */
    public function __call($methodName, $arguments) {
        if (is_array($this->options) && substr($methodName, 0, 3) === 'get' && strlen($methodName) > 5) {
            $propertyName = lcfirst(substr($methodName, 3));
            return $this->getConfigValue($propertyName);
        }
        return NULL;
    }

    /**
     * Returns config value if exists
     *
     * @param $index
     * @return mixed
     */
    protected function getConfigValue($index) {
        if (is_array($this->options) && array_key_exists($index, $this->options)) {
            return $this->options[$index];
        }
        return NULL;
    }
}
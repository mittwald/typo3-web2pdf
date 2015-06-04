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
 * Description of class ModuleOptions
 *
 */
class ModuleOptions implements \TYPO3\CMS\Core\SingletonInterface {

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
        $this->options = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'web2pdf', 'settings');
    }

    /**
     * @param $methodName
     * @param $arguments
     * @return mixed|null
     * @throws \InvalidArgumentException
     */
    public function __call($methodName, $arguments) {
        if (substr($methodName, 0, 3) === 'get' && strlen($methodName) > 5) {
            $propertyName = lcfirst(substr($methodName, 3));
            return $this->getConfigValue($propertyName);
        }
        return NULL;
    }

    /**
     * @param $index
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function getConfigValue($index) {
        if (array_key_exists($index, $this->options)) {
            return $this->options[$index];
        }

        throw new \InvalidArgumentException();
    }
}
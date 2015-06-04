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
use Mittwald\Web2pdf\Options\ModuleOptions;


/**
 * Description of class RealurlService
 *
 * @author Kevin Purrmann <entwicklung@purrmann-websolutions.de>
 * @package Mittwald
 * @subpackage Web2pdf\Service
 */
class RealurlService {


    /**
     * Generates additional RealURL configuration and merges it with provided configuration
     *
     * @param array $params Default configuration
     * @param tx_realurl_autoconfgen $pObj Parent object
     * @return array Updated configuration
     */
    public function addAutoConfig($params, &$pObj) {

        $params['config']['init']['emptySegmentValue'] = '';


        return array_merge_recursive($params['config'], array(
                'postVarSets' => array(
                        '_DEFAULT' => array(
                                'web2pdf' => array(
                                        array(
                                                'GETvar' => 'tx_web2pdf_pi1[controller]',
                                                'noMatch' => 'bypass',
                                        ),
                                        array(
                                                'GETvar' => 'tx_web2pdf_pi1[action]',
                                                'noMatch' => 'bypass',
                                        ),
                                        array(
                                                'GETvar' => 'tx_web2pdf_pi1[argument]',
                                                'valueMap' => array(
                                                        'pdf' => ModuleOptions::QUERY_PARAMETER
                                                ),
                                                'noMatch' => 'bypass',
                                        ),
                                ),
                        ),
                ),
        ));
    }
}
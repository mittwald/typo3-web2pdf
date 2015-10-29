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

namespace Mittwald\Web2pdf\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Class PdfLinkUtility
 * @package Mittwald\Web2pdf\Utility
 */
class PdfLinkUtility {

    /**
     * Method removes local absolute url if link section is given
     * Method keeps external links
     * Method keeps external links with sections
     *
     * @param $content
     * @return string
     */
    public function replace($content) {

        $tmpSiteUri = $this->getSiteUri();
        $currentSiteUri = (preg_match('/^\//', $tmpSiteUri)) ? $tmpSiteUri : '/' . $tmpSiteUri;
        $currentHost = $this->getHost();
        $dom = new \DOMDocument();
        $dom->loadHTML($content);

        foreach ($dom->getElementsByTagName('a') as $node) {
            /* @var $node \DOMNode */
            if ($node->hasAttribute('href')) {
                $href = $node->getAttribute('href');
                if ((($curPos = strpos($href, '#')) > 0) && (strpos($href, $currentSiteUri) !== false
                        || strpos(htmlentities($href), $currentSiteUri) !== false
                        || strpos($href, $currentHost . $currentSiteUri) !== false
                        || strpos(htmlentities($href), $currentHost . $currentSiteUri) !== false)

                ) {
                    $node->setAttribute('href', substr($href, $curPos, strlen($href)));
                }
            }


        }

        return $dom->saveHTML();

    }


    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function getSiteUri() {
        return htmlentities(GeneralUtility::getIndpEnv("TYPO3_SITE_PATH"));
    }

    /**
     * @return string
     */
    protected function getHost() {

        return ($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    }


}

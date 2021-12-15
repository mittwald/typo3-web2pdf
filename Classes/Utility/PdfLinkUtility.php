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

namespace Mittwald\Web2pdf\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class PdfLinkUtility
{
    /**
     * Method removes local absolute url if link section is given
     * Method keeps external links
     * Method keeps external links with sections
     *
     * @param string $content
     * @return string
     */
    public function replace(string $content): string
    {
        $tmpSiteUri = $this->getSiteUri();
        $currentSiteUri = (preg_match('/^\//', $tmpSiteUri)) ? $tmpSiteUri : '/' . $tmpSiteUri;
        $currentHost = $this->getHost();
        $regex = '/<a(.*?)href="([^#"]*?)#([a-zA-Z0-9]+)"/';
        $replacedContent = preg_replace_callback($regex, function ($hit) use ($currentSiteUri, $currentHost) {
            if (strpos($hit[0], $currentSiteUri) !== false
                || strpos(htmlentities($hit[0]), $currentSiteUri) !== false
                || strpos($hit[0], $currentHost . $currentSiteUri) !== false
                || strpos(htmlentities($hit[0]), $currentHost . $currentSiteUri) !== false
            ) {
                return '<a' . $hit[1] . 'href="#' . $hit[3] . '"';
            }
            return $hit[0];
        }, $content);

        return $replacedContent;
    }

    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function getSiteUri(): string
    {
        return htmlentities(GeneralUtility::getIndpEnv('TYPO3_SITE_SCRIPT'));
    }

    /**
     * @return string
     */
    protected function getHost(): string
    {
        return $_SERVER['HTTP_HOST'] ?? '';
    }
}

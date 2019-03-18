<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (C) 2015 Mittwald CM Service GmbH & Co. KG <opensource@mittwald.de>
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

namespace Mittwald\Tests\Utility;

use Mittwald\Web2pdf\Utility\PdfLinkUtility;
use Nimut\TestingFramework\TestCase\UnitTestCase;


/**
 * Class PdfLinkUtilityTest
 * @package Mittwald\Tests\Utility
 */
class PdfLinkUtilityTest extends UnitTestCase {

    /**
     * @var PdfLinkUtility|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture;


    /**
     *
     */
    public function testKeepLinksIfNoSectionGiven() {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn($this->getSiteUri());
        $return = $this->fixture->replace($this->getLink($this->getSiteUri()));
        $this->assertEquals($this->getLink($this->getSiteUri()), $return);
    }

    /**
     *
     */
    public function testLocalAnchorsAreResolvedIfSiteUriContainsSpecialChars() {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn($this->getSiteUriWithSectionAndSpecialChars());
        $return = $this->fixture->replace($this->getLink($this->getSiteUriWithSectionAndSpecialChars()));
        $this->assertEquals('<a href="#section1">Link Test</a>', $return);
    }

    /**
     *
     */
    public function testLocalAnchorsAreResolvedIfSiteUriContainsHtmlEntities() {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn(htmlentities($this->getSiteUriWithSectionAndSpecialChars()));
        $return = $this->fixture->replace($this->getLink($this->getSiteUriWithSectionAndSpecialChars()));
        $this->assertEquals('<a href="#section1">Link Test</a>', $return);
    }

    /**
     *
     */
    public function testLocalAnchorsAreResolved() {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn($this->getSiteUriWithSection());
        $return = $this->fixture->replace($this->getLink($this->getSiteUriWithSection()));
        $this->assertEquals('<a href="#section1">Link Test</a>', $return);
    }

    /**
     *
     */
    public function testLocalAnchorsOfExternalPageKeptInContent() {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn($this->getSiteUriWithSection());
        $return = $this->fixture->replace($this->getLink('/external-page#section1', $this->getExternalHost()));
        $this->assertEquals('<a href="http://www.external.de/external-page#section1">Link Test</a>', $return);
    }

    /**
     * @param string $siteUri
     * @return string
     */
    protected function getLink($siteUri, $host = null) {
        if (is_null($host)) {
            $host = $this->getDefaultHost();
        }
        return '<a href="' . $host . $siteUri . '">Link Test</a>';
    }

    /**
     * @return string
     */
    protected function getSiteUri() {
        return '/index.php?id=124';
    }

    /**
     * @return string
     */
    protected function getSiteUriWithSection() {
        return '/index.php?id=123#section1';
    }

    /**
     * @return string
     */
    protected function getSiteUriWithSectionAndSpecialChars() {
        return '/index.php?id=123&my_ext_pi1[test]=1#section1';
    }

    /**
     * @return string
     */
    protected function getDefaultHost() {
        return 'http://www.google.de';
    }

    /**
     * @return string
     */
    protected function getExternalHost() {
        return 'http://www.external.de';
    }


    /**
     * Set up fixture
     */
    protected function setUp() {
        $this->fixture = $this->getAccessibleMock(
                'Mittwald\Web2pdf\Utility\PdfLinkUtility',
                array('getSiteUri', 'getHost'),
                array(),
                '',
                false
        );
        $this->fixture->expects($this->once())->method('getHost')->willReturn($this->getDefaultHost());
    }

}

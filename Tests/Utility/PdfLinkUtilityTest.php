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

namespace Mittwald\Tests\Utility;

use Mittwald\Web2pdf\Utility\PdfLinkUtility;
use TYPO3\CMS\Core\Tests\UnitTestCase;


/**
 * Class PdfLinkUtilityTest
 * @package Mittwald\Tests\Utility
 */
class PdfLinkUtilityTest extends UnitTestCase
{

    /**
     * @var PdfLinkUtility|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture;

    /**
     * @dataProvider getHtmlPageProvider
     */
    public function testKeepLinksIfNoSectionGiven($html)
    {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn('/nosectiongiven/');
        $return = $this->fixture->replace($html);
        $this->assertTrue((bool)preg_match('/\/nosectiongiven\//', $return));
    }

    /**
     * @dataProvider getHtmlPageProvider
     */
    public function testLocalAnchorsAreResolvedIfSiteUriContainsHtmlEntities($html)
    {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn('/sectionwithparams/');
        $return = $this->fixture->replace($html);
        $this->assertTrue((bool)preg_match('/"#section1"/', $return), $return);
    }

    /**
     * @dataProvider getHtmlPageProvider
     */
    public function testLocalAnchorsAreResolved($html)
    {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn('/sectiongiven/');
        $return = $this->fixture->replace($html);
        $this->assertTrue((bool)preg_match('/"#section1"/', $return), $return);
    }

    /**
     * @dataProvider getHtmlPageProvider
     */
    public function testLocalAnchorsOfExternalPageKeptInContent($html)
    {
        $this->fixture->expects($this->once())->method('getSiteUri')->willReturn($this->getSiteUriWithSection());
        $return = $this->fixture->replace($html);
        $this->assertTrue((bool)preg_match('/"http\:\/\/www\.external\.de\/external\-page#section1"/', $return), $return);
    }

    /**
     * @param string $siteUri
     * @return string
     */
    protected function getLink($siteUri, $host = null)
    {
        if (is_null($host)) {
            $host = $this->getDefaultHost();
        }
        return '<a href="' . $host . $siteUri . '">Link Test</a>';
    }

    /**
     * @return string
     */
    protected function getSiteUri()
    {
        return '/index.php?id=124';
    }

    /**
     * @return string
     */
    protected function getSiteUriWithSection()
    {
        return '/index.php?id=123#section1';
    }

    /**
     * @return string
     */
    protected function getDefaultHost()
    {
        return 'http://www.google.de';
    }

    /**
     * @return string
     */
    protected function getExternalHost()
    {
        return 'http://www.external.de';
    }

    /**
     * @return array
     */
    public function getHtmlPageProvider()
    {
        return array(
            array(file_get_contents(__DIR__ . '/../Fixtures/HtmlPage.html'))
        );
    }


    /**
     * Set up fixture
     */
    protected function setUp()
    {
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

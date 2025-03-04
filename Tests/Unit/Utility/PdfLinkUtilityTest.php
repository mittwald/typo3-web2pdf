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
 *  https://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Mittwald\Tests\Utility;

use Mittwald\Web2pdf\Utility\PdfLinkUtility;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class PdfLinkUtilityTest
 */
class PdfLinkUtilityTest extends UnitTestCase
{
    protected AccessibleObjectInterface|MockObject|PdfLinkUtility $subject;

    /**
     * Set up fixture
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            PdfLinkUtility::class,
            ['getSiteUri', 'getHost'],
            [],
            '',
            false
        );
        $this->subject->expects(self::once())->method('getHost')->willReturn($this->getDefaultHost());
    }

    #[Test]
    public function keepLinksIfNoSectionGiven(): void
    {
        $this->subject->expects(self::once())->method('getSiteUri')->willReturn($this->getSiteUri());
        $return = $this->subject->replace($this->getLink($this->getSiteUri()));
        self::assertEquals($this->getLink($this->getSiteUri()), $return);
    }

    public function testLocalAnchorsAreResolvedIfSiteUriContainsSpecialChars(): void
    {
        $this->subject->expects(self::once())->method('getSiteUri')->willReturn($this->getSiteUriWithSectionAndSpecialChars());
        $return = $this->subject->replace($this->getLink($this->getSiteUriWithSectionAndSpecialChars()));
        self::assertEquals('<a href="#section1">Link Test</a>', $return);
    }

    public function testLocalAnchorsAreResolvedIfSiteUriContainsHtmlEntities(): void
    {
        $this->subject->expects(self::once())->method('getSiteUri')->willReturn(htmlentities($this->getSiteUriWithSectionAndSpecialChars()));
        $return = $this->subject->replace($this->getLink($this->getSiteUriWithSectionAndSpecialChars()));
        self::assertEquals('<a href="#section1">Link Test</a>', $return);
    }

    public function testLocalAnchorsAreResolved(): void
    {
        $this->subject->expects(self::once())->method('getSiteUri')->willReturn($this->getSiteUriWithSection());
        $return = $this->subject->replace($this->getLink($this->getSiteUriWithSection()));
        self::assertEquals('<a href="#section1">Link Test</a>', $return);
    }

    public function testLocalAnchorsOfExternalPageKeptInContent(): void
    {
        $this->subject->expects(self::once())->method('getSiteUri')->willReturn($this->getSiteUriWithSection());
        $return = $this->subject->replace($this->getLink('/external-page#section1', $this->getExternalHost()));
        self::assertEquals('<a href="https://www.external.de/external-page#section1">Link Test</a>', $return);
    }

    protected function getLink(string $siteUri, string $host = null): string
    {
        if (is_null($host)) {
            $host = $this->getDefaultHost();
        }
        return '<a href="' . $host . $siteUri . '">Link Test</a>';
    }

    protected function getSiteUri(): string
    {
        return '/index.php?id=124';
    }

    protected function getSiteUriWithSection(): string
    {
        return '/index.php?id=123#section1';
    }

    protected function getSiteUriWithSectionAndSpecialChars(): string
    {
        return '/index.php?id=123&my_ext_pi1[test]=1#section1';
    }

    protected function getDefaultHost(): string
    {
        return 'https://typo3.org';
    }

    protected function getExternalHost(): string
    {
        return 'https://www.external.de';
    }
}

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

use Mittwald\Web2pdf\Utility\FilenameUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class PdfLinkUtilityTest
 */
class FilenameUtilityTest extends UnitTestCase
{
    /**
     * @var FilenameUtility
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new FilenameUtility();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @dataProvider getTitleData
     */
    public function testConvertMethod($string, $expected)
    {
        self::assertEquals($expected, $this->subject->convert($string));
    }

    /**
     * @return array
     */
    public function getTitleData()
    {
        return [
            ['my tést string', 'my_test_string'],
            ['mY TeSt', 'mY_TeSt'],
            ['my test 123', 'my_test_123'],
            ['my tést 123', 'my_test_123'], // in case it should be é to e // works but could not find a way to test it
        ];
    }
}

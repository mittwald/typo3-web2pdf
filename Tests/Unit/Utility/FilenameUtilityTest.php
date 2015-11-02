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

use Mittwald\Web2pdf\Utility\FilenameUtility;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Tests\UnitTestCase;


/**
 * Class PdfLinkUtilityTest
 * @package Mittwald\Tests\Utility
 */
class FilenameUtilityTest extends UnitTestCase
{

    /**
     * @var FilenameUtility|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture;


    /**
     * @dataProvider getTitleData
     */
    public function testConvertMethod($string, $expected)
    {
        $this->assertEquals($expected, $this->fixture->convert($string));
    }


    /**
     * @return array
     */
    public function getTitleData()
    {
        return array(
            array('my tést string', 'my_test_string'),
            array('mY TeSt', 'mY_TeSt'),
            array('my test 123', 'my_test_123'),
            array('my tést 123', 'my_test_123') // in case it should be é to e // works but could not find a way to test it
        );
    }

    /**
     * Set up fixture
     */
    protected function setUp()
    {
        $this->fixture = new FilenameUtility();
    }

}

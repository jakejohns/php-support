<?php
/**
* Support
*
* PHP version 5
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* @category  Support
* @package   Tests
* @author    Jake Johns <jake@jakejohns.net>
* @copyright 2014 Jake Johns
* @license   http://www.gnu.org/licenses/gpl-3.0.txt GPL V3
* @link      http://jakejohns.net
 */

namespace Jnj\Support\Traits;

/**
 * HelpersTraitTest
 *
 * @category Support
 * @package  Tests
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/gpl-3.0.txt GPL V3
 * @version  Release: @package_version@
 * @link     http://jakejohns.net
 *
 * @see      \PHPUnit_Framework_TestCase
 */
class HelpersTraitTest extends \PHPUnit_Framework_TestCase
{

    /**
    * setUp
    *
    * @return mixed
    * @throws exceptionclass [description]
    *
    * @access protected
    */
    protected function setUp()
    {
        parent::setUp();
        $this->trait = $this
            ->getMockBuilder('\Jnj\Support\Traits\HelpersTrait')
            ->getMockForTrait();

        $this->factory = function () {
            return $this->helper;
        };

        $this->helper = function () {
            return implode('-', func_get_args());
        };

        $this->helperName = 'foo';
    }


    /**
    * testSetFactory
    *
    * @return mixed
    * @throws exceptionclass [description]
    *
    * @access public
    */
    public function testSetFactory()
    {
        $this->assertSame(
            $this->trait->setHelperFactory($this->helperName, $this->factory),
            $this->trait
        );

    }

    /**
    * testGetHelper
    *
    * @return mixed
    *
    * @access public
    */
    public function testGetHelper()
    {
        $this->trait->setHelperFactory($this->helperName, $this->factory);
        $this->assertSame(
            $this->trait->getHelper($this->helperName),
            $this->helper
        );
    }

    /**
    * testGetHelperException
    *
    * @return mixed
    * @throws exceptionclass [description]
    *
    * @access public
    */
    public function testGetHelperException()
    {
        $this->setExpectedException('\Jnj\Support\Traits\Exceptions\HelperNotFound');
        $this->trait->setHelperFactory($this->helperName, $this->factory);
        $this->trait->getHelper('asd');
    }


    /**
    * testHasHelper
    *
    * @return mixed
    * @throws exceptionclass [description]
    *
    * @access public
    */
    public function testHasHelper()
    {
        $this->trait->setHelperFactory($this->helperName, $this->factory);
        $this->assertTrue(
            $this->trait->hasHelper($this->helperName)
        );
    }

    /**
    * testCall
    *
    * @return mixed
    * @throws exceptionclass [description]
    *
    * @access public
    */
    public function testCall()
    {
        $helper = $this->helper;
        $expected = $helper();

        $this->trait->setHelperFactory($this->helperName, $this->factory);

        $this->assertSame(
            $this->trait->{$this->helperName}(),
            $expected
        );
    }

    /**
    * dataProvider
    *
    * @return mixed
    * @throws exceptionclass [description]
    *
    * @access public
    */
    public function callDataProvider()
    {
        $return = [];

        foreach (range(1, 10) as $num) {
            $args = array_fill(0, $num, rand());
            $return[] = [ $args ];
        }

        return $return;
    }

    /**
    * testCallWithArgs
    *
    * @param mixed $args DESCRIPTION
    *
    * @return mixed
    * @throws exceptionclass [description]
    *
    * @access public
    * @dataProvider callDataProvider
    */
    public function testCallWithArgs($args)
    {
        $helper = $this->helper;
        $expected = call_user_func_array($helper, $args);

        $this->trait->setHelperFactory($this->helperName, $this->factory);

        $this->assertSame(
            call_user_func_array([$this->trait, $this->helperName], $args),
            $expected
        );
    }

}




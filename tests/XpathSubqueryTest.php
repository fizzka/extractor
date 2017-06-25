<?php

class XpathSubqueryTest extends PHPUnit\Framework\TestCase
{
    public function testNotRelative()
    {
        $this->assertEquals(XpathSubquery::get('aaa bbb'), '//aaa//bbb');
    }

    public function testRelative()
    {
        $this->assertEquals(XpathSubquery::get('aaa bbb', true), '/aaa//bbb');
    }

    public function testId()
    {
        $this->assertEquals(XpathSubquery::get('#yyy'), "//*[@id='yyy']");
    }

    public function testClass()
    {
        $this->assertEquals(
            XpathSubquery::get('.yyy'),
            '//*[contains(concat(" ", normalize-space(@class), " "), " yyy ")]'
        );
    }

    public function testAttr()
    {
        $this->assertEquals(XpathSubquery::get('[xxx="yyy"]'), '//*[@xxx="yyy"]');
    }

    public function testAttrPresent()
    {
        $this->assertEquals(XpathSubquery::get('[xxx]'), '//*[@xxx]');
    }

    public function testPseudo()
    {
        $this->assertEquals(XpathSubquery::get(':first-child'), '//*[1]');
        $this->assertEquals(XpathSubquery::get(':last-child'), '//*[last()]');
        $this->assertEquals(XpathSubquery::get(':nth-child(4)'), '//*[position() = 4]');

        $this->assertEquals(
            XpathSubquery::get(':nth-child(odd)'),
            '//*[(position() -1) mod 2 = 0 and position() >= 1]'
        );
        $this->assertEquals(
            XpathSubquery::get(':nth-child(even)'),
            '//*[position() mod 2 = 0 and position() >= 0]'
        );
        $this->assertEquals(
            XpathSubquery::get(':nth-child(5n+2)'),
            '//*[(position() -2) mod 5 = 0 and position() >= 2]'
        );
    }
}

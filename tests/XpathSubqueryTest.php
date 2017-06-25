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
    }
}

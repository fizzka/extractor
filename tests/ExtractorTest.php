<?php

class ExtractorTest extends PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function fromHtml()
    {
        $ex = Extractor::fromHtml('');
        $this->assertInstanceOf('Extractor', $ex);
        $this->assertEmpty((string)$ex);
    }

    public function testCssPath()
    {
        $this->assertEquals(
            'xxx',
            (string)$this->createExtractor()->cssPathFirst('div.c1 div')
        );
    }

    public function testCssPathFirst()
    {
        $this->assertEquals(
            'yyy',
            (string)$this->createExtractor()->cssPathFirst('div.c1 span')
        );

        $this->assertFalse($this->createExtractor()->cssPathFirst('iframe'));
    }

    public function testXpathFirst()
    {
        $this->assertEquals(
            'yyy',
            (string)$this->createExtractor()->xpathFirst('//div//span')
        );

        $this->assertFalse($this->createExtractor()->xpathFirst('//iframe'));
    }

    public function testXpathAfterCss()
    {
        $this->assertEquals(
            'yyy',
            (string)$this->createExtractor()->cssPathFirst('div.c1')->xpathFirst('.//span')
        );
    }

    public function testCssAfterCss()
    {
        $this->assertNotEquals(
            'foo',
            (string)$this->createExtractor()->cssPathFirst('div.c1')->cssPathFirst('span.s1')
        );

        $this->assertEquals(
            'baz',
            (string)$this->createExtractor()->cssPathFirst('div.c1')->cssPathFirst('span.s1')
        );
    }

    public function testGet()
    {
        $this->assertCount(3, $this->createExtractor()->get('div'));
    }

    public function testGetElements()
    {
        $this->assertCount(3, $this->createExtractor()->getElements('//div'));
    }

    /**
     * @test
     */
    public function innetText()
    {
        $this->assertEquals('baz123', $this->createExtractor()->cssPathFirst('.s2')->innerText());
    }

    private function createExtractor()
    {
        return Extractor::fromHtml(<<<HTML
<html>
    <body>
        <span class="s1">foo</span>
        <div class="c1">
            <div>xxx</div>
            <div>
                <span>yyy</span>
                <span>zzz</span>
                <span class="s1">baz</span>
                <span class="s2">baz<p>123</p></span>
            </div>
        </div>
    </body>
</html>
HTML
        );
    }
}

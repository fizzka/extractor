<?php

class ExtractorTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
		$this->sut = Extractor::fromHtml(<<<HTML
<html>
	<body>
		<div class="c1">
			<div>xxx</div>
			<div>
				<span>yyy</span>
				<span>zzz</span>
			</div>
		</div>
	</body>
</html>
HTML
		);
	}

	public function testCssPath() {
		$this->assertEquals(
			'xxx',
			(string)$this->sut->cssPathFirst('div.c1 div')
		);
	}

	public function testCssPathFirst() {
		$this->assertEquals(
			'yyy',
			(string)$this->sut->cssPathFirst('div.c1 span')
		);

		$this->assertFalse($this->sut->cssPathFirst('iframe'));
	}

	public function testXpathFirst() {
		$this->assertEquals(
			'yyy',
			(string)$this->sut->xpathFirst('//div//span')
		);

		$this->assertFalse($this->sut->xpathFirst('//iframe'));
	}

	public function testXpathAfterCss() {
		$this->assertEquals(
			'yyy',
			(string)$this->sut->cssPathFirst('div.c1')->xpathFirst('.//span')
		);
	}

	public function testGet() {
		$this->assertCount(3, $this->sut->get('div'));
	}

	public function testGetElements() {
		$this->assertCount(3, $this->sut->getElements('//div'));
	}
}

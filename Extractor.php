<?php

class Extractor extends SimpleXMLElement {

	public static function fromHtml($htmlString) {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->preserveWhiteSpace = false;

		if (strlen($htmlString)){
			libxml_use_internal_errors(true);
			$dom->loadHTML($htmlString);
			libxml_clear_errors();
		}

		return static::fromDom($dom);
	}

	public static function fromDom($dom) {
		return simplexml_import_dom($dom, get_class());
	}

	public function cssPath($expression) {
		return $this->xpath(XpathSubquery::get($expression));
	}

	public function cssPathFirst($expression) {
		$res = $this->cssPath($expression);
		if (count($res)) {
			return $res[0];
		}

		return false;
	}

	public function xpathFirst($xpathQuery) {
		$res = $this->xpath($xpathQuery);
		if (count($res)) {
			return $res[0];
		}

		return false;
	}

	/**
	 * @deprecated, for compatibility with nokogiri (@see)
	 */
	public function get($expression) {
		return $this->cssPath($expression);
	}

	/**
	 * @deprecated, for compatibility with nokogiri (@see)
	 */
	public function getElements($xpathQuery) {
		return $this->xpath($xpathQuery);
	}
}

<?php
class PhDReader extends XMLReader {
	protected $map = array();

	public function __construct($file, $encoding = "UTF-8", $options = null) {
		if (!parent::open($file, $encoding, $options)) {
			throw new Exception();
		}
	}
	public function seek($id) {
		while(parent::read()) {
			if ($this->nodeType == XMLREADER::ELEMENT && $this->hasAttributes && $this->moveToAttributeNs("id", "http://www.w3.org/XML/1998/namespace") && $this->value == $id) {
				return $this->moveToElement();
			}
		}
		return false;
	}
	public function nextNode() {
		while($this->read()) {
			switch($this->nodeType) {
			case XMLReader::ELEMENT:
				if($this->isEmptyElement) { continue; }
			case XMLReader::TEXT:
			case XMLReader::CDATA:
			case XMLReader::END_ELEMENT:
				return true;

			}
		}
		return false;
	}
	public function readNode($nodeName) {
		return $this->read() && !($this->nodeType == XMLReader::END_ELEMENT && $this->name == $nodeName);
	}
	public function readContent($node = null) {
		$retval = "";
		if (!$node) {
			$node = $this->name;
		}
		if ($this->readNode($node)) {
			$retval = $this->value;
			$this->read(); // Jump over END_ELEMENT too
		}
		return $retval;
	}
	public function readAttribute($attr) {
		return $this->moveToAttribute($attr) ? $this->value : "";
	}
	public function __call($func, $args) {
		if($this->nodeType == XMLReader::END_ELEMENT) { /* ignore */ return;}
		trigger_error("No mapper for $func", E_USER_WARNING);

		/* NOTE:
		 *       The _content_ of the element will get processed even though we dont 
		 *       know how to handle the elment itself
		 */
		return "";
	}
	public function transform() {
		$type = $this->nodeType;
		$name = $this->name;

		switch($type) {
		case XMLReader::ELEMENT:
		case XMLReader::END_ELEMENT:
			if(isset($this->map[$name])) {
				return $this->transormFromMap($type == XMLReader::ELEMENT, $name);
			}
			return call_user_func(array($this, "format_" . $name), $type == XMLReader::ELEMENT);
			break;
		case XMLReader::TEXT:
			return $this->value;
			break;
		case XMLReader::CDATA:
			return $this->highlight_php_code($this->value);
			break;
		case XMLReader::COMMENT:
		case XMLReader::WHITESPACE:
		case XMLReader::SIGNIFICANT_WHITESPACE:
			/* swallow it */
			$this->read();
			return $this->transform();
		default:
			trigger_error("Dunno what to do with {$this->name} {$this->nodeType}", E_USER_ERROR);
			return "";
		}
	}
}


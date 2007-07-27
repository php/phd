<?php
class PhDXHTMLReader extends PhDReader {
	protected $map = array( /* {{{ */
		'application'           => 'span',
		'classname'             => 'span',
		'code'                  => 'code',
		'collab'                => 'span',
		'collabname'            => 'span',
		'command'               => 'span',
		'computeroutput'        => 'span',
		'constant'              => 'span',
		'emphasis'              => 'em',
		'enumname'              => 'span',
		'envar'                 => 'span',
		'filename'              => 'span',
		'glossterm'             => 'span',
		'holder'                => 'span',
		'informaltable'         => 'table',
		'itemizedlist'          => 'ul',
		'listitem'              => 'li',
		'literal'               => 'span',
		'mediaobject'           => 'div',
		'methodparam'           => 'span',
		'member'                => 'li',
		'note'                  => 'div',
		'option'                => 'span',    
		'orderedlist'           => 'ol',
		'para'                  => 'p',
		'parameter'             => 'span',
		'productname'           => 'span',
		'propname'              => 'span',
		'property'              => 'span',
		'proptype'              => 'span',
		'simplelist'            => 'ul',
		'simpara'               => 'p',
		'title'                 => 'h1',
		'year'                  => 'span',
	); /* }}} */

	public function format_refentry($open) {
		if($open) {
			return '<div>';
		}

		echo "</div>";
		if ($this->hasAttributes && $this->moveToAttributeNs("id", "http://www.w3.org/XML/1998/namespace")) {
			$id = $this->value;
		}
		$content = ob_get_contents();
		ob_clean();
		file_put_contents("cache/$id.html", $content);
	}
	public function format_function($open) {
		return sprintf('<a href="function.%1$s.html">%1$s</a>', $this->readContent());
	}
	public function format_refsect1($open) {
		if($open) {
			return sprintf('<div class="refsect %s">', $this->readAttribute("role"));
		}
		return "</div>\n";
	}
	public function format_link($open) {
		$this->moveToNextAttribute();
		$href = $this->value;
		$class = $this->name;
		$content = $this->readContent("link");
		return sprintf('<a href="%s" class="%s">%s</a>', $href, $class, $content);
	}
	public function format_methodsynopsis($open) {
		/* We read this element to END_ELEMENT so $open is useless */

		$content = '<div class="methodsynopsis">';
		$root = $this->name;

		while($this->readNode($root)) {
			if($this->nodeType == XMLReader::END_ELEMENT) {
				$content .= "</span>\n";
				continue;
			}
			$name = $this->name;
			switch($name) {
			case "type":
			case "parameter":
			case "methodname":
				$content .= sprintf('<span class="%s">%s</span>', $name, $this->readContent($name));
				break;
			case "methodparam":
				$content .= '<span class="methodparam">';
				break;
			}
		}
		$content .= "</div>";
		return $content;
	}
	public function transormFromMap($open, $name) {
		$tag = $this->map[$name];
		if($open) {
			return sprintf('<%s class="%s">', $tag, $name);
		}
		return "</$tag>";
	}
	public function format_listing_hyperlink_function($matches) {
		$link = str_replace('_', '-', $matches[1]);
		$link = "function{$link}.html";
		return '<a class="phpfunc" href="' . $link . '">' . $matches[1] . '</a></span>' . $matches[2];
	}
	public function highlight_php_code($str) { /* copy&paste from livedocs */
		if (is_array($str)) {
			$str = $str[0];
		}
	
		$tmp = str_replace(
			array(
				'&nbsp;',
				'<font color="',        // for PHP 4
				'<span style="color: ', // for PHP 5.0.0RC1
				'</font>',
				"\n ",
				'  '
			),
			array(
				' ',
				'<span class="',
				'<span class="',
				'</span>',
				"\n&nbsp;",
				' &nbsp;'
			),
			highlight_string($str, true)
		);
	
		$tmp = preg_replace_callback('{([\w_]+)\s*</span>(\s*<span\s+class="keyword">\s*\()}m', array($this, 'format_listing_hyperlink_function'), $tmp);
		return sprintf('<div class="phpcode">%s</div>', $tmp);
	}
}


<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette\Web
 */

/*namespace Nette\Web;*/



/**
 * HTML helper.
 *
 * <code>
 * $anchor = Html::el('a')->href($link)->setText('Nette');
 * $el->class = 'myclass';
 * echo $el;
 *
 * echo $el->startTag(), $el->endTag();
 * </code>
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Web
 */
class Html extends /*Nette\*/Object implements /*\*/ArrayAccess, /*\*/Countable, /*\*/IteratorAggregate
{
	/** @var string  element's name */
	private $name;

	/** @var bool  is element empty? */
	private $isEmpty;

	/** @var array  element's attributes */
	public $attrs = array();

	/** @var array  of Html | string nodes */
	protected $children = array();

	/** @var bool  use XHTML syntax? */
	public static $xhtml = TRUE;

	/** @var array  empty elements */
	public static $emptyElements = array('img'=>1,'hr'=>1,'br'=>1,'input'=>1,'meta'=>1,'area'=>1,'command'=>1,'keygen'=>1,'source'=>1,
		'base'=>1,'col'=>1,'link'=>1,'param'=>1,'basefont'=>1,'frame'=>1,'isindex'=>1,'wbr'=>1,'embed'=>1);



	/**
	 * Static factory.
	 * @param  string element name (or NULL)
	 * @param  array|string element's attributes (or textual content)
	 * @return Html
	 */
	public static function el($name = NULL, $attrs = NULL)
	{
		$el = new /**/self/**/ /*static*/;
		$parts = explode(' ', $name, 2);
		$el->setName($parts[0]);

		if (is_array($attrs)) {
			$el->attrs = $attrs;

		} elseif ($attrs !== NULL) {
			$el->setText($attrs);
		}

		if (isset($parts[1])) {
			preg_match_all('#([a-z0-9:-]+)(?:=(["\'])?(.*?)(?(2)\\2|\s))?#i', $parts[1] . ' ', $parts, PREG_SET_ORDER);
			foreach ($parts as $m) {
				$el->attrs[$m[1]] = isset($m[3]) ? $m[3] : TRUE;
			}
		}

		return $el;
	}



	/**
	 * Changes element's name.
	 * @param  string
	 * @param  bool  Is element empty?
	 * @return Html  provides a fluent interface
	 * @throws \InvalidArgumentException
	 */
	final public function setName($name, $isEmpty = NULL)
	{
		if ($name !== NULL && !is_string($name)) {
			throw new /*\*/InvalidArgumentException("Name must be string or NULL, " . gettype($name) ." given.");
		}

		$this->name = $name;
		$this->isEmpty = $isEmpty === NULL ? isset(self::$emptyElements[$name]) : (bool) $isEmpty;
		return $this;
	}



	/**
	 * Returns element's name.
	 * @return string
	 */
	final public function getName()
	{
		return $this->name;
	}



	/**
	 * Is element empty?
	 * @return bool
	 */
	final public function isEmpty()
	{
		return $this->isEmpty;
	}



	/**
	 * Overloaded setter for element's attribute.
	 * @param  string    HTML attribute name
	 * @param  mixed     HTML attribute value
	 * @return void
	 */
	final public function __set($name, $value)
	{
		$this->attrs[$name] = $value;
	}



	/**
	 * Overloaded getter for element's attribute.
	 * @param  string    HTML attribute name
	 * @return mixed     HTML attribute value
	 */
	final public function &__get($name)
	{
		return $this->attrs[$name];
	}



	/**
	 * Overloaded unsetter for element's attribute.
	 * @param  string    HTML attribute name
	 * @return void
	 */
	final public function __unset($name)
	{
		unset($this->attrs[$name]);
	}



	/**
	 * Overloaded setter for element's attribute.
	 * @param  string  HTML attribute name
	 * @param  array   (string) HTML attribute value or pair?
	 * @return Html  provides a fluent interface
	 */
	final public function __call($m, $args)
	{
		$p = substr($m, 0, 3);
		if ($p === 'get' || $p === 'set' || $p === 'add') {
			$m = substr($m, 3);
			$m[0] = $m[0] | "\x20";
			if ($p === 'get') {
				return isset($this->attrs[$m]) ? $this->attrs[$m] : NULL;

			} elseif ($p === 'add') {
				$args[] = TRUE;
			}
		}

		if (count($args) === 1) { // set
			$this->attrs[$m] = $args[0];

		} elseif ($args[0] == NULL) { // intentionally ==
			$tmp = & $this->attrs[$m]; // appending empty value? -> ignore, but ensure it exists

		} elseif (!isset($this->attrs[$m]) || is_array($this->attrs[$m])) { // needs array
			$this->attrs[$m][$args[0]] = $args[1];

		} else {
			$this->attrs[$m] = array($this->attrs[$m], $args[0] => $args[1]);
		}

		return $this;
	}



	/**
	 * Special setter for element's attribute.
	 * @param  string path
	 * @param  array query
	 * @return Html  provides a fluent interface
	 */
	final public function href($path, $query = NULL)
	{
		if ($query) {
			$query = http_build_query($query, NULL, '&');
			if ($query !== '') $path .= '?' . $query;
		}
		$this->attrs['href'] = $path;
		return $this;
	}



	/**
	 * Sets element's HTML content.
	 * @param  string
	 * @return Html  provides a fluent interface
	 * @throws \InvalidArgumentException
	 */
	final public function setHtml($html)
	{
		if ($html === NULL) {
			$html = '';

		} elseif (is_array($html)) {
			throw new /*\*/InvalidArgumentException("Textual content must be a scalar, " . gettype($html) ." given.");

		} else {
			$html = (string) $html;
		}

		$this->removeChildren();
		$this->children[] = $html;
		return $this;
	}



	/**
	 * Returns element's HTML content.
	 * @return string
	 */
	final public function getHtml()
	{
		$s = '';
		foreach ($this->children as $child) {
			if (is_object($child)) {
				$s .= $child->render();
			} else {
				$s .= $child;
			}
		}
		return $s;
	}



	/**
	 * Sets element's textual content.
	 * @param  string
	 * @return Html  provides a fluent interface
	 * @throws \InvalidArgumentException
	 */
	final public function setText($text)
	{
		if (!is_array($text)) {
			$text = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), (string) $text);
		}
		return $this->setHtml($text);
	}



	/**
	 * Returns element's textual content.
	 * @return string
	 */
	final public function getText()
	{
		return html_entity_decode(strip_tags($this->getHtml()), ENT_QUOTES, 'UTF-8');
	}



	/**
	 * Adds new element's child.
	 * @param  Html|string child node
	 * @return Html  provides a fluent interface
	 */
	final public function add($child)
	{
		return $this->insert(NULL, $child);
	}



	/**
	 * Creates and adds a new Html child.
	 * @param  string  elements's name
	 * @param  array|string element's attributes (or textual content)
	 * @return Html  created element
	 */
	final public function create($name, $attrs = NULL)
	{
		$this->insert(NULL, $child = /**/self/**/ /*static*/::el($name, $attrs));
		return $child;
	}



	/**
	 * Inserts child node.
	 * @param  int
	 * @param  Html node
	 * @param  bool
	 * @return Html  provides a fluent interface
	 * @throws \Exception
	 */
	public function insert($index, $child, $replace = FALSE)
	{
		if ($child instanceof Html || is_scalar($child)) {
			if ($index === NULL)  { // append
				$this->children[] = $child;

			} else { // insert or replace
				array_splice($this->children, (int) $index, $replace ? 1 : 0, array($child));
			}

		} else {
			throw new /*\*/InvalidArgumentException("Child node must be scalar or Html object, " . (is_object($child) ? get_class($child) : gettype($child)) ." given.");
		}

		return $this;
	}



	/**
	 * Inserts (replaces) child node (\ArrayAccess implementation).
	 * @param  int
	 * @param  Html node
	 * @return void
	 */
	final public function offsetSet($index, $child)
	{
		$this->insert($index, $child, TRUE);
	}



	/**
	 * Returns child node (\ArrayAccess implementation).
	 * @param  int index
	 * @return mixed
	 */
	final public function offsetGet($index)
	{
		return $this->children[$index];
	}



	/**
	 * Exists child node? (\ArrayAccess implementation).
	 * @param  int index
	 * @return bool
	 */
	final public function offsetExists($index)
	{
		return isset($this->children[$index]);
	}



	/**
	 * Removes child node (\ArrayAccess implementation).
	 * @param  int index
	 * @return void
	 */
	public function offsetUnset($index)
	{
		if (isset($this->children[$index])) {
			array_splice($this->children, (int) $index, 1);
		}
	}



	/**
	 * Required by the \Countable interface.
	 * @return int
	 */
	final public function count()
	{
		return count($this->children);
	}



	/**
	 * Removed all children.
	 * @return void
	 */
	public function removeChildren()
	{
		$this->children = array();
	}



	/**
	 * Iterates over a elements.
	 * @param  bool    recursive?
	 * @param  string  class types filter
	 * @return \RecursiveIterator
	 */
	final public function getIterator($deep = FALSE)
	{
		if ($deep) {
			$deep = $deep > 0 ? /*\*/RecursiveIteratorIterator::SELF_FIRST : /*\*/RecursiveIteratorIterator::CHILD_FIRST;
			return new /*\*/RecursiveIteratorIterator(new RecursiveHtmlIterator($this->children), $deep);

		} else {
			return new RecursiveHtmlIterator($this->children);
		}
	}



	/**
	 * Returns all of children.
	 * return array
	 */
	final public function getChildren()
	{
		return $this->children;
	}



	/**
	 * Renders element's start tag, content and end tag.
	 * @param  int indent
	 * @return string
	 */
	final public function render($indent = NULL)
	{
		$s = $this->startTag();

		if (!$this->isEmpty) {
			// add content
			if ($indent !== NULL) {
				$indent++;
			}
			foreach ($this->children as $child) {
				if (is_object($child)) {
					$s .= $child->render($indent);
				} else {
					$s .= $child;
				}
			}

			// add end tag
			$s .= $this->endTag();
		}

		if ($indent !== NULL) {
			return "\n" . str_repeat("\t", $indent - 1) . $s . "\n" . str_repeat("\t", max(0, $indent - 2));
		}
		return $s;
	}



	final public function __toString()
	{
		return $this->render();
	}



	/**
	 * Returns element's start tag.
	 * @return string
	 */
	final public function startTag()
	{
		if ($this->name) {
			return '<' . $this->name . $this->attributes() . (self::$xhtml && $this->isEmpty ? ' />' : '>');

		} else {
			return '';
		}
	}



	/**
	 * Returns element's end tag.
	 * @return string
	 */
	final public function endTag()
	{
		return $this->name && !$this->isEmpty ? '</' . $this->name . '>' : '';
	}



	/**
	 * Returns element's attributes.
	 * @return string
	 */
	final public function attributes()
	{
		if (!is_array($this->attrs)) {
			return '';
		}

		$s = '';
		foreach ($this->attrs as $key => $value)
		{
			// skip NULLs and false boolean attributes
			if ($value === NULL || $value === FALSE) continue;

			// true boolean attribute
			if ($value === TRUE) {
				// in XHTML must use unminimized form
				if (self::$xhtml) $s .= ' ' . $key . '="' . $key . '"';
				// in HTML should use minimized form
				else $s .= ' ' . $key;
				continue;

			} elseif (is_array($value)) {

				// prepare into temporary array
				$tmp = NULL;
				foreach ($value as $k => $v) {
					// skip NULLs & empty string; composite 'style' vs. 'others'
					if ($v == NULL) continue; // intentionally ==

					//  composite 'style' vs. 'others'
					$tmp[] = is_string($k) ? ($v === TRUE ? $k : $k . ':' . $v) : $v;
				}
				if ($tmp === NULL) continue;

				$value = implode($key === 'style' || !strncmp($key, 'on', 2) ? ';' : ' ', $tmp);

			} else {
				$value = (string) $value;
			}

			// add new attribute
			$s .= ' ' . $key . '="'
				. str_replace(array('&', '"', '<', '>', '@'), array('&amp;', '&quot;', '&lt;', '&gt;', '&#64;'), $value)
					. '"';
		}
		return $s;
	}



	/**
	 * Clones all children too.
	 */
	public function __clone()
	{
		foreach ($this->children as $key => $value) {
			if (is_object($value)) {
				$this->children[$key] = clone $value;
			}
		}
	}

}






/**
 * Recursive HTML element iterator. See Html::getIterator().
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Web
 */
class RecursiveHtmlIterator extends /*\*/RecursiveArrayIterator implements /*\*/Countable
{

	/**
	 * The sub-iterator for the current element.
	 * @return \RecursiveIterator
	 */
	public function getChildren()
	{
		return $this->current()->getIterator();
	}



	/**
	 * Returns the count of elements.
	 * @return int
	 */
	public function count()
	{
		return iterator_count($this);
	}

}

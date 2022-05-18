<?php

namespace BuzzingPixel\Treasury\Service\FilePicker;

// use EllisLab\ExpressionEngine\Service\URL\URLFactory;
// use BuzzingPixel\Treasury\Service\LocationsService;

class Link
{
	/**
	 * FilePicker instance
	 */
	protected $filepicker;

	/**
	 * Link HTML
	 */
	protected $html;

	/**
	 * Link attributes
	 */
	protected $attributes = array(
		'class' => '',
		'rel' => ''
	);

	/**
	 * Constructor
	 *
	 * @param FilePicker $fp
	 */
	public function __construct(FilePicker $fp)
	{
		$this->filepicker = $fp;
	}

	/**
	 * ToString Magic Method
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Render the link
	 *
	 * @return String An html link
	 */
	public function render()
	{
		// Get the URL from the file picker
		$url = $this->filepicker->getUrl();

		// Set the href attribute
		$this->setAttribute('href', $url->compile());

		// Get attributes
		$attr = '';
		foreach ($this->attributes as $key => $value) {
			if ($key == 'class') {
				$value = "m-link js-treasury-filepicker {$value}";
			} elseif ($key === 'rel') {
				$value = "js-treasury-file-picker-modal {$value}";
			}

			$attr .= " {$key}='{$value}'";
		}

		// Return button
		return "<a{$attr}>{$this->html}</a>";
	}

	/**
	 * Set an HTML attribute on the link
	 *
	 * @param String $k The attribute key
	 * @param String $v The attribute value
	 * @return Link
	 */
	public function setAttribute($k, $v)
	{
		$this->attributes[$k] = $v;
		return $this;
	}

	/**
	 * Set several HTML attributes on the link
	 *
	 * @param Array[String] $attr Key/value pairs of attributes
	 * @return Link
	 */
	public function addAttributes($attr)
	{
		foreach ($attr as $k => $v) {
			$this->setAttribute($k, $v);
		}

		return $this;
	}

	/**
	 * Set the innerText of the link. Encodes html.
	 *
	 * @param String $text The link text
	 * @return Link
	 */
	public function setText($text)
	{
		$this->setHtml(htmlentities($text));
		return $this;
	}

	/**
	 * Set the innerHTML of the link
	 *
	 * @param String $html The link html
	 * @return Link
	 */
	public function setHtml($html)
	{
		$this->html = $html;
		return $this;
	}
}

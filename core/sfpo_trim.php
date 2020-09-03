<?php declare(strict_types=1);

/**
* @package Show First Post Only To Guest
* @copyright (c) 2020 Rich McGirr (RMcGirr83)
* much thanks to JoshyPHP for the assistance...
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace rmcgirr83\sfpo\core;

use DOMDocument;
use DOMElement;
use DOMText;

class sfpo_trim
{
	/**
	* @var DOMDocument
	*/
	protected $dom_document;

	/**
	* @var int Current length of text processed
	*/
	protected $length;

	/**
	* @var int Current length of text processed
	*/
	protected $max_length;

	/**
	* @param  string $html Original HTML
	* @param  int    $max  Max length of text kept
	* @return string       Modified HTML
	*/
	public function trimHtml(string $html, int $max): string
	{
		$html = '<?xml encoding="utf-8"?><html><body><div>' . $html . '</div></body></html>';

		$this->dom_document = new DOMDocument;
		$this->dom_document->loadHTML($html, LIBXML_COMPACT | LIBXML_HTML_NODEFDTD | LIBXML_NOCDATA | LIBXML_NOENT | LIBXML_NONET);

		$this->length = 0;
		$this->max_length = $max;

		$this->trimElement($this->dom_document->documentElement->firstChild);

		$html = $this->dom_document->saveHTML($this->dom_document->documentElement->firstChild->firstChild);

		$html = substr($html, 5, -6);

		return $html;
	}

	protected function trimElement(DOMElement $element): void
	{
		$i = 0;
		while ($i < $element->childNodes->length)
		{
			$child = $element->childNodes[$i++];

			if ($this->length >= $this->max_length)
			{
				--$i;
				$element->removeChild($child);
			}
			else if ($child instanceof dom_element)
			{
				$this->trimElement($child);
			}
			else if ($child instanceof DOMText)
			{
				$max = $this->max_length - $this->length;
				if ($child->length > $max)
				{
					$child->deleteData($max, $child->length);
				}
				$this->length += $child->length;
			}
		}
	}
}

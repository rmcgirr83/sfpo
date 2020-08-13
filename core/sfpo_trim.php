<?php
/**
*
* @package Show First Post Only To Guest
* @copyright (c) 2020 Rich McGirr (RMcGirr83)
* much thanks to JoshyPHP for the assistance...
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

declare(strict_types=1);

namespace rmcgirr83\sfpo\core;

use DOMDocument;
use DOMElement;
use DOMText;

class sfpo_trim
{
	/**
	* @var DOMDocument
	*/
	protected $dom;

	/**
	* @var int Current length of text processed
	*/
	protected $len;

	/**
	* @var int Current length of text processed
	*/
	protected $max;

	/**
	* @param  string $html Original HTML
	* @param  int    $max  Max length of text kept
	* @return string       Modified HTML
	*/
	public function trimHtml(string $html, int $max): string
	{
		$html = '<?xml encoding="utf-8"?><html><body><div>' . $html . '</div></body></html>';

		$this->dom = new DOMDocument;
		$this->dom->loadHTML($html, LIBXML_COMPACT | LIBXML_HTML_NODEFDTD | LIBXML_NOCDATA | LIBXML_NOENT | LIBXML_NONET);

		$this->len = 0;
		$this->max = $max;

		$this->trimElement($this->dom->documentElement->firstChild);

		$html = $this->dom->saveHTML($this->dom->documentElement->firstChild->firstChild);

		$html = substr($html, 5, -6);

		return $html;
	}

	protected function trimElement(DOMElement $element): void
	{

		$i = 0;
		while ($i < $element->childNodes->length)
		{
			$child = $element->childNodes[$i++];

			if ($this->len >= $this->max)
			{
				--$i;
				$element->removeChild($child);
			}
			elseif ($child instanceof DOMElement)
			{
				$this->trimElement($child);
			}
			elseif ($child instanceof DOMText)
			{
				$max = $this->max - $this->len;
				if ($child->length > $max)
				{
					$child->deleteData($max, $child->length);
				}
				$this->len += $child->length;
			}
		}
	}
}
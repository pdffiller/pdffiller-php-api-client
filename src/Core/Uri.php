<?php
/**
 * Class Uri
 *
 * @package PDFfiller\OAuth2\Client\Provider
 *
 * @property string $baseUri
 */

namespace PDFfiller\OAuth2\Client\Provider\Core;


class Uri
{
	private $baseUri;

	public function __construct($baseUri)
	{
		$this->setBaseUri($baseUri);
	}

	public function setBaseUri($baseUri)
	{
		$this->baseUri = $baseUri;
		return $this;
	}

	public function getBaseUri()
	{
		return $this->baseUri;
	}

	function resolve($path, $uri = '')
	{
		if (!empty($uri)) {
			$this->setBaseUri($uri);
		}
		$uri = $this->getBaseUri();
		$url = parse_url($uri);

		$str = '';
		$str .= $url['scheme'] . '://';

		if (isset($url['user']) || isset($url['pass'])) {
			$str .= $url['user'] .':' . $url['pass'] . '@';
		}

		if (isset($url['host'])) {
			$str .= $url['host'];
		}

		if (isset($url['port'])) {
			$str .= ':'. $url['port'];
		}

		$str .= $url['path'] . $path;

		if (isset($url['query'])) {
			$str .= '?' . $url['query'];
		}

		if (isset($url['fragment'])) {
			$str .= '#' . $url['fragment'];
		}

		return $str;
	}
}

<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Client;

use AsisTeam\ARES\Exception\ResponseException;

interface IRequester
{

	/**
	 * Makes HTTP GET request and returns response body
	 * Or throws ResponseException on any error or non 200 response StatusCode
	 *
	 * @throws ResponseException
	 */
	public function get(string $url): string;

	/**
	 * Set max query timeout
	 *
	 * @return IRequester
	 */
	public function setTimeout(?int $timeout): self;

}

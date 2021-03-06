<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Client;

use AsisTeam\ARES\Exception\ResponseException;

final class Requester implements IRequester
{

	private const SUCCESS_HEADERS_START = 'HTTP/1.1 200 OK';

	/** @var int|null */
	private $timeout;

	public function __construct(?int $timeout = null)
	{
		$this->timeout = $timeout;
	}

	/**
	 * @inheritdoc
	 */
	public function get(string $url): string
	{
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HEADER => 1,
			CURLOPT_URL => $url,
		]);

		if ($this->timeout !== null) {
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		}

		$resp = curl_exec($curl);
		if ($resp === false) {
			throw new ResponseException(sprintf('Curl error: %s', curl_error($curl)));
		}

		$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$headers = substr((string) $resp, 0, $headerSize);
		curl_close($curl);

		if (substr($headers, 0, strlen(self::SUCCESS_HEADERS_START)) !== self::SUCCESS_HEADERS_START) {
			throw new ResponseException(sprintf('Server responded with invalid headers: %s', $headers));
		}

		// response body to be returned
		return substr((string) $resp, $headerSize);
	}

	/**
	 * @inheritdoc
	 */
	public function setTimeout(?int $timeout): IRequester
	{
		$this->timeout = $timeout;
		return $this;
	}

}

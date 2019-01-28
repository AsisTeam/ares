<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Client;

use AsisTeam\ARES\Client\Response\FindResponse;
use AsisTeam\ARES\Entity\Subject;
use AsisTeam\ARES\Exception\InvalidArgumentException;
use AsisTeam\ARES\Exception\RequestException;
use Nette\Utils\Strings;

final class Finder
{

	private const URL = 'http://wwwinfo.mfcr.cz/cgi-bin/ares/ares_es.cgi';

	/** @var IRequester */
	private $requester;

	public function __construct(?IRequester $requester = null)
	{
		$this->requester = $requester ?? new Requester();
	}

	public function findById(string $companyId): ?Subject
	{
		$url = self::URL . '?' . http_build_query(['ico' => $this->prepareCompanyId($companyId)]);
		$contents = $this->requester->get($url);

		return FindResponse::parseFind($contents)[0] ?? null;
	}

	/**
	 * @return Subject[]
	 */
	public function findByName(string $name): array
	{
		$url = self::URL . '?' . http_build_query(['obch_jm' => $this->prepareCompanyName($name)]);
		$contents = $this->requester->get($url);

		return FindResponse::parseFind($contents);
	}

	private function prepareCompanyId(string $companyId): string
	{
		try {
			Subject::assertCompanyId($companyId);
		} catch (InvalidArgumentException $e) {
			throw new RequestException($e->getMessage(), $e->getCode(), $e);
		}

		return $companyId;
	}

	private function prepareCompanyName(string $name): string
	{
		try {
			Subject::assertName($name);
		} catch (InvalidArgumentException $e) {
			throw new RequestException($e->getMessage(), $e->getCode(), $e);
		}

		return trim(strtolower(Strings::toAscii($name)));
	}

}

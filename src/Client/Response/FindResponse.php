<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Client\Response;

use AsisTeam\ARES\Entity\Subject;
use AsisTeam\ARES\Exception\ResponseException;
use AsisTeam\ARES\Exception\TooManySubjectsException;
use SimpleXMLElement;

final class FindResponse
{

	/**
	 * @return Subject[]
	 */
	public static function parseFind(string $contents): array
	{
		$aresResponse = self::parseAresResponse($contents);
		$ns = $aresResponse->getNamespaces(true);

		if (!isset($ns['dtt'])) {
			throw new ResponseException('Missing namespace "dtt" in "Odpoved".');
		}

		$responses = $aresResponse->children($ns['dtt']);

		if (isset($responses->Help[0]->R[0])) {
			$helpText = trim((string) $responses->Help[0]->R[0]);
			$tooMany = 'Zadané parametry vedou k výběru více subjektů než je zadáno v "Zobrazit vět".';

			if (substr($helpText, 0, strlen($tooMany)) === $tooMany) {
				throw new TooManySubjectsException('Too many subjects match given params');
			}
		}

		if (!isset($responses->Pocet_zaznamu)) {
			throw new ResponseException('Missing "Pocet_zaznamu" response key.');
		}

		$subjects = [];

		if ((int) $responses->Pocet_zaznamu === 0) {
			return $subjects;
		}

		if (!isset($responses->V[0])) {
			throw new ResponseException('Missing "V" response key.');
		}

		foreach ($responses->V->S as $subj) {
			$vatIdNumber = null;

			if (isset($subj->p_dph) && substr((string) $subj->p_dph, 0, 4) === 'dic=') {
				$vatIdNumber = substr((string) $subj->p_dph, 4);
			}

			$subjects[] = new Subject(
				(string) $subj->ojm,
				(string) $subj->ico,
				(string) $subj->jmn,
				(int) $subj->pf,
				$vatIdNumber
			);
		}

		return $subjects;
	}

	private static function parseAresResponse(string $contents): SimpleXMLElement
	{
		$xml = new SimpleXMLElement($contents);
		$ns = $xml->getNamespaces(true);

		if (!isset($ns['are'])) {
			throw new ResponseException('Namespace "are" is missing.');
		}

		$children = $xml->children($ns['are']);

		if (!isset($children->Odpoved[0])) {
			throw new ResponseException('Missing "Ares_Odpovedi->Odpoved" nodes in response.');
		}

		return $children->Odpoved[0];
	}

}

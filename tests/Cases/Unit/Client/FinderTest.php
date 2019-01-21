<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Tests\Cases\Unit\Client;

use AsisTeam\ARES\Client\Finder;
use AsisTeam\ARES\Client\IRequester;
use Mockery;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class FinderTest extends TestCase
{

	public function testFindById(): void
	{
		$finder = new Finder($this->createRequester('find_single.xml'));
		$subject = $finder->findById($ic = '28319061');

		Assert::equal($ic, $subject->getCompanyId());
		Assert::equal('28319061', $subject->getVatId());
		Assert::equal('AsisTeam s.r.o.', $subject->getName());
		Assert::equal('Praha 1, Staré Město, Kaprova 42/14', $subject->getAddress());
	}

	public function testFindByName(): void
	{
		$finder = new Finder($this->createRequester('find_multiple.xml'));
		$subjects = $finder->findByName('Asis');

		Assert::count(5, $subjects);

		Assert::equal('04925190', $subjects[0]->getCompanyId());
		Assert::equal('04925190', $subjects[0]->getVatId());
		Assert::equal('A.S.I.S. Technologies s.r.o.', $subjects[0]->getName());
		Assert::equal('Zádveřice-Raková 395', $subjects[0]->getAddress());

		// ...

		Assert::equal('27046583', $subjects[4]->getCompanyId());
		Assert::null($subjects[4]->getVatId());
		Assert::equal('Mezinárodní asociace bezpečnostního managementu - ASIS CZ, z.s.', $subjects[4]->getName());
		Assert::equal('Praha 2, Nové Město, Lazarská 11/6', $subjects[4]->getAddress());
	}

	public function testFindNoMatch(): void
	{
		$finder = new Finder($this->createRequester('find_no_match.xml'));
		Assert::null($finder->findById('58525212'));
	}

	/**
	 * @throws AsisTeam\ARES\Exception\ResponseException Too many subjects match given params
	 */
	public function testFindTooMany(): void
	{
		$finder = new Finder($this->createRequester('find_too_many.xml'));
		$finder->findByName('Company');
	}

	private function createRequester(string $filename): IRequester
	{
		return Mockery::mock(IRequester::class)
			->shouldReceive('get')->once()
			->andReturn(file_get_contents(__DIR__ . '/responses/' . $filename))
			->getMock();
	}

}

(new FinderTest())->run();

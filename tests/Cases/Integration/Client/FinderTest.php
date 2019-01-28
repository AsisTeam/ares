<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Tests\Cases\Integration\Client;

use AsisTeam\ARES\Client\Finder;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class FinderTest extends TestCase
{

	/** @var Finder */
	private $finder;

	public function setUp(): void
	{
		Environment::skip('this test should be run manually');

		$this->finder = new Finder();
	}

	public function testFindById(): void
	{
		$subject = $this->finder->findById($ic = '28319061');

		Assert::equal($ic, $subject->getCompanyId());
		Assert::equal('28319061', $subject->getVatId());
		Assert::equal('AsisTeam s.r.o.', $subject->getName());
		Assert::equal('Praha 1, Staré Město, Kaprova 42/14', $subject->getAddress());
	}

	public function testFindByIdNoMatch(): void
	{
		Assert::null($this->finder->findById($ic = '98765432'));
	}

	public function testFindByName(): void
	{
		$subjects = $this->finder->findByName('Asis');
		Assert::count(5, $subjects);
	}

	public function testFindByNamePerson(): void
	{
		$subjects = $this->finder->findByName('Tomáš Holan');
		Assert::count(12, $subjects);
	}

}

(new FinderTest())->run();

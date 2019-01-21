<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Tests\Cases\Unit\Entity;

use AsisTeam\ARES\Entity\Subject;
use AsisTeam\ARES\Exception\InvalidArgumentException;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class SubjectTest extends TestCase
{

	public function testAssertCompanyId(): void
	{
		Subject::assertCompanyId('76641040'); // valid

		try {
			Subject::assertCompanyId('766410');
		} catch (InvalidArgumentException $e) {
			Assert::equal('CompanyID (IC) must contain 8 digits. "766410" given', $e->getMessage());
		}

		try {
			Subject::assertCompanyId('nesmysly');
		} catch (InvalidArgumentException $e) {
			Assert::equal('CompanyID (IC) must be numeric. "nesmysly" given', $e->getMessage());
		}
	}

}

(new SubjectTest())->run();

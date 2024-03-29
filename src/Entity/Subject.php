<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Entity;

use AsisTeam\ARES\Exception\InvalidArgumentException;

final class Subject
{

	/** @var string */
	private $name;

	/** @var string */
	private $companyId;

	/** @var string */
	private $address;

	/** @var int */
	private $legalForm;

	/** @var string|null */
	private $vatId;

	public function __construct(string $name, string $companyId, string $address, int $legalForm, ?string $vatId = null)
	{
		$this->name      = $name;
		$this->companyId = $companyId;
		$this->address   = $address;
		$this->legalForm = $legalForm;
		$this->vatId     = $vatId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getCompanyId(): string
	{
		return $this->companyId;
	}

	public function getAddress(): string
	{
		return $this->address;
	}

	public function getLegalForm(): int
	{
		return $this->legalForm;
	}

	public function getVatId(): ?string
	{
		return $this->vatId;
	}

	public function getLegalFormName(): string
	{
		return LegalForm::getLegalFormName($this->legalForm);
	}

	public function isPhysicalPerson(): bool
	{
		return LegalForm::isPhysicalPerson($this->legalForm);
	}

	public function isLegalPerson(): bool
	{
		return LegalForm::isLegalPerson($this->legalForm);
	}

	public static function assertCompanyId(string $companyId): void
	{
		if (strlen($companyId) !== 8) {
			throw new InvalidArgumentException(sprintf('CompanyID (IC) must contain 8 digits. "%s" given', $companyId));
		}

		if (!is_numeric($companyId)) {
			throw new InvalidArgumentException(sprintf('CompanyID (IC) must be numeric. "%s" given', $companyId));
		}
	}

	public static function assertName(string $name): void
	{
		if (strlen($name) < 3) {
			throw new InvalidArgumentException(sprintf(
				'Company name must be at least 3 characters long. "%s" given',
				$name
			));
		}
	}

}

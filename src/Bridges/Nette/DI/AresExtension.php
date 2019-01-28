<?php declare(strict_types = 1);

namespace AsisTeam\ARES\Bridges\Nette\DI;

use AsisTeam\ARES\Client\Finder;
use AsisTeam\ARES\Client\Requester;
use Nette\DI\CompilerExtension;

class AresExtension extends CompilerExtension
{

	/** @var int[] */
	public $defaults = [
		'timeout' => 10, // max request duration in seconds
	];

	/**
	 * @inheritDoc
	 */
	public function loadConfiguration(): void
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('requester'))
			->setFactory(Requester::class, [$config['timeout']]);

		$builder->addDefinition($this->prefix('finder'))
			->setFactory(Finder::class);
	}

}

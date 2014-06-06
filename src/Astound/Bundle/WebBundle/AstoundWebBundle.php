<?php

namespace Astound\Bundle\WebBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Astound Override of Sylius Bundle AddressingBundle
 */

class AstoundWebBundle extends Bundle
{
	public function getParent()
	{
		return 'SyliusWebBundle';
	}

}
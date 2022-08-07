<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\RockSolidMegaMenu;

use MadeYourDay\RockSolidMegaMenu\DependencyInjection\RockSolidMegaMenuExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Configures the RockSolid Mega Menu bundle.
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
class RockSolidMegaMenuBundle extends Bundle
{
	/**
	 * {@inheritdoc}
	 */
	public function getContainerExtension()
	{
		return new RockSolidMegaMenuExtension();
	}
}

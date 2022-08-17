<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\RockSolidMegaMenu\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * RockSolid Mega Menu bundle extension.
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
class RockSolidMegaMenuExtension extends Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getAlias(): string
	{
		return 'rocksolid_mega_menu';
	}

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../Resources/config')
		);

		$loader->load('services.yaml');
	}
}

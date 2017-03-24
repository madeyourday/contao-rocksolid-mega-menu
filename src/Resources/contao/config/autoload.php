<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * RockSolid Mega Menu autload configuration
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */

ClassLoader::addClasses(array(
	'MadeYourDay\\RockSolidMegaMenu\\MegaMenu' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/MegaMenu.php',
	'MadeYourDay\\RockSolidMegaMenu\\Runonce' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Runonce.php',
	'MadeYourDay\\RockSolidMegaMenu\\Model\\MenuModel' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Model/MenuModel.php',
	'MadeYourDay\\RockSolidMegaMenu\\Model\\MenuColumnModel' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Model/MenuColumnModel.php',
	'MadeYourDay\\RockSolidMegaMenu\\Module\\Menu' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Module/Menu.php',
	'MadeYourDay\\RockSolidMegaMenu\\Module\\MenuCustom' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Module/MenuCustom.php',
));

$templatesFolder = 'vendor/madeyourday/contao-rocksolid-mega-menu/templates';

TemplateLoader::addFiles(array(
	'rsmm_default' => $templatesFolder,
	'nav_rsmm' => $templatesFolder,
));

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
	'MadeYourDay\\Contao\\MegaMenu\\MegaMenu' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/MegaMenu.php',
	'MadeYourDay\\Contao\\MegaMenu\\Model\\MenuModel' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Model/MenuModel.php',
	'MadeYourDay\\Contao\\MegaMenu\\Model\\MenuColumnModel' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Model/MenuColumnModel.php',
	'MadeYourDay\\Contao\\MegaMenu\\Module\\Menu' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Module/Menu.php',
	'MadeYourDay\\Contao\\MegaMenu\\Module\\MenuCustom' => 'system/modules/rocksolid-mega-menu/src/MadeYourDay/Contao/MegaMenu/Module/MenuCustom.php',
));

TemplateLoader::addFiles(array(
	'rsmm_default' => 'system/modules/rocksolid-mega-menu/templates',
	'nav_rsmm' => 'system/modules/rocksolid-mega-menu/templates',
));

<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * RockSolid Mega Menu back end modules configuration
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */

$GLOBALS['TL_MODELS']['tl_rocksolid_mega_menu'] = 'MadeYourDay\\Contao\\MegaMenu\\Model\\MenuModel';
$GLOBALS['TL_MODELS']['tl_rocksolid_mega_menu_column'] = 'MadeYourDay\\Contao\\MegaMenu\\Model\\MenuColumnModel';

array_insert($GLOBALS['BE_MOD']['design'], 2, array(
	'rocksolid_mega_menu' => array(
		'tables' => array(
			'tl_rocksolid_mega_menu',
			'tl_rocksolid_mega_menu_column',
		),
		'icon' => 'system/modules/rocksolid-mega-menu/assets/img/icon.png',
	),
));

array_insert($GLOBALS['FE_MOD'], 2, array(
	'navigationMenu' => array(
		'rocksolid_mega_menu' => 'MadeYourDay\\Contao\\MegaMenu\\Module\\Menu',
		'rocksolid_mega_menu_custom' => 'MadeYourDay\\Contao\\MegaMenu\\Module\\MenuCustom',
	),
));

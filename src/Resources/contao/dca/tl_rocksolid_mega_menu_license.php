<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * RockSolid Mega Menu license DCA
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
$GLOBALS['TL_DCA']['tl_rocksolid_mega_menu_license'] = array(

	'config' => array(
		'dataContainer' => 'File',
		'closed' => true,
		'onsubmit_callback' => array(
			function() {
				\Controller::redirect('contao/main.php?do=rocksolid_mega_menu' . (defined('TL_REFERER_ID') ? '&ref=' . TL_REFERER_ID : ''));
			},
		),
	),

	'palettes' => array(
		'default' => '{license_legend},rocksolid_mega_menu_license',
	),

	'fields' => array(
		'rocksolid_mega_menu_license' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_license']['rocksolid_mega_menu_license'],
			'inputType' => 'text',
			'eval' => array(
				'mandatory' => true,
				'tl_class' => 'w50',
			),
			'save_callback' => array(
				array('MadeYourDay\\RockSolidMegaMenu\\MegaMenu', 'licenseSaveCallback'),
			),
		),
	),

);

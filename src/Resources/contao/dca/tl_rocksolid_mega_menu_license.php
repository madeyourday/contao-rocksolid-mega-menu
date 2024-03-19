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

use Contao\Controller;
use Contao\DC_File;
use Contao\System;

$GLOBALS['TL_DCA']['tl_rocksolid_mega_menu_license'] = array(

	'config' => array(
		'dataContainer' => DC_File::class,
		'closed' => true,
		'onsubmit_callback' => array(
			function() {
				Controller::redirect(System::getContainer()->get('router')->generate('contao_backend', ['do' => 'rocksolid_mega_menu', 'ref' => System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id')]));
			},
		),
	),

	'list' => array(
		'sorting' => array(
			'mode' => 5,
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

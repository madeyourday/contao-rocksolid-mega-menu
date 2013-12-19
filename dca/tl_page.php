<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Mega Menu Page DCA
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace(';{layout_legend', ';{rocksolid_mega_menu_legend:hide},rsmm_subtitle,rsmm_color,rsmm_image,rsmm_enabled;{layout_legend', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['forward'] = str_replace(';{layout_legend', ';{rocksolid_mega_menu_legend:hide},rsmm_subtitle,rsmm_color,rsmm_image,rsmm_enabled;{layout_legend', $GLOBALS['TL_DCA']['tl_page']['palettes']['forward']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['redirect'] = str_replace(';{layout_legend', ';{rocksolid_mega_menu_legend:hide},rsmm_subtitle,rsmm_color,rsmm_image,rsmm_enabled;{layout_legend', $GLOBALS['TL_DCA']['tl_page']['palettes']['redirect']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'rsmm_enabled';
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['rsmm_enabled'] = 'rsmm_id';

$GLOBALS['TL_DCA']['tl_page']['fields']['rsmm_subtitle'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_page']['rsmm_subtitle'],
	'exclude' => true,
	'inputType' => 'text',
	'eval' => array(
		'tl_class' => 'w50',
	),
	'sql' => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_page']['fields']['rsmm_color'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_page']['rsmm_color'],
	'inputType' => 'text',
	'eval' => array(
		'maxlength' => 6,
		'multiple' => true,
		'size' => 2,
		'colorpicker' => true,
		'isHexColor' => true,
		'decodeEntities' => true,
		'tl_class' => 'w50 wizard',
	),
	'sql' => "varchar(64) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_page']['fields']['rsmm_image'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_page']['rsmm_image'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array(
		'fieldType' => 'radio',
		'filesOnly' => true,
		'extensions' => 'jpg,jpeg,png,gif,svg',
		'tl_class' => 'clr',
	),
	'sql' => "binary(16) NULL",
);
$GLOBALS['TL_DCA']['tl_page']['fields']['rsmm_enabled'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_page']['rsmm_enabled'],
	'exclude' => true,
	'inputType' => 'checkbox',
	'eval' => array(
		'submitOnChange' => true,
		'tl_class' => 'm12'
	),
	'sql' => "char(1) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_page']['fields']['rsmm_id'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_page']['rsmm_id'],
	'exclude' => true,
	'inputType' => 'select',
	'options_callback' => array('MadeYourDay\\Contao\\MegaMenu\\MegaMenu', 'getMenuIds'),
	'eval' => array(
		'mandatory' => true,
	),
	'sql' => "int(10) unsigned NOT NULL default '0'",
);

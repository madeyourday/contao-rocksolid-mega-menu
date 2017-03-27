<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * RockSolid Mega Menu DCA
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */

if (TL_MODE === 'BE') {

	$GLOBALS['TL_CSS'][] = 'bundles/rocksolidcolumns/css/be_main.css';

	// Load module language file
	$this->loadLanguageFile('tl_module');

}

$GLOBALS['TL_DCA']['tl_rocksolid_mega_menu'] = array(

	'config' => array(
		'dataContainer' => 'Table',
		'ctable' => array('tl_rocksolid_mega_menu_column'),
		'switchToEdit' => true,
		'enableVersioning' => true,
		'sql' => array(
			'keys' => array(
				'id' => 'primary',
			),
		),
		'onload_callback' => array(
			array('MadeYourDay\\RockSolidMegaMenu\\MegaMenu', 'dcaOnloadCallback'),
		),
	),

	'list' => array(
		'sorting' => array(
			'mode' => 1,
			'fields' => array('name'),
			'flag' => 1,
			'panelLayout' => 'filter;search,limit',
		),
		'label' => array(
			'fields' => array('name'),
			'format' => '%s',
		),
		'global_operations' => array(
			'license' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['editLicense'],
				'href' => 'table=tl_rocksolid_mega_menu_license',
				'class' => 'header_icon',
				'icon' => 'system/themes/' . \Backend::getTheme() . '/images/settings.gif',
			),
			'all' => array(
				'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href' => 'act=select',
				'class' => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
			),
		),
		'operations' => array(
			'edit' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['edit'],
				'href' => 'table=tl_rocksolid_mega_menu_column',
				'icon' => 'edit.gif',
				'attributes' => 'class="contextmenu"',
			),
			'editheader' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['editheader'],
				'href' => 'act=edit',
				'icon' => 'header.gif',
				'attributes' => 'class="edit-header"',
			),
			'copy' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['copy'],
				'href' => 'act=copy',
				'icon' => 'copy.gif',
			),
			'delete' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['delete'],
				'href' => 'act=delete',
				'icon' => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'show' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['show'],
				'href' => 'act=show',
				'icon' => 'show.gif',
			),
		),
	),

	'palettes' => array(
		'__selector__' => array('type'),
		'default' => '{type_legend},name,type',
		'auto' => '{type_legend},name,type;{rs_columns_legend},rs_columns_large,rs_columns_medium,rs_columns_small;{slider_legend},slider,sliderNavType,sliderControls,sliderSkin,sliderGapSize,sliderMaxCount,sliderMinSize,sliderPrevNextSteps,sliderLoop;{settings_legend},imageSize;{background_legend},backgroundImage,backgroundImageSize,backgroundSize,backgroundPosition,backgroundRepeat;{expert_legend},cssClass,cssId',
		'auto_images' => '{type_legend},name,type;{rs_columns_legend},rs_columns_large,rs_columns_medium,rs_columns_small;{slider_legend},slider,sliderNavType,sliderControls,sliderSkin,sliderGapSize,sliderMaxCount,sliderMinSize,sliderPrevNextSteps,sliderLoop;{settings_legend},imageSize;{background_legend},backgroundImage,backgroundImageSize,backgroundSize,backgroundPosition,backgroundRepeat;{expert_legend},cssClass,cssId',
		'manual' => '{type_legend},name,type;{rs_columns_legend},rs_columns_large,rs_columns_medium,rs_columns_small;{slider_legend},slider,sliderNavType,sliderControls,sliderSkin,sliderGapSize,sliderMaxCount,sliderMinSize,sliderPrevNextSteps,sliderLoop;{background_legend},backgroundImage,backgroundImageSize,backgroundSize,backgroundPosition,backgroundRepeat;{expert_legend},cssClass,cssId',
		'html' => '{type_legend},name,type;{html_legend},html;{background_legend},backgroundImage,backgroundImageSize,backgroundSize,backgroundPosition,backgroundRepeat;{expert_legend},cssClass,cssId',
	),

	'fields' => array(
		'id' => array(
			'sql' => "int(10) unsigned NOT NULL auto_increment",
		),
		'tstamp' => array(
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'name' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['name'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array(
				'mandatory' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'type' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['type'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(
				'auto',
				'auto_images',
				'manual',
				'html',
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['types'],
			'eval' => array(
				'mandatory' => true,
				'chosen' => true,
				'submitOnChange' => true,
				'includeBlankOption' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(32) NOT NULL default ''",
		),
		'rs_columns_large' => array(
			'inputType' => 'text',
			'exclude' => true,
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['rs_columns_large'],
			'eval' => array(
				'mandatory' => true,
				'tl_class' => 'rs_columns_w33',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'rs_columns_medium' => array(
			'inputType' => 'text',
			'exclude' => true,
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['rs_columns_medium'],
			'eval' => array(
				'tl_class' => 'rs_columns_w33',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'rs_columns_small' => array(
			'inputType' => 'text',
			'exclude' => true,
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['rs_columns_small'],
			'eval' => array(
				'tl_class' => 'rs_columns_w33',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'slider' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['slider'],
			'exclude' => true,
			'inputType' => 'checkbox',
			'eval' => array(
				'tl_class' => 'clr',
			),
			'sql' => "char(1) NOT NULL default ''",
		),
		'sliderNavType' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_module']['rsts_navType'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(
				'bullets' => $GLOBALS['TL_LANG']['tl_module']['rsts_navType_bullets'],
				'numbers' => $GLOBALS['TL_LANG']['tl_module']['rsts_navType_numbers'],
				'tabs' => $GLOBALS['TL_LANG']['tl_module']['rsts_navType_tabs'],
				'none' => $GLOBALS['TL_LANG']['tl_module']['rsts_navType_none'],
			),
			'eval' => array('tl_class' => 'w50'),
			'sql' => "varchar(64) NOT NULL default ''",
		),
		'sliderControls' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_module']['rsts_controls'],
			'exclude' => true,
			'inputType' => 'checkbox',
			'eval' => array('tl_class' => 'w50 m12'),
			'sql' => "char(1) NOT NULL default '1'",
		),
		'sliderSkin' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_module']['rsts_customSkin'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array('tl_class' => 'w50'),
			'sql' => "varchar(64) NOT NULL default ''",
		),
		'sliderGapSize' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_module']['rsts_gapSize'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array('tl_class' => 'w50'),
			'sql' => "varchar(64) NOT NULL default '0%'",
		),
		'sliderMaxCount' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_module']['rsts_slideMaxCount'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20),
			'eval' => array('tl_class' => 'w50', 'includeBlankOption' => true),
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'sliderMinSize' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_module']['rsts_slideMinSize'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array('tl_class' => 'w50'),
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'sliderPrevNextSteps' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_module']['rsts_prevNextSteps'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20),
			'eval' => array('tl_class' => 'w50', 'includeBlankOption' => true),
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'sliderLoop' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_module']['rsts_loop'],
			'exclude' => true,
			'inputType' => 'checkbox',
			'eval' => array('tl_class' => 'w50 m12'),
			'sql' => "char(1) NOT NULL default ''",
		),
		'imageSize' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['imageSize'],
			'exclude' => true,
			'inputType' => 'imageSize',
			'options_callback' => function () {
				return System::getContainer()
					->get('contao.image.image_sizes')
					->getOptionsForUser(BackendUser::getInstance());
			},
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval' => array(
				'rgxp' => 'digit',
				'nospace' => true,
				'helpwizard' => true,
				'includeBlankOption' => true,
			),
			'sql' => "varchar(64) NOT NULL default ''",
		),
		'html' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['html'],
			'exclude' => true,
			'inputType' => 'textarea',
			'eval' => array(
				'mandatory' => true,
				'allowHtml' => true,
				'class' => 'monospace',
				'rte' => 'ace|html',
			),
			'explanation' => 'insertTags',
			'sql' => "mediumtext NULL",
		),
		'backgroundImage' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['backgroundImage'],
			'exclude' => true,
			'inputType' => 'fileTree',
			'eval' => array(
				'fieldType' => 'radio',
				'files' => true,
				'filesOnly' => true,
				'tl_class' => 'clr',
				'extensions' => \Config::get('validImageTypes'),
			),
			'sql' => "binary(16) NULL",
		),
		'backgroundImageSize' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['backgroundImageSize'],
			'exclude' => true,
			'inputType' => 'imageSize',
			'options_callback' => function () {
				return System::getContainer()
					->get('contao.image.image_sizes')
					->getOptionsForUser(BackendUser::getInstance());
			},
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval' => array(
				'rgxp' => 'digit',
				'nospace' => true,
				'helpwizard' => true,
				'tl_class' => 'w50',
				'includeBlankOption' => true,
			),
			'sql' => "varchar(64) NOT NULL default ''",
		),
		'backgroundSize' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['backgroundSize'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(
				'auto',
				'cover',
				'contain',
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['backgroundSizes'],
			'eval' => array(
				'includeBlankOption' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(64) NOT NULL default ''",
		),
		'backgroundPosition' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['backgroundPosition'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(
				'left top',
				'left center',
				'left bottom',
				'center top',
				'center center',
				'center bottom',
				'right top',
				'right center',
				'right bottom',
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['backgroundPositions'],
			'eval' => array(
				'includeBlankOption' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(64) NOT NULL default ''",
		),
		'backgroundRepeat' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['backgroundRepeat'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(
				'no-repeat',
				'repeat-x',
				'repeat-y',
				'repeat',
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['backgroundRepeats'],
			'eval' => array(
				'includeBlankOption' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(64) NOT NULL default ''",
		),
		'cssClass' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['cssClass'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array(
				'tl_class' => 'w50 clr',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'cssId' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu']['cssId'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array(
				'tl_class' => 'w50',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
	),

);

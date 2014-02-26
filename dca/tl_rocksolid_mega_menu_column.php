<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * RockSolid Mega Menu column DCA
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
$GLOBALS['TL_DCA']['tl_rocksolid_mega_menu_column'] = array(

	'config' => array(
		'dataContainer' => 'Table',
		'ptable' => 'tl_rocksolid_mega_menu',
		'enableVersioning' => true,
		'sql' => array(
			'keys' => array(
				'id' => 'primary',
				'pid' => 'index',
			)
		),
	),

	'list' => array(
		'sorting' => array(
			'mode' => 4,
			'fields' => array('sorting'),
			'headerFields' => array('name', 'type', 'columnCount'),
			'header_callback' => array('MadeYourDay\\Contao\\MegaMenu\\MegaMenu', 'headerCallback'),
			'panelLayout' => 'limit',
			'child_record_callback' => array('MadeYourDay\\Contao\\MegaMenu\\MegaMenu', 'listColumns'),
			'child_record_class' => 'no_padding',
		),
		'global_operations' => array(
			'all' => array(
				'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href' => 'act=select',
				'class' => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
			)
		),
		'operations' => array(
			'edit' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['edit'],
				'href' => 'act=edit',
				'icon' => 'edit.gif',
			),
			'copy' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['copy'],
				'href' => 'act=paste&amp;mode=copy',
				'icon' => 'copy.gif',
			),
			'cut' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['cut'],
				'href' => 'act=paste&amp;mode=cut',
				'icon' => 'cut.gif',
			),
			'delete' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['delete'],
				'href' => 'act=delete',
				'icon' => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'toggle' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['toggle'],
				'icon' => 'visible.gif',
				'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback' => array('MadeYourDay\\Contao\\MegaMenu\\MegaMenu', 'toggleColumnIcon'),
			),
			'show' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['show'],
				'href' => 'act=show',
				'icon' => 'show.gif',
			)
		)
	),

	'palettes' => array(
		'__selector__' => array('type'),
		'default' => '{type_legend},name,type',
		'auto' => '{type_legend},name,type,displayName;{settings_legend},image,imageSize,text;{navigation_legend},page;{expert_legend},cssClass,cssId;{publish_legend},published,start,stop',
		'auto_image' => '{type_legend},name,type,displayName;{settings_legend},image,imageSize,text;{navigation_legend},page;{expert_legend},cssClass,cssId;{publish_legend},published,start,stop',
		'manual' => '{type_legend},name,type,displayName;{settings_legend},image,imageSize,text;{navigation_legend},page,pages;{expert_legend},cssClass,cssId;{publish_legend},published,start,stop',
		'manual_image' => '{type_legend},name,type,displayName;{settings_legend},image,imageSize,text;{navigation_legend},page,pages;{expert_legend},cssClass,cssId;{publish_legend},published,start,stop',
		'image' => '{type_legend},name,type,displayName;{settings_legend},image,imageSize,text;{navigation_legend},page;{expert_legend},cssClass,cssId;{publish_legend},published,start,stop',
		'link' => '{type_legend},name,type;{navigation_legend},page;{expert_legend},cssClass,cssId;{publish_legend},published,start,stop',
		'html' => '{type_legend},name,type,displayName;{settings_legend},html;{expert_legend},cssClass,cssId;{publish_legend},published,start,stop',
	),

	'fields' => array(
		'id' => array(
			'sql' => "int(10) unsigned NOT NULL auto_increment",
		),
		'pid' => array(
			'foreignKey' => 'tl_rocksolid_mega_menu.name',
			'sql' => "int(10) unsigned NOT NULL default '0'",
			'relation' => array('type' => 'belongsTo', 'load' => 'eager'),
		),
		'tstamp' => array(
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'sorting' => array(
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'name' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['name'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array(
				'mandatory' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'type' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['type'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(
				'auto',
				'auto_image',
				'manual',
				'manual_image',
				'image',
				'link',
				'html',
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['types'],
			'eval' => array(
				'mandatory' => true,
				'chosen' => true,
				'submitOnChange' => true,
				'includeBlankOption' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(32) NOT NULL default ''",
		),
		'displayName' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['displayName'],
			'exclude' => true,
			'inputType' => 'checkbox',
			'eval' => array(
				'tl_class' => 'clr',
			),
			'sql' => "char(1) NOT NULL default ''",
		),
		'image' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['image'],
			'exclude' => true,
			'inputType' => 'fileTree',
			'eval' => array(
				'fieldType' => 'radio',
				'files' => true,
				'filesOnly' => true,
				'tl_class' => 'clr',
			),
			'sql' => version_compare(VERSION, '3.2', '<') ? "varchar(255) NOT NULL default ''" : "binary(16) NULL",
		),
		'imageSize' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['imageSize'],
			'exclude' => true,
			'inputType' => 'imageSize',
			'options' => $GLOBALS['TL_CROP'],
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval' => array(
				'rgxp' => 'digit',
				'nospace' => true,
				'helpwizard' => true,
			),
			'sql' => "varchar(64) NOT NULL default ''",
		),
		'text' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['text'],
			'exclude' => true,
			'inputType' => 'textarea',
			'eval' => array(
				'rte' => 'tinyMCE',
				'helpwizard' => true,
			),
			'explanation' => 'insertTags',
			'sql' => "mediumtext NULL",
		),
		'page' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['page'],
			'exclude' => true,
			'inputType' => 'pageTree',
			'foreignKey' => 'tl_page.title',
			'eval' => array(
				'fieldType' => 'radio',
			),
			'sql' => "int(10) unsigned NOT NULL default '0'",
			'relation' => array(
				'type' => 'hasOne',
				'load' => 'eager',
			),
		),
		'pages' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['pages'],
			'exclude' => true,
			'inputType' => 'pageTree',
			'foreignKey' => 'tl_page.title',
			'eval' => array(
				'multiple' => true,
				'fieldType' => 'checkbox',
				'files' => true,
				'orderField' => 'orderPages',
				'mandatory' => true,
			),
			'sql' => "blob NULL",
			'relation' => array(
				'type' => 'hasMany',
				'load' => 'lazy',
			),
		),
		'orderPages' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['orderPages'],
			'sql' => "blob NULL",
		),
		'html' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['html'],
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
		'cssClass' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['cssClass'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array(
				'tl_class' => 'w50 clr',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'cssId' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['cssId'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array(
				'tl_class' => 'w50',
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'published' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['published'],
			'exclude' => true,
			'filter' => true,
			'flag' => 1,
			'inputType' => 'checkbox',
			'eval' => array('doNotCopy'=>true),
			'sql' => "char(1) NOT NULL default ''",
		),
		'start' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['start'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql' => "varchar(10) NOT NULL default ''",
		),
		'stop' => array(
			'label' => &$GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_column']['stop'],
			'exclude' => true,
			'inputType' => 'text',
			'eval' => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql' => "varchar(10) NOT NULL default ''",
		)
	),

);

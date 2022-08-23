<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\RockSolidMegaMenu\Module;

use Contao\File;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\FrontendUser;
use Contao\Input;
use Contao\ModuleNavigation;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use MadeYourDay\RockSolidMegaMenu\Model\MenuModel;
use MadeYourDay\RockSolidMegaMenu\Model\MenuColumnModel;
use MadeYourDay\RockSolidColumns\Element\ColumnsStart;

/**
 * Menu Frontend Module
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
class Menu extends ModuleNavigation
{
	protected function renderNavigation($pid, $level = 1, $host = null, $language = null)
	{
		$parentPage = PageModel::findByPk($pid);
		if (!$parentPage->rsmm_enabled || !$parentPage->rsmm_id) {
			return parent::renderNavigation($pid, $level, $host, $language);
		}

		$menu = MenuModel::findByPk($parentPage->rsmm_id);
		if (!$menu || !$menu->id) {
			return '';
		}

		$template = new FrontendTemplate($this->rsmm_template);

		$template->id = $menu->id;
		$template->type = $menu->type;
		$template->cssClass = $menu->cssClass;
		$template->cssId = $menu->cssId;
		$template->level = 'level_' . ($level + 1);
		$template->html = $menu->html;
		$template->backgroundImage = $this->getImageObject($menu->backgroundImage, $menu->backgroundImageSize);
		$template->backgroundStyle = '';
		if ($template->backgroundImage) {
			$template->backgroundStyle .= 'background-image: url(&quot;' . $template->backgroundImage->src . '&quot;);';
		}
		if ($menu->backgroundSize) {
			$template->backgroundStyle .= 'background-size: ' . $menu->backgroundSize . ';';
		}
		if ($menu->backgroundPosition) {
			$template->backgroundStyle .= 'background-position: ' . $menu->backgroundPosition . ';';
		}
		if ($menu->backgroundRepeat) {
			$template->backgroundStyle .= 'background-repeat: ' . $menu->backgroundRepeat . ';';
		}

		$sliderAssetsDir = 'bundles/rocksolidslider';

		if ($menu->slider && file_exists(System::getContainer()->getParameter('contao.web_dir') . '/' . $sliderAssetsDir . '/js/rocksolid-slider.min.js')) {
			$template->slider = true;
			$options = array(
				'navType' => $menu->sliderNavType,
				'controls' => $menu->sliderControls,
				'gapSize' => $menu->sliderGapSize,
				'skin' => $menu->sliderSkin ?: 'mega-dropdown',
				'loop' => (bool)$menu->sliderLoop,
				'keyboard' => false,
			);
			if ($menu->sliderMaxCount) {
				$options['slideMaxCount'] = (int)$menu->sliderMaxCount;
			}
			if ($menu->sliderMinSize) {
				$options['slideMinSize'] = (int)$menu->sliderMinSize;
			}
			if ($menu->sliderPrevNextSteps) {
				$options['prevNextSteps'] = (int)$menu->sliderPrevNextSteps;
			}
			$template->sliderOptions = $options;
			$GLOBALS['TL_JAVASCRIPT'][] = $sliderAssetsDir . '/js/rocksolid-slider.min.js|static';
			$GLOBALS['TL_CSS'][] = $sliderAssetsDir . '/css/rocksolid-slider.min.css||static';
			$template->getColumnClassName = function () {
				return '';
			};
		}
		else {
			$columnsConfig = ColumnsStart::getColumnsConfiguration($menu->row());
			$template->getColumnClassName = function ($index) use($columnsConfig) {
				$classes = array('rs-column');
				foreach ($columnsConfig as $name => $media) {
					$classes = array_merge($classes, $media[$index % count($media)]);
					if ($index < count($media)) {
						$classes[] = '-' . $name . '-first-row';
					}
				}
				return implode(' ', $classes);
			};
		}

		if ($menu->type === 'manual') {

			$menuColumns = MenuColumnModel::findPublishedByPid($menu->id);
			if (!$menuColumns) {
				return '';
			}

			$columns = array();
			while ($menuColumns->next()) {

				$column = $menuColumns->row();

				if ($column['page']) {
					$pageResult = PageModel::findPublishedById($column['page']);
					if ($pageResult) {
						$column['page'] = $this->getPageData($pageResult, $column['imageSize']);
					}
					else {
						$column['page'] = null;
					}
				}

				$column['image'] = $this->getImageObject($column['image'], $column['imageSize']);

				if ($column['type'] === 'manual' || $column['type'] === 'manual_image') {
					$column['pages'] = $this->buildPagesArray($column['pages'], $column['imageSize'], true, 1);
				}
				else if ($column['type'] === 'auto' || $column['type'] === 'auto_image') {
					$column['pages'] = $this->buildPagesArray($column['page']['id'] ?? $column['page'], $column['imageSize'], false, $column['stopLevel'] ?: 0);
				}
				else {
					$column['pages'] = array();
				}

				$columns[] = $column;

			}

			$template->columns = $columns;

		}
		else if ($menu->type !== 'html') {
			$template->pages = $this->buildPagesArray($pid, $menu->imageSize, false, $menu->stopLevel);
		}

		return $template->parse();
	}

	protected function buildPagesArray($pid, $imageSize, $customNav = false, $stopLevel = 0)
	{
		$pages = array();

		if ($customNav) {
			$pagesResult = PageModel::findPublishedRegularByIds(StringUtil::deserialize($pid, true));
			if ($pagesResult) {
				$pagesResult = array_map(
					static function ($page): array
					{
						return array(
							'page' => $page,
							'hasSubpages' => false,
						);
					},
					$pagesResult->getModels()
				);
			}
		}
		else {
			$pagesResult = static::getPublishedSubpagesByPid((int) $pid, $this->showHidden);
		}
		if (!$pagesResult) {
			return array();
		}

		$userGroups = System::getContainer()->get('contao.security.token_checker')->hasFrontendUser()
			? FrontendUser::getInstance()->groups
			: array('-1');

		foreach ($pagesResult as ['page' => $subpage, 'hasSubpages' => $hasSubpages]) {

			$pageGroups = StringUtil::deserialize($subpage->groups);

			if (
				$subpage->protected
				&& !System::getContainer()->get('contao.security.token_checker')->isPreviewMode()
				&& (
					!is_array($pageGroups)
					|| !count(array_intersect($pageGroups, $userGroups))
				)
				&& !$this->showProtected
			) {
				continue;
			}

			$page = $this->getPageData($subpage, $imageSize);

			if ($hasSubpages && (!$stopLevel || $stopLevel > 1)) {
				$page['pages'] = $this->buildPagesArray($page['id'], $imageSize, false, $stopLevel ? $stopLevel - 1 : 0);
			}
			else {
				$page['pages'] = array();
			}

			$pages[$page['id']] = $page;

		}

		return array_values(array_filter($pages));
	}

	protected function getPageData($pagesResult, $imageSize = null)
	{
		$href = null;

		if ($pagesResult->type === 'redirect') {
			$href = $pagesResult->url;
			if (strncasecmp($href, 'mailto:', 7) === 0) {
				$href = StringUtil::encodeEmail($href);
			}
		}

		else if ($pagesResult->type === 'forward') {

			if ($pagesResult->jumpTo) {
				$targetPage = $pagesResult->getRelated('jumpTo');
			}
			else {
				$targetPage = PageModel::findFirstPublishedRegularByPid($pagesResult->id);
			}

			if ($targetPage !== null) {
				$href = $targetPage->getFrontendUrl();
			}

		}
		if (!$href) {
			$href = $pagesResult->current()->getFrontendUrl();
		}

		if (
			($GLOBALS['objPage']->id == $pagesResult->id || $pagesResult->type == 'forward' && $GLOBALS['objPage']->id == $pagesResult->jumpTo)
			&& !Input::get('articles')
		) {
			$cssClass = (($pagesResult->type == 'forward' && $GLOBALS['objPage']->id == $pagesResult->jumpTo) ? 'forward' . (in_array($pagesResult->id, $GLOBALS['objPage']->trail) ? ' trail' : '') : 'active') . ($pagesResult->protected ? ' protected' : '') . (($pagesResult->cssClass != '') ? ' ' . $pagesResult->cssClass : '');
			$page['isActive'] = true;
		}
		else {
			$cssClass = ($pagesResult->protected ? ' protected' : '') . (in_array($pagesResult->id, $GLOBALS['objPage']->trail) ? ' trail' : '') . (($pagesResult->cssClass != '') ? ' ' . $pagesResult->cssClass : '');
			if ($pagesResult->pid == $GLOBALS['objPage']->pid) {
				$cssClass .= ' sibling';
			}
			$page['isActive'] = false;
		}

		$page = $pagesResult->row();

		$page['class'] = trim($cssClass);
		$page['title'] = StringUtil::specialchars($pagesResult->title, true);
		$page['pageTitle'] = StringUtil::specialchars($pagesResult->pageTitle, true);
		$page['link'] = $pagesResult->title;
		$page['href'] = $href ?: './';
		$page['nofollow'] = (strncmp($pagesResult->robots, 'noindex', 7) === 0);
		$page['target'] = '';
		$page['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $pagesResult->description);
		$page['rsmm_image'] = $this->getImageObject($page['rsmm_image'], $imageSize);

		// Override the link target
		if ($pagesResult->type == 'redirect' && $pagesResult->target)
		{
			$page['target'] = ' target="_blank"';
		}

		return $page;
	}

	protected function getImageObject($id, $size = null)
	{
		if (!trim($id)) {
			return null;
		}

		$figure = System::getContainer()
			->get('contao.image.studio')
			->createFigureBuilder()
			->from($id)
			->setSize($size)
			->buildIfResourceExists();

		if (null === $figure) {
			return null;
		}

		return (object) $figure->getLegacyTemplateData();
	}
}

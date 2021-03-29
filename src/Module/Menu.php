<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\RockSolidMegaMenu\Module;

use PageModel;
use FrontendTemplate;
use MadeYourDay\RockSolidMegaMenu\Model\MenuModel;
use MadeYourDay\RockSolidMegaMenu\Model\MenuColumnModel;
use MadeYourDay\RockSolidColumns\Element\ColumnsStart;

/**
 * Menu Frontend Module
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
class Menu extends \ModuleNavigation
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

		if ($menu->slider && file_exists(\System::getContainer()->getParameter('contao.web_dir') . '/' . $sliderAssetsDir . '/js/rocksolid-slider.min.js')) {
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
					$column['pages'] = $this->buildPagesArray($column['pages'], $column['imageSize'], $column['orderPages']);
				}
				else if ($column['type'] === 'auto' || $column['type'] === 'auto_image') {
					$column['pages'] = $this->buildPagesArray($column['page'], $column['imageSize'], null, $column['stopLevel'] ?: 0);
				}
				else {
					$column['pages'] = array();
				}

				$columns[] = $column;

			}

			$template->columns = $columns;

		}
		else if ($menu->type !== 'html') {
			$template->pages = $this->buildPagesArray($pid, $menu->imageSize, null, $menu->stopLevel);
		}

		return $template->parse();
	}

	protected function buildPagesArray($pid, $imageSize, $orderPages = null, $stopLevel = 0)
	{
		$pages = array();

		if ($orderPages !== null) {

			$pagesResult = PageModel::findPublishedRegularWithoutGuestsByIds(\StringUtil::deserialize($pid, true));

			if ($orderPages != '') {
				$orderPages = \StringUtil::deserialize($orderPages);

				if (!empty($orderPages) && is_array($orderPages)) {
					$pages = array_map(function(){}, array_flip($orderPages));
				}
			}

		}
		else {
			$pagesResult = PageModel::findPublishedSubpagesWithoutGuestsByPid($pid, $this->showHidden);
		}
		if (!$pagesResult) {
			return array();
		}

		$userGroups = FE_USER_LOGGED_IN
			? \FrontendUser::getInstance()->groups
			: array();

		while ($pagesResult->next()) {

			$pageGroups = \StringUtil::deserialize($pagesResult->groups);

			if (
				$pagesResult->protected
				&& !BE_USER_LOGGED_IN
				&& (
					!is_array($pageGroups)
					|| !count(array_intersect($pageGroups, $userGroups))
				)
				&& !$this->showProtected
			) {
				continue;
			}

			$page = $this->getPageData($pagesResult, $imageSize);

			if (!empty($page['subpages']) && (!$stopLevel || $stopLevel > 1)) {
				$page['pages'] = $this->buildPagesArray($page['id'], $imageSize, null, $stopLevel ? $stopLevel - 1 : 0);
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
				$href = \StringUtil::encodeEmail($href);
			}
		}

		else if ($pagesResult->type === 'forward') {

			if ($pagesResult->jumpTo) {
				$targetPage = $pagesResult->getRelated('jumpTo');
			}
			else {
				$targetPage = \PageModel::findFirstPublishedRegularByPid($pagesResult->id);
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
			&& !\Input::get('articles')
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
		$page['title'] = \StringUtil::specialchars($pagesResult->title, true);
		$page['pageTitle'] = \StringUtil::specialchars($pagesResult->pageTitle, true);
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

		$image = \FilesModel::findByUuid($id);
		if (!$image) {
			return null;
		}

		try {
			$file = new \File($image->path, true);
			if (!$file->exists()) {
				return null;
			}
		}
		catch (\Exception $e) {
			return null;
		}

		if (is_string($size) && trim($size)) {
			$size = \StringUtil::deserialize($size);
		}
		if (!is_array($size)) {
			$size = array();
		}
		$size[0] = isset($size[0]) ? $size[0] : 0;
		$size[1] = isset($size[1]) ? $size[1] : 0;
		$size[2] = isset($size[2]) ? $size[2] : 'crop';

		$imageItem = array(
			'id' => $image->id,
			'uuid' => isset($image->uuid) ? $image->uuid : null,
			'name' => $file->basename,
			'singleSRC' => $image->path,
			'size' => $size,
		);

		$imageObject = new \FrontendTemplate('rsce_image_object');
		$this->addImageToTemplate($imageObject, $imageItem, null, null, $image);
		$imageObject = (object)$imageObject->getData();

		if (empty($imageObject->src)) {
			$imageObject->src = $imageObject->singleSRC;
		}

		$imageObject->id = $image->id;
		$imageObject->uuid = isset($image->uuid) ? \StringUtil::binToUuid($image->uuid) : null;

		return $imageObject;
	}
}

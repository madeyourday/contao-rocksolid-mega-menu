<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\Contao\MegaMenu\Model;

use Model;

/**
 * Mega Menu Column Model
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
class MenuColumnModel extends Model
{
	/**
	 * @var string Table name
	 */
	protected static $strTable = 'tl_rocksolid_mega_menu_column';

	/**
	 * Find published columns by their parent ID
	 *
	 * @param integer $id      The mege menu ID
	 * @param integer $limit   An optional limit
	 * @param array   $options An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no columns
	 */
	public static function findPublishedByPid($id, $limit = 0, array $options = array())
	{
		$time = time();
		$table = static::$strTable;

		$columns = array("$table.pid=? AND ($table.start='' OR $table.start<$time) AND ($table.stop='' OR $table.stop>$time) AND $table.published=1");

		if (! isset($options['order'])) {
			$options['order'] = "$table.sorting ASC";
		}

		if ($limit > 0) {
			$options['limit'] = $limit;
		}

		return static::findBy($columns, $id, $options);
	}
}

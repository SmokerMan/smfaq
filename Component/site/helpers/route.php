<?php
/**
 * SMFAQ
 *
 * @package		component for Joomla 1.6. - 2.5
 * @version		1.7 beta 1
 * @copyright	(C)2009 - 2012 by SmokerMan (http://joomla-code.ru)
 * @license		GNU/GPL v.3 see http://www.gnu.org/licenses/gpl.html
 */

// защита от прямого доступа
defined('_JEXEC') or die('@-_-@');

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Weblinks Component Route Helper
 *
 * @static
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.5
 */
abstract class SmfaqHelperRoute
{
	protected static $lookup;

	public static function getCategoryRoute($catid)
	{
		if ($catid instanceof JCategoryNode) {
			$id = $catid->id;
			$category = $catid;
		}
		else {
			$id = (int) $catid;
			$category = JCategories::getInstance('smfaq')->get($id);
		}

		if ($id < 1) {
			$link = '';
		}
		else {
			$needles = array(
				'category' => array($id)
			);

			//Create the link
			$link = 'index.php?option=com_smfaq&view=category&id='.$id;

			if ($category) {
				$catids = array_reverse($category->getPath());
				$needles = array(
						'category' => $catids,
						'categories' => $catids
				);

				if ($item = self::_findItem($needles)) {
					$link .= '&Itemid='.$item;
				}
				else if ($item = self::_findItem()) {
					$link .= '&Itemid='.$item;
				}
			}

		}

		return $link;
	}

	public static function getQuestionRoute($catid, $id)
	{
		//Create the link
		$link = 'index.php?option=com_smfaq&view=question&catid='.(int) $catid.'&id='.$id;
		$needles = array(
						'category' => array($catid)
		);
		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}

		return $link;

	}

	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication('site');
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null) {
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_smfaq');
			$items		= $menus->getItems('component_id', $component->id);
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view'])) {
					$view = $item->query['view'];

					if (!isset(self::$lookup[$view])) {
						self::$lookup[$view] = array();
					}

					if (isset($item->query['id'])) {
						self::$lookup[$view][$item->query['id']] = $item->id;
					}
				}
			}
		}

		if ($needles) {
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view])) {
					foreach($ids as $id)
					{
						if (isset(self::$lookup[$view][(int)$id])) {
							return self::$lookup[$view][(int)$id];
						}
					}
				}
			}
		}
		else {
			$active = $menus->getActive();
			if ($active) {
				return $active->id;
			}
		}

		return null;
	}
}
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

// подключаем библеотеку modellist Joomla
jimport('joomla.application.component.modellist');

class SmfaqModelUnpublished extends JModelList
{

	/**
	 * Метод для создания SQL запроса
	 *
	 * @return string  JDatabaseQuery
	 */
	protected function getListQuery() {
		// Создаем новый объект для работы с БД.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Выбираем нужные поля
		$query->select('a.id, a.question, a.created, a.created_by, a.catid');
		
		// Указываем из какой таблицы
		$query->from('#__smfaq AS a');
		// Фильтр по состоянию публикации
		$query->where('a.published = 0');

		$query->where('a.catid = '. (int) $this->getState('category.id'));
		$query->order('a.created DESC');

		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$this->setState('list.limit', '0');
		$this->setState('category.id', (int) JRequest::getInt('catid'));
	}

}
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
// Создаем класс модели
class SmFAQModelSmfaqList extends JModelList
{

	/**
	 * Конструктор.
	 *
	 * @param	array	дополнительно, ассоциативный массив параметров конфигурации
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array('a.id', 'a.catid', 'a.ip', 
			'a.question', 'a.published','a.checked_out','a.checked_out_time', 
			'a.created', 'a.created_by', 'category', 'a.ordering', 'a.answer_state',
			'a.user_id', 'v.vote_yes', 'v.vote_no');
		}

		parent::__construct($config);
	}

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
		$query->select($this->getState('list.select',
			'a.id, a.question, a.published, a.checked_out, a.checked_out_time, 
			a.created, a.created_by, a.created_by_email, a.ordering, a.catid,
			a.ordering, a.answer_state, a.ip, 
			a.user_id, count(co.id) AS comments'));

		// Указываем из какой таблицы
		$query->from('#__smfaq AS a');
		
		// Категории
		$query->select('c.title AS category');
		$query->leftjoin('#__categories AS c ON a.catid = c.id');
		
		// Опрос
		$query->select('v.vote_yes, v.vote_no');
		$query->leftjoin('#__smfaq_votes AS v ON a.id = v.question_id ');

		$query->leftjoin('#__smfaq_comments AS co ON a.id = co.question_id ');
		$query->group('a.id');
		
		// Фильтр для состоянию публикации
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Фильтр для поиска
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.question LIKE '.$search.')');
			}
		}
		
		// Фильтр для состояния ответа
		$state = $this->getState('filter.state');
		if (is_numeric($state) && $state > -1) { 
			$query->where('a.answer_state = '.(int) $state);
		}
		
		// Фильтр для категории
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = '.(int) $categoryId);
		}
						

		// Добавляем сортировку для списка.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'category') {
			$orderCol = 'category '.$orderDirn.', a.ordering';
		}
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}

	/**
	 * Метод для автоматической установки состояний для модели.
	 *
	 * Замечание. Не вызываейте getState в этом методе, это может привести к рекурсии.
	 */
	protected function populateState($ordering = null, $direction = null)
	{

		// Загрузка состояния фильтров.
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);
		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state');
		$this->setState('filter.state', $state);
		
		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);
		
		
		// Состояние сортировки для списка.
		parent::populateState('a.question', 'asc');
	}

	/**
	 * Метод для получения идентификатор состояния на основе конфигурации модели
	 *
	 * Он необходим, посколько модель может использоваться компонентом и различными
	 * модулями, которые могут требовать различный набор данных или сортировки.
	 *
	 * @param $id Префикс  ID
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.published');
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		$id.= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}


}

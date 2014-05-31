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

jimport('joomla.application.component.model');

class SmfaqModelQuestion extends JModel
{
	/**
	 * Category items data
	 *
	 * @var array
	 */
	protected $_item = null;


	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array();
		}
		parent::__construct($config);
	}


	/**
	 * построение запроса
	 *
	 * @return string SQL запрос
	 */
	public function getQuestion()
	{
		$user	= JFactory::getUser();

		$groups = $user->getAuthorisedViewLevels();
		$params = $this->getState('params', null);

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Выборка нужных полей.
		$query->select('a.*');
		$query->from('`#__smfaq` AS a');


        if (isset($params->answer_created_by_type) && (int) $params->answer_created_by_type === 1) {
			$query->select('u.name AS answer_created_by');
		} else {
			$query->select('u.username AS answer_created_by');
		}
		
		$query->join('LEFT', '#__users AS u ON u.id = a.answer_created_by_id');

		// для редакторов
		if ($user->authorise('core.edit', 'com_smfaq.category.'.$this->getState('category.id'))) {
			$query->select('v.vote_yes, v.vote_no, count(co.id) AS comments');
			$query->join('LEFT', '#__smfaq_votes AS v ON v.question_id = a.id');
			$query->leftjoin('#__smfaq_comments AS co ON a.id = co.question_id ');
			$query->group('a.id');
		}
		
		// Фильтр по публикации.
		$query->where('a.published = 1');
		
		$query->where('a.id = '.(int) $this->state->get('question.id'));
		
		$db->setQuery($query);

		$item = $db->loadObject();

		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}
		
		return $item;
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('site');

		
		$id = JRequest::getInt('id');
		$this->setState('question.id', $id);
		
		$catid = JRequest::getInt('catid');
		$this->setState('category.id', $id);
		$category = JCategories::getInstance('Smfaq')->get($catid);
		if (!$category) {
			throw new Exception(JText::_('COM_SMFAQ_ERROR_LOAD_CATEGORY'));
			return;
		}
		
		$params = $category->getParams();
		$this->setState('params', $params);
		
	}



}




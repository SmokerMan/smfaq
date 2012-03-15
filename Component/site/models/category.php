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

jimport('joomla.application.component.modellist');

class SmfaqModelCategory extends JModelList
{
	/**
	 * Category items data
	 *
	 * @var array
	 */
	protected $_item = null;

	protected $_articles = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;

	protected $_categories = null;

	protected $_category = null;


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
	protected function getListQuery()
	{
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());

		$params = $this->state->get('params');
		$params = $params->toObject();

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Выборка нужных полей.
		$query->select('a.*');
		$query->from('`#__smfaq` AS a');


		if (isset($params->created_by_type) && (int) $params->created_by_type === 1) {
			$query->select('u.name AS answer_created_by');
		} else {
			$query->select('u.username AS answer_created_by');
		}

		$query->join('LEFT', '#__users AS u ON u.id = a.answer_created_by_id');

		// категория
		if ($categoryId = $this->getState('category.id')) {
			$query->where('a.catid = '.(int) $categoryId);
			$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
			//$query->where('c.access IN ('.$groups.')');
			$query->where('c.published = 1');
		}

		// для редакторов
		if ($user->authorise('core.edit', 'com_smfaq.category.'.$this->getState('category.id'))) {
			$query->select('v.vote_yes, v.vote_no, count(co.id) AS comments');
			$query->join('LEFT', '#__smfaq_votes AS v ON v.question_id = a.id');
			$query->leftjoin('#__smfaq_comments AS co ON a.id = co.question_id ');
			$query->group('a.id');
		}

		// сортировка
		switch ($this->getState('ordering')) {
			case 0:
				$query->order('a.question');
				break;
			case 1:
				$query->order('a.question DESC');
				break;
			case 2:
				$query->order('a.created');
				break;
			case 3:
				$query->order('a.created DESC');
				break;
			default:
			case 4:
				$query->order('a.ordering');
			break;
			case 5:
				$query->order('a.ordering DESC');
				break;
		}

		// Фильтр по публикации.
		$query->where('a.published = 1');

		return $query;
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication(1);
		$user	= JFactory::getUser();
		$groups	= $user->getAuthorisedViewLevels();

		$id = (int) JRequest::getInt('id');
		$this->setState('category.id', $id);

		// параметры категории
		$categories = JCategories::getInstance('Smfaq');
		$category = $categories->get($id);
		$this->_item = $category;
		if ($category) {
			$params = $category->getParams();
		} else {
			$this->_item = false;
			return;
		}

		// установка уровня доступа
		$params->set('access-view', in_array($category->access, $groups));

		$this->setState('ordering', $params->get('ordering', 0));

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $params->get('list_limit', 10));
		$this->setState('list.limit', $limit);

		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		$this->setState('list.start', $limitstart);


		// Загрузка параметров
		$this->setState('params', $params);


	}


	/**
	 * Получение данных о текущей категории
	 *
	 * @return Ambiguous
	 */
	public function getCategory()
	{

		if (!$this->_item) {
			return false;
		}

		if(is_object($this->_item))
		{
			$this->_children = $this->_item->getChildren();
			$this->_parent = false;
			if($this->_item->getParent())
			{
				$this->_parent = $this->_item->getParent();
			}
			$this->_rightsibling = $this->_item->getSibling();
			$this->_leftsibling = $this->_item->getSibling();
		} else {
			$this->_children = false;
			$this->_parent = false;
		}


		return $this->_item;
	}

	/**
	 * Получение родительской категории
	 *
	 * @return	mixed	Массив категорий или false если ошибка.
	 */
	public function getParent()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_parent;
	}

	/**
	 * Получение смежных категориий.
	 *
	 * @return	mixed	Массив категорий или false если ошибка.
	 */
	protected function &getLeftSibling()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_leftsibling;
	}

	protected function &getRightSibling()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_rightsibling;
	}

	/**
	 * Получение подкатегорий
	 *
	 * @return	mixed	Массив категорий или false если ошибка.
	 */
	public function &getChildren()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_children;
	}

	/**
	 * Метод для записи голосования
	 * @param integer $id		ID вопроса
	 * @param integer $value	Значение
	 */
	public function storeVote($id = null, $value)
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);


		// Получение значений голосования для ID вопроса
		$query->select('*');
		$query->from('#__smfaq_votes');
		$query->where('question_id = '.(int) $id);

		$db->setQuery($query);
		$voting = $db->loadResult();

		$vote_value = $value ? 'vote_yes' : 'vote_no';

		//Если нет записей, добавляем новую.
		if (!$voting) {
			$query = 'INSERT INTO #__smfaq_votes ( question_id, '.$vote_value.' )' .
					' VALUES ( '.(int) $id.', 1 )';
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		} else {
			$query = 'UPDATE #__smfaq_votes' .
					' SET '.$vote_value.' = '.$vote_value.' + 1 ' .
					' WHERE question_id = '.(int) $id;
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}

		}

		return true;
	}

	/**
	 * Отправка формы вопроса
	 * @param  array	$post Массив данных
	 * @return boolean
	 */
	public function send($post, &$category)
	{
		$table = $this->getTable('Smfaq');

		$post['created'] = JFactory::getDate()->toMySQL();
		$post['ip'] = $_SERVER['REMOTE_ADDR'];

		// удаляем ненужные значения если такие будут
		unset($post['answer'], $post['checked_out'], $post['checked_out_time'],
		$post['published'], $post['answer_created'], $post['answer_state'],
		$post['access'], $post['answer_created_by_id']);

		// установка порядка
		$where = 'catid = ' . (int) $post['catid'];
		$table->ordering = $table->getNextOrder( $where );

		// Bind the form fields to  table
		if (!$table->bind($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure table is valid
		if (!$table->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store table to the database
		if (!$table->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$pkName = $table->getKeyName();
		if (isset($table->$pkName)) {
			$this->setState('smfaq.id', $table->$pkName);
		}

		$mail = $this->sendMail($post, $category);
		if (!$mail) {
			return false;
		}

		unset($post);

		return true;
	}


	/**
	 * Метод для отправки оповещения редакторам
	 * @param integer $catid	ID категории
	 * @param string  $name		Автор вопроса
	 */
	protected function sendMail($data, &$category)
	{
		$params = $category->getParams();

		// отключение
		if ($params->get('disabled_mail')) {
			return true;
		}

		$rules = JAccess::getAssetRules('com_smfaq.category.'.$category->id, false);
		$r = $rules->getData();

		$group = $r['core.edit']->getData();
		if (is_array($group) && count($group) > 0) {
			foreach ($group as $key => $value) {
				if ($value == 1) {
					$groups[] = JAccess::getUsersByGroup($key);
				}
			}
			
			if (isset($groups)) {
				$users_id = array();
				foreach ($groups as $group) {
					if ($group) {
						for ($i = 0; $i < count($group); $i++) {
							$users_id[] = $group[$i];
						}
					}
				}
				if (count($users_id) > 0) {
					$users_id = implode(",", array_unique($users_id));
					$db		= JFactory::getDBO();
					$query = 'SELECT email FROM #__users WHERE id IN ('.$users_id.')';
					$db->setQuery($query);
					$emails = $db->loadResultArray();
				
					if ($emails) {
						$config	= JFactory::getConfig();
						$mailfrom = $config->get('mailfrom');
						$fromname = $config->get('fromname');
				
						$date = JFactory::getDate(null, $config->get('offset'));
						$date = $date->format('d.m.Y H:i');
				
						$link = JURI::root().'index.php?option=com_smfaq&task=smfaq.edit&id='.$this->getState('smfaq.id').'&catid='.$category->id;
						$subject = JText::sprintf('COM_SMFAQ_MAIL_SUBJECT_NEW', $category->title);
						$message = JText::sprintf('COM_SMFAQ_MAIL_MESSAGE_NEW', $data['created_by'], $date, $data['question'], $link);
				

						$mail = JFactory::getMailer();
						if (($mail->Mailer == 'mail') && ! function_exists('mail')) {
							return false;
						}
				
						foreach ($emails as $email) {
							$send = $mail->sendMail($mailfrom, $fromname, $email, $subject, $message);
							if ($send !== true) {
								return false;
							}
						}
					}
				}				
			}
		} else {

		}

		return true;
	}


}




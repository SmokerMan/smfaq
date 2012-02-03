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

jimport('joomla.application.component.modelform');

class SmfaqModelSmfaq extends JModelForm
{

	/**
	 * Метод для получения бланка формы
	 * @param array 	$data		Дополнительно, массив данных для формы
	 * @param boolean 	$loadData	True если в форму будут загружаться данные (по умолчанию), false нет.
	 * @return если успешно объект JForm, иначе false
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Берем форму
		JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/fields');
		JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/forms');

		$form = $this->loadForm('com_smfaq.smfaq', 'smfaq', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Возвращает ссылку на объект таблицы БД
	 * @param $type		Тип таблицы для создания экземпляра
	 * @param $prefix	Превикс класса таблицы. Дополнительно
	 * @param $config	Конфигурация массива для модели
	 * @return объект JTable
	 */
	public function getTable($type = 'Smfaq', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		// Проверка сессии на ранее введенные данные.
		$data = JFactory::getApplication()->getUserState('com_smfaq.edit.smfaq.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			$user = JFactory::getUser();
			$datenow = JFactory::getDate();
			// Установка значение по умолчанию.
			if (!$data->get('id')) {
				$data->set('catid', JRequest::getInt('catid'));
				$data->set('created_by', $user->get('username'));
				$data->set('created_by_email', $user->get('email'));
			}

			if ($data->get('answer_created') == '0000-00-00 00:00:00') {
				$data->set('answer_created', null);
			}

		}

		return $data;
	}

	public function getItem($pk = null)
	{
		// Initialise variables.

		$pk		= (!empty($pk)) ? $pk : (int) JRequest::getInt('id');
		$table	= $this->getTable();

		if ($pk > 0) {
			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError()) {
				$this->setError($table->getError());
				return false;
			}
		}

		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');

		return $item;
	}

	public function getComments()
	{
		$id = JRequest::getInt('id');
		if ($id) {
			$query = 'SELECT comment, id, created' .
				' FROM #__smfaq_comments' .
				' WHERE question_id = '. (int) $id .
				' ORDER BY created DESC';
			$this->_db->setQuery($query);
			$comments = $this->_db->loadObjectList();
		} else {
			return;
		}
		return $comments;
	}

	/**
	 * Сохранение данных для редактора
	 * @param array $data	Данные из формы
	 * @return boolean
	 */
	public function save($data)
	{
		// Initialise variables;
		$user 	 =  JFactory::getUser();
		$table		= $this->getTable();
		$key		= $table->getKeyName();
		$pk			= (!empty($data[$key])) ? $data[$key] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;

		// устнаовка дат
		$config = JFactory::getConfig();
		if (isset($data['created'])) {
			$date = JFactory::getDate($data['created'],$config->get('offset'));
			$data['created'] = $date->toMySQL();
		} else {
			$data['created'] = JFactory::getDate()->toMySQL();
		}

		if ($data['answer_created']) {
			$date = JFactory::getDate($data['answer_created'],$config->get('offset'));
			$data['answer_created'] = $date->toMySQL();
		}

		// проверка автора
		if (!$data['id']) {
			if ($data['created_by'] == $user->get('username')) {
				$data['user_id'] = $user->get('id');
			} else {
				$data['user_id'] = null;
			}
		}
		// установка автора ответа
		$data['answer_created_by_id'] = $user->get('id');

		// установка порядка
		$where = 'catid = ' . (int) $data['catid'];
		$table->ordering = $table->getNextOrder($where);

		// Allow an exception to be throw.
		try
		{
			// Load the row if saving an existing record.
			if ($pk > 0) {
				$table->load($pk);
				$isNew = false;
			}

			// Bind the data.
			if (!$table->bind($data)) {
				$this->setError($table->getError());
				return false;
			}

			// Проверка данных.
			if (!$table->check()) {
				$this->setError($table->getError());
				return false;
			}

			// Запись данных.
			if (!$table->store()) {
				$this->setError($table->getError());
				return false;
			}

			// Очистка кэша компонента.
			$cache = JFactory::getCache($this->option);
			$cache->clean();

			// Оправка email пользователю
			if ($data['answer_email'] && ($data['published'] == 1)) {
				$this->sendUser($data);
			}

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}


		return true;
	}

	/**
	 * метод для отправки уедомления
	 */
	protected function sendUser($data)
	{

		$config	= JFactory::getConfig();
		$mailfrom = $config->get('mailfrom');
		$fromname = $config->get('fromname');
		
		$categories = JCategories::getInstance('SmFaq');
		$category = $categories->get((int) $data['catid']);		

		$subject = JText::sprintf('COM_SMFAQ_MAIL_SUBJECT_ANSWER', $category->title);
		$itemId = JRequest::getInt('Itemid');
		$link = JURI::root().'index.php?option=com_smfaq&view=category&id='.$category->id.'&Itemid='.$itemId.'&limit=0#p'.$data['id'];
		$date = JHtml::date($data['created'], 'd.m.Y');
		$message = JText::sprintf('COM_SMFAQ_MAIL_MESSAGE_ANSWER', $date, $data['question'], $link);
		
		jimport('joomla.mail.mail');
		$mail = JMail::getInstance();
		

		$send = $mail->sendMail($mailfrom, $fromname, $data['created_by_email'], $subject, $message);
		if ($send !== true) {
			//
			return false;
		}
		
		return true;
	}
	
	protected function preprocessForm(JForm $form, $data, $group = 'smfaq')
	{
		parent::preprocessForm($form, $data, $group);
	}
	


}
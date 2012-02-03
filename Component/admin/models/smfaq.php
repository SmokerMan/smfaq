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
// Подключаем библеотеку ModelForm
jimport('joomla.application.component.modeladmin');
/**
 * Модель Smfaq
 */
class SmfaqModelSmfaq extends JModelAdmin
{

	/**
	 * Метод для получения бланка формы
	 * @param array 	$data			Дополнительно, массив данных для формы
	 * @param boolean 	$loadData		True если в форму будут загружаться данные (по умолчанию), false нет.
	 * @return если успешно объект JForm, иначе false
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Берем форму
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

	/**
	 * Метод для загрузки данных, которые подставляются в форму
	 * @return mixed	Данные для формы.
	 */
	protected function loadFormData()
	{
		// Проверка сессии на ранее введенные данные.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_smfaq.edit.smfaq.data', array());

		if (empty($data)) {
			$data = $this->getItem();
			// Установка значение по умолчанию.
			$app = JFactory::getApplication();
			if (!$data->get('catid')) {
				$data->set('catid', JRequest::getInt('catid', $app->getUserState('com_smfaq.smfaqlist.filter.category_id')));
			}
		}

		return $data;
	}


	/**
	 * Method to get a single record.
	 *
	 * @param	integer	$pk	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk		= (!empty($pk)) ? $pk : (int) $this->getState($this->getName().'.id');
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

	/**
	 * Подготовка перед сохранением
	 *
	 */
	protected function prepareTable(&$table)
	{
		$user = JFactory::getUser();
		$config = JFactory::getConfig();
		

		$table->answer_created_by_id = $user->get('id');

		if (empty($table->created_by)) {
			// параметры категории
			jimport( 'joomla.application.categories');
			$categories = JCategories::getInstance('SmFaq');
			$category = $categories->get((int) $table->catid);
			$params = $category->getParams();
			if ($params->getValue('created_by_type')) {
				$table->created_by = $user->get('name');			
			} else {
				$table->created_by = $user->get('username');
			}
			$table->created_by_email = $user->get('email');
			$table->user_id = $user->get('id');
		}

		// Установка дат
		$datenow = JFactory::getDate()->toSql();
		if (empty($table->created )) {
			$table->created = $datenow;
		} else {
			$table->created = JFactory::getDate($table->created)->toSql(false);
		}

		//если дата ответа пустая ставим текущю дату
		if (empty($table->answer_created)) {
			$table->answer_created = $datenow;
		} else {
			$table->answer_created = JFactory::getDate($table->answer_created)->toSql(false);
		}


		if (empty($table->id)) {
			// Установка сортировки для новой записи
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__smfaq WHERE catid = ' . (int) $table->catid);
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
		}
		
		if ($table->answer_email && $table->published) {
			$data = JArrayHelper::fromObject($table);
			$this->sendUser($data);
		}
	}


	/**
	 * Метод для записи сортировки.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = '.(int) $table->catid;
		return $condition;
	}

	/**
	 * метод для отправки уведомления
	 */
	protected function sendUser($data)
	{

		$config	= JFactory::getConfig();
		$mailfrom = $config->get('mailfrom');
		$fromname = $config->get('fromname');
		
		jimport('joomla.application.categories');
		$categories = JCategories::getInstance('SmFaq');
		$category = $categories->get((int) $data['catid']);		

		$subject = JText::sprintf('COM_SMFAQ_MAIL_SUBJECT_ANSWER', $category->title);
		$itemId = JRequest::getInt('Itemid');
		$link = $link = JURI::root().'index.php?option=com_smfaq&view=category&id='.$category->id.'&Itemid='.$itemId.'&limit=0#p'.$data['id'];
		$date = JHtml::date($data['created'], 'd.m.Y');
		$message = JText::sprintf('COM_SMFAQ_MAIL_MESSAGE_ANSWER', $date, $data['question'], $link);
		
		
		
		jimport('joomla.mail.mail');
		$mail = JMail::getInstance();
		

		$send = $mail->sendMail($mailfrom, $fromname, $data['created_by_email'], $subject, $message);
		
		return;
	}

	
	protected function preprocessForm(JForm $form, $data, $group = 'smfaq')
	{
		parent::preprocessForm($form, $data, $group);
	}

	

	
}



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
// Подключаем библиотеку контроллера Joomla
jimport('joomla.application.component.controllerform');
/**
 * Контроллер для изменеия или создания новой записи
 */
class SmFAQControllerSmfaq extends JControllerForm
{
	/**
	 * Конструктор
	 */
	function __construct($config=array())
	{
		// Задаем вид для списка
		$this->view_list = 'smfaqlist';
		
		parent::__construct($config);
	}

	public function add()
	{
		$db = JFactory::getDbo();
		$query = "SELECT id FROM #__categories WHERE `extension`='com_smfaq' AND `published`!='-2'";
		$db->setQuery($query);
		$categoryCheck = $db->loadResult();
		if (!$categoryCheck) {
			$this->setError(JText::_('COM_SMFAQ_NO_CATEGORY_ERROR'), 'error');
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list));
			return;
		}
		
		parent::add();
		
	}
	/**
	 * Метод для проверки на добавление новой записи
	 *
	 * @param	array	$data	Массив данных
	 * @return	boolean
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user		= JFactory::getUser();
		$categoryId	= JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
		$allow		= null;

		if ($categoryId) {
			// If the category has been passed in the URL check it.
			$allow	= $user->authorise('core.create', $this->option.'.category.'.$categoryId);
		}

		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	/**
	 * Проверка на редактирование записи.
	 *
	 * @param	array	$data	Массив данных.
	 * @param	string	$key	Название ключа для первичного ключа.
	 *
	 * @return	boolean
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId) {
			$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId) {
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise('core.edit', $this->option.'.category.'.$categoryId);
		} else {
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}
	


}

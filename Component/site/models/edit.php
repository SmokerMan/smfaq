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

//jimport('joomla.application.component.modelform');

require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'smfaq.php';

class SmfaqModelEdit extends SmfaqModelSmfaq
{

	public function __construct($config) 
	{
		//подключение языков из админки
		$lang = JFactory::getLanguage();
		$lang->load('com_smfaq', JPATH_ADMINISTRATOR, null, false, false);		
		parent::__construct($config = array());
	}

	
	public function getForm($data = array(), $loadData = true)
	{
		JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'forms');
		JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'fields');
			
		return parent::getForm($data, $loadData);
	}	
	
	protected function loadFormData()
	{
		// Проверка сессии на ранее введенные данные.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_smfaq.edit.edit.data', array());
	
		if (empty($data)) {
			$data = $this->getItem();
		}
	
		return $data;
	}
	
}
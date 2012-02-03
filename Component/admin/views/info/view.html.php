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
// Подключаем библиотеку представления Joomla
jimport('joomla.application.component.view');

/**
 * Вид для отображения списка записей
 *
 */
class SmFAQViewInfo extends JView {

	/**
	 * Метод для отображения вида
	 */
	public function display($tpl = null)
	{
		$data = JApplicationHelper::parseXMLInstallFile(JPATH_COMPONENT.DS.'smfaq.xml');
		
		$this->assignRef('data', $data);
		
		require_once JPATH_COMPONENT.'/helpers/smfaq.php';
		SmFaqHelper::addSubmenu($this->_name);
		
		parent::display($tpl);
		
	}

}


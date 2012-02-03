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
// Подключаем библеотеку контроллера Joomla
jimport('joomla.application.component.controller');
/**
 * Основной контроллер
 */
class SmFAQController extends JController
{
	/**
	 * Отображаем задачу
	 * Параметр $cachable устанавливает использовать или нет кэш
	 */
	function display($cachable = false)
	{
		// устанавка вида по умолчанию
		JRequest::setVar('view', JRequest::getCmd('view', 'SmfaqList'));
		
		//подключение стилей
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_smfaq/css/smfaq.css');

		parent::display($cachable);
	}
}


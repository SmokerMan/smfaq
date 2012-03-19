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

if (!JFactory::getUser()->authorise('core.manage', 'com_smfaq')) {
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
	return;
}
// Подключаем библеотеку контроллера Joomla
jimport('joomla.application.component.controller');
// Получаем экземпляр класса контроллера с префиксом SmFAQ
$controller = JController::getInstance('SmFAQ');
// Обрабатываем запрос (task)
$controller->execute(JRequest::getCmd('task'));
// Переадресуем, если установлено контроллером
$controller->redirect();

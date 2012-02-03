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
jimport('joomla.application.component.controller');
/**
 * Контроллер для изменеия или создания новой записи
 */
class SmFAQControllerSmfaq extends JController
{
	public function delcomment()
	{
		$id = JRequest::getInt('id');
		$query = ' DELETE FROM #__smfaq_comments ' .
				 ' WHERE id = ' . (int) $id;
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError( 500, $db->stderr());
		}
		echo JText::_('COM_SMFAQ_COMMENT_DEL_OK');
		return;
	}
	
	public function resetvote()
	{
		$id = JRequest::getInt('id');
		$query = ' DELETE FROM #__smfaq_votes ' .
				 ' WHERE question_id = ' . (int) $id;
		$db		=& JFactory::getDBO();
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError( 500, $db->stderr());
		}
		echo JText::_('COM_SMFAQ_RESET_VOTE_OK');
		return;
	}
	
}
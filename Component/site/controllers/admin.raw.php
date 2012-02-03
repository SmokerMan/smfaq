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


jimport('joomla.application.component.controller');

class SmfaqControllerAdmin extends JController
{

	/**
	 * Метод для показа неопубликованых вопросов
	 * @return 
	 */
	public function show_unpublished()
	{
		// Проверка доступа
		$catid = (int) JRequest::getInt('catid');
		$user = JFactory::getUser();
		if (!$catid || $user->guest || !$user->authorise('core.edit', 'com_smfaq.category.'.$catid)) {
			$this->setError(JText::_('COM_SMFAQ_NOT_PERMITTED'));
			echo $this->getError();
			return false;
				
		}
		JRequest::setVar('Itemid', JRequest::getInt('Itemid', null));
		
		$model = $this->getModel('unpublished');
		$view = $this->getView('unpublished','html');
		$view->setModel($model, true);
		$view->display();

		return;

	}
	
	/**
	 * TODO: Разобраться с authorise
	 */
	public function delcomment()
	{
		$user = JFactory::getUser();
		$id = JRequest::getInt('id');
		
		$db	= JFactory::getDBO();
		$query = ' SELECT catid FROM #__smfaq ' .
				 ' WHERE id = ' . (int) $id;
		
		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->stderr());
			echo $this->getError();
			return;
		}
		
		$catid = $db->loadResult();		
		
		if (!$user->authorise('core.edit', 'com_smfaq.category.'.$catid)) {
			$this->setError(JText::_('COM_SMFAQ_NOT_PERMITTED'));
			echo $this->getError();
			return;
				
		}
		
		$query = ' DELETE FROM #__smfaq_comments ' .
				 ' WHERE id = ' . (int) $id;
		
		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->stderr());
			echo $this->getError();
			return;
		}
		echo JText::_('COM_SMFAQ_COMMENT_DEL_OK');
		return;
	}
	
}






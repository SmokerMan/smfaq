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


jimport('joomla.application.component.controllerform');

class SmfaqControllerEdit extends JControllerForm
{

	public function __construct($config = array())
	{

		$this->view_list = 'category';
		
		

		return parent::__construct($config);
	}

	
	public function add()
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$context = "$this->option.edit.$this->context";

		// Access check.
		if (!$this->allowAdd())
		{
			// Set the internal error and also the redirect error.
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Clear the record edit information from the session.
		$app->setUserState($context . '.data', null);
		
		$catid = JRequest::getInt('catid');

		// Redirect to the edit screen.
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item
				.'&catid='.$catid.'&id=0'.$this->getRedirectToItemAppend(), false
			)
		);

		return true;
	}
		
	/* 
	 *	Проверка прав на добавление вопроса редактором
	 */
	protected function allowAdd($data = array())
	{
	
		$user 	= JFactory::getUser();
		$catId	= JRequest::getInt('catid');
		$allow	= false;
		if (!$user->guest && $catId) {
			$allow = $user->authorise('core.edit', 'com_smfaq.category.'.$catId);
		}
	
		return $allow;
	}

	/*
	 *	Проверка прав на редактирование вопроса редактором
	 */	
	protected function allowEdit($data = array(), $urlVar = null)
	{
		$user 	= JFactory::getUser();
		$catid = JRequest::getInt('catid');
		if ($user->guest || ($user->authorise('core.edit', 'com_smfaq.category.'.$catid) !== true)) {
			return false;
		}

		$record		= $this->getModel()->getItem(JRequest::getInt($urlVar));
		if ($user->authorise('core.edit', 'com_smfaq.category.'.$record->catid)) {
			return true;
		}

		return false;

	}

	/*
	*	Проверка прав на сохранение вопроса редактором
	*/	
	protected function allowSave($data = array())
	{
		$user 	= JFactory::getUser();
		$catId	= JArrayHelper::getValue($data, 'catid', null, 'int');
		$allow		= false;
		if ($catId) {
			$allow = $user->authorise('core.edit', 'com_smfaq.category.'.$catId);
		}

		return $allow;
	}

	
	public function edit($key = 'id', $urlVar = 'id')
	{
		if (!$this->allowEdit(null, 'id')) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');
			$return = 'index.php?option=com_smfaq&task=edit.edit&id='.JRequest::getInt('id').'&catid='.JRequest::getInt('catid');
			$this->setRedirect(JRoute::_('index.php?option=com_users&task=login&return='.base64_encode($return)));
			return false;
		}
		parent::edit($key, $urlVar);
	}	
	
	protected function postSaveHook(JModel &$model, $validData)
	{
		$task = $this->getTask();
		if ($task == 'save') {
			$this->setRedirect(JRoute::_(SmfaqHelperRoute::getCategoryRoute($validData['catid']), false));
		}
	}
	
	public function cancel($key = null) 
	{
		if (parent::cancel($key)) {
			$catid = JRequest::getInt('catid');
			$this->setRedirect(JRoute::_(SmfaqHelperRoute::getCategoryRoute($catid), false));;
		}
	}
	
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$tmpl = JRequest::getCmd('tmpl');
		$layout = JRequest::getCmd('layout', 'edit');
		$catid =  JRequest::getInt('catid', null);
		
		$append = '';
	
		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}
	
		if ($layout)
		{
			$append .= '&layout=' . $layout;
		}
	
		if ($recordId)
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}
		
		if ($catid)
		{
			$append .= '&catid=' . $catid;
		}		
	
		return $append;
	}	
}



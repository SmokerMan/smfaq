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

class SmfaqControllerSmfaq extends JControllerForm
{

	public function __construct($config = array())
	{

		$this->view_list = 'category';
		
		//parent::addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'smfaq');

		return parent::__construct($config);
	}

	/* (non-PHPdoc)
	 * @see JControllerForm::add()
	 */
	public function add() 
	{
		if(parent::add()) {
			$this->setRedirect('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend(), false);
		}
	}

	/* (non-PHPdoc)
	 * @see JControllerForm::edit()
	 */
	public function edit($key = 'id', $urlVar = 'id')
	{
		if (!$this->allowEdit(null, 'id')) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');
			$return = 'index.php?option=com_smfaq&task=smfaq.edit&id='.JRequest::getInt('id').'&catid='.JRequest::getInt('catid');
			$this->setRedirect(JRoute::_('index.php?option=com_users&task=login&return='.base64_encode($return)));
			return;
		}
		parent::edit($key, $urlVar);
	}

	public function save($key = 'id', $urlVar = null)
	{
		$data	= JRequest::getVar('jform', array(), 'post', 'array');
		$catid 	= JArrayHelper::getValue($data, 'catid', null, 'int');
		JRequest::setVar('catid', $catid);
		
		$id = $data['id'];
		if (!$id) {
			JRequest::setVar('id', null);
		}
		parent::save($key = 'id', $urlVar);
	}

	protected function allowAdd()
	{
		$user 	= JFactory::getUser();
		$catId	= JRequest::getInt('catid');
		$allow	= false;
		if (!$user->guest && $catId) {
			$allow = $user->authorise('core.edit', 'com_smfaq.category.'.$catId);
		}

		return $allow;
	}

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

	protected function allowSave($data = array())
	{
		$user 	= JFactory::getUser();
		$catId	= JArrayHelper::getValue($data, 'catid', null, 'int');
		$allow		= null;
		if ($catId) {
			$allow = $user->authorise('core.edit', 'com_smfaq.category.'.$catId);
		}

		return $allow;
	}


	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

		$catid = JRequest::getInt('catid', null);
		if ($catid) {
			$append .= '&catid='.$catid;
		}

		$itemId = JRequest::getInt('Itemid', null);
		if ($itemId) {

			$append .= '&Itemid='.$itemId;
		}

		return $append;
	}

	protected function getRedirectToListAppend() {
		$append ='';

		$catid = JRequest::getInt('catid', null);
		if ($catid) {
			$append .= '&id='.$catid;
		}
		$itemId = JRequest::getInt('Itemid', null);
		if ($itemId) {

			$append .= '&Itemid='.$itemId;
		}
		
		return $append;
	}
	
}



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

jimport( 'joomla.application.component.view');

class SmfaqViewQuestion extends JView
{
	protected $item;
	protected $user;
	protected $params;
	

	function display( $tpl = null )
	{
		$app		= JFactory::getApplication();
		$this->user	= JFactory::getUser();

		// берем данные из модели
		$state		= $this->get('State');
		$this->item		= $this->get('Question');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($this->item == false) {
			JError::raiseError(404, JText::_('COM_SMFAQ_QUESTION_NOT_FOUND'));
			return;
		}

		$this->params = &$state->params;

		if ($layout = $this->params->get('category_layout')) {
			$this->setLayout($layout);
		}

		$print = JRequest::getCmd('tmpl');
		if ($print == 'component') {
			parent::display('print');
			return;
		}
		
		$this->_prepareDocument();

		$dispatcher = JDispatcher::getInstance();
		
		$events = $dispatcher->trigger('onSmfaqBeforeDisplay', array('com_smfaq.question', $this->item, $this->params));
		if ($events && is_array($events)) {
			foreach ($events as $event) {
				echo $event;
			};
		}
		
		parent::display($tpl);
		
		$events = $dispatcher->trigger('onSmfaqAfterDisplay', array('com_smfaq.question', $this->item, $this->params));
		if ($events && is_array($events)) {
			foreach ($events as $event) {
				echo $event;
			};
		}
		
		return;

	}

	/**
	 * Подготовка документа
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		
		//Заголовок
		$title = $this->item->question;
		if ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
		
		//мета данные
		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}		

		// Подключение js и стилей
		$baseurl = $this->document->baseurl;
		$this->document->addStyleSheet($this->document->baseurl . "components/com_smfaq/css/smfaq.css");
		if ($this->params->get('show_poll', 1)) {
			$jscript = 'SmFaq.url = \'index.php?option=com_smfaq&amp;catid='.$this->item->catid.'&amp;format=raw&amp;task=\';';
			$this->document->addScriptDeclaration($jscript);			
			$this->document->addScript($baseurl . "components/com_smfaq/js/smfaq.js");
			
		}
		
	}

}

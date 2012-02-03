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

jimport( 'joomla.application.component.view' );

class SmfaqViewSmfaq extends JView
{
	protected $state;
	protected $item;
	protected $form;
	protected $comments;

	public function display($tpl = null)
	{
		// данные из модели
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->comments	= $this->get('Comments');
		
		
		// запрет прямого просмотра для пользователей
		$user		= JFactory::getUser();
		$catid = $this->form->getValue('catid');
		
		if (!$catid) {
			$catid = JRequest::getInt('catid');
		}

		$authorised = $user->authorise('core.edit', 'com_smfaq.category.'.$catid);
		
		if (($authorised !== true) || !$catid) {
			JError::raiseError(403, JText::_("JERROR_ALERTNOAUTHOR"));
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		// установка значений для формы
		if ($this->form->getValue('user_id')) {
			$this->form->setFieldAttribute('created_by', 'readonly', 'true');
			$this->form->setFieldAttribute('created_by_email', 'readonly', 'true');
		}
		if (!$this->form->getValue('answer_email')) {
			$this->form->setFieldAttribute('answer_email', 'disabled', 'true');
		}
		
		$this->form->setFieldAttribute('answer', 'buttons', 'false');

		
		$baseurl = $this->document->baseurl;
		
		$this->document->addStyleSheet( $baseurl . 'components/com_smfaq/css/smfaq_edit.css' );

		require_once $baseurl . 'components/com_smfaq/libraries/calendar/calendar.php';
		SmfaqHelperCalendar::setup();
		$this->document->addScript( $baseurl . 'components/com_smfaq/libraries/calendar/js/jscal2.js');		
		$this->document->addStyleSheet( $baseurl . 'components/com_smfaq/libraries/calendar/css/jscal2.css');
		$this->document->addStyleSheet( $baseurl . 'components/com_smfaq/libraries/calendar/css/gold/gold.css');
		
		$url = JURI::getInstance();
		$this->assignRef('url', $url);

		
		parent::display($tpl);
	}
}
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
// Подключаем библеотеку Представляния Joomla
jimport('joomla.application.component.view');
/**
 * Вид для редактирования записи
 */
class SmFAQViewSmfaq extends JView
{

	protected $form;

	/**
	 * метод отображения Вида
	 * @return void
	 */
	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');

		// Проверка на ошибки.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Установка значений для формы
		if ($this->form->getValue('user_id')) {
			$this->form->setFieldAttribute('created_by', 'readonly', 'true');
			$this->form->setFieldAttribute('created_by_email', 'readonly', 'true');
		}

		if (!$this->form->getValue('answer_email')) {
			$this->form->setFieldAttribute('answer_email', 'disabled', 'true');
		}

		//установки календарика
		$baseurl = JURI::root();
		require_once JPATH_SITE.DS.'components/com_smfaq/libraries/calendar/calendar.php';
		SmfaqHelperCalendar::setup();
		$this->document->addScript( $baseurl . 'components/com_smfaq/libraries/calendar/js/jscal2.js');
		$this->document->addStyleSheet( $baseurl . 'components/com_smfaq/libraries/calendar/css/jscal2.css');
		$this->document->addStyleSheet( $baseurl . 'components/com_smfaq/libraries/calendar/css/gold/gold.css');		
		
		// Установка тулбара
		$this->_setToolBar();
		// Отображаем шаблон
		parent::display($tpl);
	}
	/**
	 * Метод для установки заголовка и тулбара
	 */
	protected function _setToolBar()
	{
		// Скрываем главное меню
		JRequest::setVar('hidemainmenu', 1);
		// Подключение скриптов для тулбара
		JHTML::_('behavior.tooltip');
		// Установка живучести сессии
		JHTML::_('behavior.keepalive');
		// Подключение скриптов проверки формы
		JHTML::_('behavior.formvalidation');



		// Проверка на новую запись
		if ($this->form->getValue('id') < 1) {
			$isNew = true;
		} else {
			$isNew = false;
		}

		JToolBarHelper::title(JText::_('COM_SMFAQ_MANAGER_QUESTION') . ': <small>[ ' . ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT')) . ' ]</small>', 'smfaq');
		JToolBarHelper::apply('smfaq.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('smfaq.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::custom('smfaq.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		JToolBarHelper::custom('smfaq.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		JToolBarHelper::cancel('smfaq.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
	}
}

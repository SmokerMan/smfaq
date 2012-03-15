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
class SmFAQViewSmfaqList extends JView {

	//Элементы, которые будут отображены
	protected $items;
	//Состояние элемента
	protected $state;
	//Пагинация для элементов
	protected $pagination;

	/**
	 * Метод для отображения вида
	 */
	public function display($tpl = null)
	{
		// Берем данные из модели
		$this->state		= $this->get('State');
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
			
		// Проверка на ошибки.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		// Подключаем Тулбар
		$this->_setToolBar();

		// Отображаем в разметке
		parent::display($tpl);
	}

	/**
	 * Установки тулбара
	 */
	protected function _setToolBar()
	{
		
		require_once JPATH_COMPONENT.'/helpers/smfaq.php';
		JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
		SmFaqHelper::addSubmenu($this->_name);
		$canDo	= SmFaqHelper::getActions($this->state->get('filter.category_id'));

		// Выводим нужные кнопки и заголовок
		JToolBarHelper::title(JText::_('COM_SMFAQ_MANAGER_QUESTIONS'),'smfaq');
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNewX('smfaq.add', 'JTOOLBAR_NEW');
		}
		
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editListX('smfaq.edit', 'JTOOLBAR_EDIT');
		}
		JToolBarHelper::divider();
		
		JToolBarHelper::custom('smfaqlist.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('smfaqlist.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::checkin('smfaqlist.checkin');
		
		// Замена тулбара карзины на удаление
		if ($this->state->get('filter.published') == -2) {
			JToolBarHelper::deleteList('COM_SMFAQ_YOU_WANT_DELETE_THIS', 'smfaqlist.delete','JTOOLBAR_EMPTY_TRASH');
		} else {
			JToolBarHelper::trash('smfaqlist.trash','JTOOLBAR_TRASH');
		}
		JToolBarHelper::divider();
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_smfaq');
		}
	}
}


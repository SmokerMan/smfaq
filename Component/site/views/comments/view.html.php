<?php
/**
 * SMFAQ
 *
 * @package		component for Joomla 2.5+
 * @version		1.7 beta 2
 * @copyright	(C)2009 - 2011 by SmokerMan (http://joomla-code.ru)
 * @license		GNU/GPL v.3 see http://www.gnu.org/licenses/gpl.html
 */

// защита от прямого доступа
defined('_JEXEC') or die('@-_-@');

jimport( 'joomla.application.component.view');

class SmfaqViewComments extends JView
{

	/**
	 * Метод для отображения формы вопроса
	 * @param unknown_type $tpl
	 */
	public function display($tpl = null)
	{

		$id = JRequest::getInt('id', null, 'POST');
		$catid = JRequest::getInt('catid', null, 'catid');

		$categories = JCategories::getInstance('SmFaq');
		$category = $categories->get($catid);

		$params = $category->getParams();
		
		if ($layout = $params->get('category_layout')) {
			$this->setLayout($layout);
		}

		$this->assignRef('id', $id);

		parent::display($tpl);
	}


}

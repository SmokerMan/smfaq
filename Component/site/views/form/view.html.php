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

class SmfaqViewForm extends JView
{

	/**
	 * Метод для отображения формы вопроса
	 * @param unknown_type $tpl
	 */
	public function display($tpl = null)
	{

		$catid = JRequest::getInt('catid', null, 'GET');
		if (!$catid) {
			throw new Exception('Category id not set');
		}
		
		JForm::addFormPath('components/com_smfaq/models/forms');
		JForm::addFieldPath('components/com_smfaq/models/fields');
		$form = JForm::getInstance('question', 'question');
	

		$categories = JCategories::getInstance('SmFaq');
		$category = $categories->get($catid);

		$params = $category->getParams();
		if ($layout = $params->get('category_layout')) {
			$this->setLayout($layout);
		}
		
		JPluginHelper::importPlugin('smfaq');
		$dispatcher = JDispatcher::getInstance();
		$results = $dispatcher->trigger('onPrepareForm', array($form));
		
		// Check for errors encountered while preparing the form.
		if (count($results) && in_array(false, $results, true))
		{
			// Get the last error.
			$error = $dispatcher->getError();
		
			if (!($error instanceof Exception))
			{
				throw new Exception($error);
			}
		}		

		$this->assignRef('form', $form);
		$this->assignRef('params', $params);
		$this->assignRef('category', $category);

		parent::display($tpl);
	}


}

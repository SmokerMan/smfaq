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

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Supports an HTML select list of categories
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldSmCategory extends JFormFieldList
{
	/**
	 * @var		string	The form field type.
	 * @since	1.6
	 */
	public $type = 'SmCategory';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options	= array();
		$extension	= 'com_smfaq';

		$options = JHtml::_('category.options', $extension, array('filter.published' => true));

		$user = JFactory::getUser();

		// отключение категорий к которым нет доступа
		foreach($options as $i => $option)
		{
			if (!$user->authorise('core.edit', $extension.'.category.'.$option->value)) {
				$option->disable = true;
			}
		}

		if (isset($this->element['show_root'])) {
			array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
		}


		return $options;
	}
}
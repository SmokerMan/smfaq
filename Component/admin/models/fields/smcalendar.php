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

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldSmCalendar extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'SmCalendar';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$format = '%d.%m.%Y %H:%M';

		if (($this->value == '0000-00-00 00:00:00') || !$this->value) {
			$this->value = null;
		} else {
			$date = JFactory::getDate($this->value);
			$config = JFactory::getConfig();
			$date->setTimezone(new DateTimeZone($config->get('offset')));
			$this->value = $date->format('d.m.Y H:i', true);
		}



		return JHtml::_('calendar', $this->value, $this->name, $this->id, $format);
	}
}

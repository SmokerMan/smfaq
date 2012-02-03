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
		// Initialize some field attributes.
		$format = $this->element['format'] ? (string) $this->element['format'] : 'd.m.Y H:i';
		$readonly = $this->element['readonly'] ? 'readonly="readonly"' : null;
		
		$date = JFactory::getDate($this->value, 'UTC');
		$config = JFactory::getConfig();
		$date->setTimezone(new DateTimeZone($config->get('offset')));
		
		if ($this->value) {
			$this->value = $date->format($format, true);
		} else {
			$this->value = null;
		}
		
		$jformat = str_replace('d', '%d', $format);
		$jformat = str_replace('m', '%m', $jformat);
		$jformat = str_replace('Y', '%Y', $jformat);
		$jformat = str_replace('H', '%H', $jformat);
		$jformat = str_replace('i', '%M', $jformat);
		
		$time = $date->format('Hi', true);
		
		$html = '<input name="'.$this->name.'" id="'.$this->id.'" value="'.$this->value.'"'. $readonly. ' /><img onmouseover="this.style.cursor=\'pointer\'" src="media/com_smfaq/images/calendar_icon.png" alt="Calendar" id="cal-'.$this->element['name'].'" />';


		$html .= '<script type="text/javascript">
   		Calendar.setup({
	        trigger    : "cal-'.$this->element['name'].'",
	        inputField : "'.$this->id.'",
	        dateFormat : "'.$jformat.'",
	        onSelect : function() { this.hide() },
	        showTime: true,
	        time: "'.$time.'",
    	});
		</script>';


		return $html;
	}
}

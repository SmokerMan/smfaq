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

jimport('joomla.form.formfield');

class JFormFieldIP extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'IP';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		if ($this->value == null) {
			$this->value = $_SERVER['REMOTE_ADDR'];
		}
		$html = '<a id="'.$this->id.'" target="_blank" style="float:left;margin:5px 5px 5px 0;" href="http://www.ripe.net/perl/whois?searchtext='.$this->value.'">'.$this->value.'</a>';
		
		return $html;
	}
}
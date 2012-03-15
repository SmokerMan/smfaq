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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldSmCaptcha extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'SmCaptcha';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$html = '<div class="captcha">';
		$html .= '<img id="smfaq-captcha" src="index.php?option=com_smfaq&amp;task=captcha&amp;format=raw&amp;ac='.rand(1, 100000).'" width="120" height="60" alt="'.JText::_('COM_SMFAQ_FORM_CAPTCHA').'" />';
		$html .= '<span onclick="SmFaq.ReloadCapthca()" class="button">'.JText::_('COM_SMFAQ_CAPTCHA_REFRESH').'</span>';
		$html .= '<input id="captcha" type="text" name="captcha" value="" size="10" />';
		$html .= '<label class="required" for="captcha">'.JText::_('COM_SMFAQ_INPUT_CAPTCHA').'</label> ';
		$html .= '</div>';
			   
		return $html;
	}
}

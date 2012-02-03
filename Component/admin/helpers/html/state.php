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


abstract class JHtmlState
{
	/**
	 * @param	int $value	The featured value
	 * @param	int $i
	 *
	 * @return	string	The anchor tag to toggle featured/unfeatured contacts.
	 * @since	1.6
	 */
	static function state($value = 0)
	{

		$states[] = JHtml::_('select.option','-1', 'COM_SMFAQ_ANSWER_STATE');
		$states[] = JHtml::_('select.option','0', 'COM_SMFAQ_ANSWER_STATE_WAITING');
		$states[] = JHtml::_('select.option','1', 'COM_SMFAQ_ANSWER_STATE_YES');
		$states[] = JHtml::_('select.option','2', 'COM_SMFAQ_ANSWER_STATE_NO');
			
		$html = JHtml::_('select.genericlist', $states, 'filter_state', 'onchange="this.form.submit()"', 'value', 'text', $value, true, true);

		return $html;
	}
	
}

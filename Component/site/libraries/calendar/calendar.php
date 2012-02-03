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

jimport('joomla.application.component.helper');

class SmfaqHelperCalendar
{
	public function setup()
	{
		$lang = JFactory::getLanguage();		
		$local = $lang->getLocale();

		$script = 'Calendar.LANG("'.$local['6'].'", "'.$lang->getName().'", {
        fdow: '.$lang->getFirstDay().',                // first day of week for this locale; 0 = Sunday, 1 = Monday, etc.
        goToday: "'.JText::_('CALENDAR_TODAY').'",
        today: "'.JText::_('CALENDAR_TODAY').'",         // appears in bottom bar
        wk: "'.JText::_('CALENDAR_WK').'",
        weekend: "'.JText::_('CALENDAR_WEEKEND').'",         // 0 = Sunday, 1 = Monday, etc.
        AM: "am",
        PM: "pm",

        mn : [ "'.JText::_('JANUARY').'",
               "'.JText::_('FEBRUARY').'",
               "'.JText::_('MARCH').'",
               "'.JText::_('APRIL').'",
               "'.JText::_('MAY').'",
               "'.JText::_('JUNE').'",
               "'.JText::_('JULY').'",
               "'.JText::_('AUGUST').'",
               "'.JText::_('SEPTEMBER').'",
               "'.JText::_('OCTOBER').'",
               "'.JText::_('NOVEMBER').'",
               "'.JText::_('DECEMBER').'", ],

        smn : [ "'.JText::_('JANUARY_SHORT').'",
               "'.JText::_('FEBRUARY_SHORT').'",
               "'.JText::_('MARCH_SHORT').'",
               "'.JText::_('APRIL_SHORT').'",
               "'.JText::_('MAY_SHORT').'",
               "'.JText::_('JUNE_SHORT').'",
               "'.JText::_('JULY_SHORT').'",
               "'.JText::_('AUGUST_SHORT').'",
               "'.JText::_('SEPTEMBER_SHORT').'",
               "'.JText::_('OCTOBER_SHORT').'",
               "'.JText::_('NOVEMBER_SHORT').'",
               "'.JText::_('DECEMBER_SHORT').'", ],
               
        dn : [ "'.JText::_('SUNDAY').'",
               "'.JText::_('MONDAY').'",
               "'.JText::_('TUESDAY').'",
               "'.JText::_('WEDNESDAY').'",
               "'.JText::_('THURSDAY').'",
               "'.JText::_('FRIDAY').'",
               "'.JText::_('SATURDAY').'",
               "'.JText::_('SUNDAY').'" ],

        sdn : [ "'.JText::_('SUN').'",
                "'.JText::_('MON').'",
                "'.JText::_('TUE').'",
                "'.JText::_('WED').'",
                "'.JText::_('THU').'",
                "'.JText::_('FRI').'",
                "'.JText::_('SAT').'",
                "'.JText::_('SUN').'" ]
		});';
		
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($script);

		return ;
	}
}
?>

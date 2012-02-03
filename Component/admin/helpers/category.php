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

// Component Helper

class SmfaqHelperCategory
{
	function onPrepareForm($form)
	{
		$doc = JFactory::getDocument();
		$script = "	function hideedit() {
			var t = document.getElementById('jform_rules_core.edit_1');
			t.value = '';
			t.parentNode.parentNode.style.display = 'none';	
			}
		window.onload = hideedit;";
		$doc->addScriptDeclaration($script);
		$doc->addStyleDeclaration('.icon-48-smfaq-category-edit {background: url("../media/com_smfaq/images/icon-48-smfaq-category.png") no-repeat 10px 0 !important}');
		$doc->addStyleDeclaration('.icon-48-smfaq-category-add {background: url("../media/com_smfaq/images/icon-48-smfaq-category.png") no-repeat 10px 0 !important}');
		
		$form->removeField('note');
		


		return;
	}
}
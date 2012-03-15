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
defined('_JEXEC') or die('@-_-@'); ?>
<?php 
$user = JFactory::getUser();

if ($user->guest) {
	$created_by = $this->escape(JRequest::getString(JApplication::getHash('com_smfaq.name'),'','cookie', null));
	$created_by_email = $this->escape(JRequest::getString(JApplication::getHash('com_smfaq.email'),'','cookie', null));
	
	//установка значений из куки
	if ($created_by) {
		$this->form->setValue('created_by', null, $created_by);
	}
	if ($created_by_email) {
		$this->form->setValue('created_by_email', null, $created_by_email);;
	}
	
}

?>
<form action="#" name="smfaq-form" id="smfaq-form" method="post" >

	<div class="titleform"><?php echo JText::_('COM_SMFAQ_TITLE_FORM'); ?></div>
	<?php if ($user->guest) { 
		echo $this->form->getLabel('created_by'); 
		echo $this->form->getInput('created_by'); 
		if ($this->params->get('show_email', 0) != 2) {
			echo $this->form->getLabel('created_by_email');
			echo $this->form->getInput('created_by_email');
		}
		foreach ($this->form->getFieldset('details') as $field) {
			echo $field->label;
			echo $field->input; 
		}		
		echo $this->form->getLabel('question');
		echo $this->form->getInput('question');
		if ($this->params->get('show_char_count', 1)) {
			echo '<div class="count">';
			echo '<span>'.JText::sprintf('COM_SMFAQ_COUNTER',  '<span id="smfaq-counter">'.$this->params->get('max_length_question').'</span>').'</span>';
			echo '</div>';
		} else {
			echo '<div class="clr"></div>';
		}
		if (($this->params->get('show_captcha', 1) == 1 && $user->guest) || ($this->params->get('show_captcha') == 2)) {
			echo $this->form->getInput('captcha');
		}
		if ($this->params->get('show_send_mail') && $this->params->get('show_email') != 2) {
			echo '<div class="ch_email">';
			echo $this->form->getInput('answer_email');
			echo $this->form->getLabel('answer_email');
			echo '</div>';
		}
	} ?>
    <div class="clr"></div>
	<input type="button" class="button" onclick="SmFaq.sendform(this.form)" value="<?php echo JText::_('COM_SMFAQ_SEND'); ?>" />
	<?php if (!$this->params->get('show_form', 0)) : ?>
	    <input type="button" class="button" onclick="SmFaq.showform(false)" value="<?php echo JText::_('COM_SMFAQ_CLOSE'); ?>" />
	<?php endif; ?>	  	
  <input type="hidden" name="count" value="<?php echo $this->params->get('max_length_question'); ?>" />
  <input type="hidden" name="catid" value="<?php echo $this->category->id; ?>" />
  <input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>"  />	 
</form>
<div class="clr"></div>

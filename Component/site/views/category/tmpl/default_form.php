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
defined('_JEXEC') or die('@-_-@'); ?>
<?php 
$user = JFactory::getUser();

if ($user->guest) {
	$created_by = JRequest::getString(JApplication::getHash('com_smfaq.name'),'','cookie', null);
	$created_by_email = JRequest::getString(JApplication::getHash('com_smfaq.email'),'','cookie', null);
}

?>
<form action="#" name="smfaq-form" id="smfaq-form" method="post" >
  	<div class="titleform"><?php echo JText::_('COM_SMFAQ_TITLE_FORM'); ?></div>
    <?php if ($user->guest) : ?>
	    <label class="required" for="created_by"><?php echo JText::_('COM_SMFAQ_NAME'); ?></label>
	    <input id="created_by" type="text" name="created_by" size="40" maxlength="50" 
	    	value="<?php echo $this->escape($created_by); ?>" />
	    <?php if ($this->params->get('show_email', 0) != 2) : ?>
		    <label class="<?php echo $this->params->get('show_email') ? '' : 'required'; ?>" for="created_by_email"><?php echo JText::_('COM_SMFAQ_EMAIL'); ?></label>
		    <input id="created_by_email" type="text" name="created_by_email" size="40" maxlength="50"  
		    	value="<?php echo $this->escape($created_by_email); ?>" />
		<?php endif; ?>
    <?php endif;  ?>
    <label class="required" for="question"><?php echo JText::_('COM_SMFAQ_QUESTION_FORM'); ?></label>
	<textarea id="question" rows="5" cols="10" onkeyup="SmFaq.Count(this.form)" name="question"></textarea>
    <div class="count">
    <?php if ($this->params->get('show_char_count', 1)) : ?>
		<span><?php echo JText::sprintf('COM_SMFAQ_COUNTER', '<span id="smfaq-counter">'.$this->params->get('max_length_question').'</span>'); ?></span>   
    <?php endif; ?>
    </div>
    <?php if (($this->params->get('show_captcha', 1) == 1 && $user->guest) || ($this->params->get('show_captcha') == 2)) : ?>
      	<div class="captcha">
		    <img id="smfaq-captcha" src="index.php?option=com_smfaq&amp;task=captcha&amp;format=row&amp;ac=<?php echo rand(1, 100000)?>" width="120" height="60" alt="<?php echo JText::_('COM_SMFAQ_FORM_CAPTCHA'); ?>" />
		    <span onclick="SmFaq.ReloadCapthca()" class="button"><?php echo JText::_('COM_SMFAQ_CAPTCHA_REFRESH'); ?></span>
		    <input id="captcha" type="text" name="captcha" value="" size="10" />
		    <label class="required" for="captcha"><?php echo JText::_('COM_SMFAQ_INPUT_CAPTCHA'); ?></label> 
	   </div>
    <?php endif; ?>
    <?php if ($this->params->get('show_send_mail') && $this->params->get('show_email') != 2) : ?>
	    <div class="ch_email">
		    <input id="answer_email"  type="checkbox" name="answer_email" />
		    <label for="answer_email"><?php echo JText::_('COM_SMFAQ_SEND_MAIL'); ?></label> 
	    </div>
    <?php endif; ?>
    <div class="clr"></div>
	<input type="button" class="button" onclick="SmFaq.sendform(this.form)" value="<?php echo JText::_('COM_SMFAQ_SEND'); ?>" />
	<?php if (!$this->params->get('show_form', 0)) : ?>
	    <input type="button" class="button" onclick="SmFaq.showform(false)" value="<?php echo JText::_('COM_SMFAQ_CLOSE'); ?>" />
	<?php endif; ?>	  
  <input type="hidden" name="count" value="<?php echo $this->params->get('max_length_question'); ?>" />
  <input type="hidden" name="catid" value="<?php echo $this->category->id; ?>" />
  <input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>"  />
<div class="clr"></div>
</form>

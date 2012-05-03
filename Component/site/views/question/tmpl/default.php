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
//Created date and author question

$author = $this->params->get('show_created_date') || $this->params->get('show_created_by');
$ans = $this->params->get('show_answer_created_by') || $this->params->get('show_answer_created_date');
$canEdit = $this->user->authorise('core.edit', 'com_smfaq.category.'.$this->item->catid);
?>

<div id="smfaq">
<?php if ($this->params->get('show_print', 0)) : ?>
	<?php $print_link = JRoute::_(SmfaqHelperRoute::getQuestionRoute($this->item->catid, $this->item->id).'&tmpl=component&print=1'); ?>
	<div class="smfaq-print"><?php echo JHtml::_('link', $print_link, JText::_('COM_SMFAQ_PRINT_VIEW'), array('rel' => 'nofollow', 'target' => '_blank'));?></div>
	<div style="clear: both"></div>
<?php endif ?>
<h1 class="single_question"><?php echo $this->item->question; ?></h1>
<div id="a<?php echo $this->item->id; ?>" class="answer">
<?php if ($author || $ans) : ?>
	<div class="info">
		<?php if ($author) : ?>
			<div>
	           <?php if ($this->params->get('show_created_by')) : ?>
	              <span class="author"><?php echo JText::sprintf('COM_SMFAQ_CREATED_BY', $this->escape($this->item->created_by)); ?></span>
	           <?php endif ?>
	           <?php if ($this->params->get('show_created_date')) : ?>
	              <span class="date"><?php echo JText::sprintf('COM_SMFAQ_CREATED', JHTML::_('date', $this->item->created, $this->params->get('date_format'))); ?></span>
	           <?php endif ?>
	        </div>
	   <?php endif ?>   
	   <?php if ($ans) : ?>
			<div>
			<?php if ($this->params->get('show_answer_created_by')) : ?>
				<span class="ans-author"><?php echo  JText::sprintf('COM_SMFAQ_ANSWER_BY', $this->escape($this->item->answer_created_by)); ?></span>
            <?php endif ?> 
     		<?php if ($this->params->get('show_answer_created_date')) : ?>
                <span class="ans-date"><?php echo JText::sprintf('COM_SMFAQ_ANSWER_CREATED', JHTML::_('date', $this->item->answer_created, $this->params->get('date_format'))); ?></span>
            <?php endif ?>
            </div>
	  <?php endif ?>
	</div>
<?php endif ?>
<?php echo $this->item->answer; ?>
<?php if ($canEdit) : ?>
	<div class="clr"></div>
	<a class="button" href="<?php echo JRoute::_('index.php?option=com_smfaq&task=edit.edit&catid='.$this->item->catid.'&id='.$this->item->id); ?>"><?php echo JText::_('COM_SMFAQ_EDIT'); ?></a>
	<span><?php echo JText::sprintf('COM_SMFAQ_VOTE_STATE', $this->item->vote_yes, $this->item->vote_no, $this->item->comments); ?></span>
<?php endif; ?>	

<?php if ($this->params->get('show_poll', 1)) : ?>
    <form action="#" name="vote<?php echo $this->item->id; ?>" class="vote">
    	<?php echo JText::_('COM_SMFAQ_VOTE_QUESTION'); ?>
        	<input type="radio" name="vote_question" onclick="SmFaq.Vote(this.form, value, true)" value="1" /> <?php echo JText::_('COM_SMFAQ_YES'); ?>
            <input type="radio" name="vote_question" onclick="SmFaq.Vote(this.form, value, true)" value="0" /> <?php echo JText::_('COM_SMFAQ_NO'); ?>
            <input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
            <input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>" />
     </form>
<?php endif; ?> 
</div>
<div class="back-link">
	<a href="<?php echo JRoute::_(SmfaqHelperRoute::getCategoryRoute($this->item->catid)); ?>"><?php echo JText::_('COM_SMFAQ_RETURN_TO_CATEGORY'); ?></a>
</div>
</div>


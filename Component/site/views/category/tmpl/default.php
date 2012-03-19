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

$canCreate	= $this->user->authorise('core.create', 'com_smfaq.category.'.$this->category->id);
$canEdit	= $this->user->authorise('core.edit', 'com_smfaq.category.'.$this->category->id);

switch ($this->params->get('open_question', 0)) {
	default:
	case 0:
		$style = 'display:none;height:1px;';
	break;
	case 1:
		$style = 'display:block;';
	break;
	case 2:
		$style = 'display:block;';
		$unclick = true;
	break;		
	case 3:
		$as_link = true;
	break;	
}

?>

<?php if ($this->params->get('show_page_title')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>
<?php if ( $this->params->get( 'show_desc', 1 ) ) : ?>
<div class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php echo $this->category->description; ?>
</div>
<?php endif; ?>
<?php // Вывод подкатегорий ?>
<?php if (!empty($this->children[$this->category->id]) && $this->maxLevel != 0) : ?>
<div class="cat-children">
	<h3><?php echo JText::_('COM_SMFAQ_SUBCATEGORIES') ; ?></h3>
	<?php echo $this->loadTemplate('children'); ?>
</div>
<?php endif; ?>
<div id="smfaq" class="questions">
<?php // Вывод формы добавления вопроса
if ($canCreate && !$canEdit) :
	if ($this->params->get('show_form', 0)) : ?>
		<div><?php echo $this->loadTemplate('form'); ?></div>
	<?php else : ?>
		<span onclick="SmFaq.showform(true,this)" class="button"><?php echo JText::_('COM_SMFAQ_NEW_QUESTION'); ?></span>
<?php endif; ?>
<?php elseif ($canEdit) : ?>
	<a href="<?php echo JRoute::_('index.php?option=com_smfaq&amp;task=edit.add&amp;catid='.$this->category->id); ?>" class="button">
	<?php echo JText::_('COM_SMFAQ_NEW_QUESTION'); ?></a>
	<span class="button" style="margin-left:10px;" onclick="SmFaq.unpublished(<?php echo JRequest::getInt('Itemid', null); ?>)">
	<?php echo JText::_('COM_SMFAQ_SHOW_NO_ANSWER_QUESTIONS'); ?></span>
	<div id="smfaq-unpub"></div>
<?php endif; ?>
<?php if ($this->params->get('show_print', 0)) : ?>
	<?php $print_link = JRoute::_(SmfaqHelperRoute::getCategoryRoute($this->category->id).'&tmpl=component&print=1'); ?>
	<div class="smfaq-print"><?php echo JHtml::_('link', $print_link, JText::_('COM_SMFAQ_PRINT_VIEW'), array('rel' => 'nofollow', 'target' => '_blank'));?></div>
	<div style="clear: both"></div>
<?php endif ?>

<?php // Вывод вопросов ?>
<?php foreach ($this->items as $item) : ?>
			<?php if (isset($as_link)) : 
				$link = JRoute::_(SmfaqHelperRoute::getQuestionRoute($this->category->id, $item->id));
				?>
				<div class="question-link">
				<a href="<?php echo $link; ?>"><?php echo $this->escape($item->question); ?></a>
				</div>
			<?php continue; ?>
			<?php endif; ?>
			<?php if (!isset($unclick)) :
				$onclick = 'onclick="SmFaq.answer(\''.$item->id.'\');"';
				else :
				$onclick = '';
				endif; ?>
			<div id="q<?php echo $item->id; ?>" class="question" <?php echo $onclick; ?>>
			<a name="<?php echo 'p'.$item->id; ?>" class="img"></a>
			<?php echo $this->escape($item->question); ?>
            </div>
            <div id="a<?php echo $item->id; ?>" class="answer" style="<?php echo $style; ?>"><div id="ac<?php echo $item->id; ?>" class="answer_content" style="top: 0px;">
            <?php //Created date and author question
            	$author = $this->params->get('show_created_date') || $this->params->get('show_created_by');
            	$ans = $this->params->get('show_answer_created_by') || $this->params->get('show_answer_created_date');
            ?>
			<?php if ($author || $ans) : ?>
				<div class="info">
				<?php if ($author) : ?>
					<div>
		            	<?php if ($this->params->get( 'show_created_by')) : ?>
		                	<span class="author"><?php echo JText::sprintf('COM_SMFAQ_CREATED_BY', $this->escape($item->created_by)); ?></span>
		                <?php endif ?>
		            	<?php if ($this->params->get( 'show_created_date')) : ?>
		                	<span class="date"><?php echo JText::sprintf('COM_SMFAQ_CREATED', JHTML::_('date', $item->created, $this->params->get('date_format'))); ?></span>
		                <?php endif ?>
		            </div>
	            <?php endif ?>   
	            <?php if ($ans) : ?>
	            	<div>
		                <?php if ($this->params->get('show_answer_created_by')) : ?>
		                    <span class="ans-author"><?php echo  JText::sprintf('COM_SMFAQ_ANSWER_BY', $this->escape($item->answer_created_by)); ?></span>
		                <?php endif ?> 
		     			<?php if ($this->params->get('show_answer_created_date')) : ?>
		                    <span class="ans-date"><?php echo JText::sprintf('COM_SMFAQ_ANSWER_CREATED', JHTML::_('date', $item->answer_created, $this->params->get('date_format'))); ?></span>
		                <?php endif ?>
		                
	                </div>
	            <?php endif ?>
	            </div>
	        <?php endif ?>   
            <div class="clr"></div>
           <?php 
            if ($this->params->get('content_plugins')) {
            	 $item->answer = JHtml::_('content.prepare', $item->answer);
            }
            ?>  
			<?php echo $item->answer; ?> 
            <?php if ($canEdit) : ?> 
            	<div class="clr"></div>
				<a class="button" href="<?php echo JRoute::_('index.php?option=com_smfaq&task=edit.edit&catid='.$item->catid.'&id='.$item->id); ?>"><?php echo JText::_('COM_SMFAQ_EDIT'); ?></a>
				<span><?php echo JText::sprintf('COM_SMFAQ_VOTE_STATE', $item->vote_yes, $item->vote_no, $item->comments); ?></span>
			<?php endif; ?>	
			<?php // Вывод опроса ?>
            <?php if ($this->params->get('show_poll', 1)) : ?>
	            <form action="#" name="vote<?php echo $item->id; ?>" class="vote">
	                <?php echo JText::_('COM_SMFAQ_VOTE_QUESTION'); ?>
	                    <input type="radio" name="vote_question" onclick="SmFaq.Vote(this.form, value)" value="1" /> <?php echo JText::_('COM_SMFAQ_YES'); ?>
	                    <input type="radio" name="vote_question" onclick="SmFaq.Vote(this.form, value)" value="0" /> <?php echo JText::_('COM_SMFAQ_NO'); ?>
	                    <input type="hidden" name="id" value="<?php echo $item->id ?>" />
	                    <input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>" />
	            </form>
            <?php endif; ?> 
            </div>
            </div>
<?php endforeach; ?>
<?php //Пагинация ?>
<?php if ($this->pagination->get('pages.total') > 1) : ?>
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>
</div>


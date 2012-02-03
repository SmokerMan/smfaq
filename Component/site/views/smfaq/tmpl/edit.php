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


?>
<script type="text/javascript">
<!--
if (typeof(SmFaq) === 'undefined') {var SmFaq = {};}
SmFaq.ajax = function(){
	  var xmlhttp;
	  try {
	    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	  } catch (e) {
	    try {
	      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	    } catch (E) {
	      xmlhttp = false;
	    }
	  }
	  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	    xmlhttp = new XMLHttpRequest();
	  }
	  return xmlhttp;
}

SmFaq.submitform = function(task, f) {
	if (task == 'save') {
		if (!this.check(f)) {
			alert(this.msg);
			delete this.msg;
			return;
		}
	}
	f.task.value = 'SmFaq.'+task;
	f.submit();
}
SmFaq.check = function (f) {
	var msg;
	if (f.jform_question.value.length < 5) {
		this.msg = '<?php echo JText::_('COM_SMFAQ_ERROR_QUESTION_VALUE'); ?>';
		return false;
	}
	if (f.jform_created.value.length < 5) {
		this.msg = '<?php echo JText::_('COM_SMFAQ_ERROR_CREATED_VALUE'); ?>';
		return false;
	}
	if (f.jform_answer_created.value.length < 5 && f.jform_published.value == 1) {
		this.msg = '<?php echo JText::_('COM_SMFAQ_ERROR_PUBLISH'); ?>';
		return false;
	}
	return true	
}
SmFaq.delComment = function(id, e) {
	if (!confirm('<?php echo JText::_('COM_SMFAQ_DEL_COMMENT_CONFIRM'); ?>')) return;
	e.innerHTML = '';
	e.className='loader'
	var req = SmFaq.ajax();
	req.onreadystatechange = function() {  
        if (req.readyState == 4) { 
            if(req.status == 200) {
            	e.className='';
            	SmFaq.e = e.parentNode.parentNode;
             	for (var i = 0; i < SmFaq.e.childNodes.length; ++i) {
             	    	while(SmFaq.e.childNodes[i]){
             	    		SmFaq.e.removeChild(SmFaq.e.childNodes[i]);
             	    	}
            	}
            	var f = document.createElement('td'); 
            	f.colSpan="3";
            	f.className='deleted';
            	SmFaq.e.appendChild(f);
            	SmFaq.e.firstChild.innerHTML = req.responseText;
            	setTimeout("SmFaq.e.parentNode.removeChild(SmFaq.e);",4000); 
            }

        }
 
    }
    req.open('GET', 'index.php?option=com_SmFaq&task=admin.delcomment&format=raw&id='+id);  
    req.send(null);  
	
}
//-->
</script>
<div id="smfaq">
<?php if ($this->form->getValue('id')) : ?>
<p class="notice"><?php echo JText::_('COM_SMFAQ_EDIT_NOTICE')?></p>
<?php endif; ?>
<form action="<?php echo JRoute::_('index.php?'.$this->url->getQuery()); ?>" method="post" name="adminForm">
	<div class="question">
       <fieldset>
           <legend><?php echo JText::_('COM_SMFAQ_FIELD_QUESTION_LABEL'); ?></legend>
           <?php echo $this->form->getInput('question'); ?>
       </fieldset>
       <fieldset>
           <legend><?php echo JText::_('COM_SMFAQ_FIELD_ANSWER_LABEL'); ?></legend>
           <?php echo $this->form->getInput('answer'); ?>
       </fieldset>
        
    </div>
    <div class="details">
 		<fieldset>
 			<legend><?php echo JText::_('COM_SMFAQ_DETAILS'); ?></legend>
 			 <?php foreach ($this->form->getFieldset('details') as $field) : ?>
				<div><?php echo $field->label; ?><?php echo $field->input; ?></div>
			 <?php endforeach; ?> 
		</fieldset>
    </div>
    <input type="button" class="button" onclick="SmFaq.submitform('save', this.form);" value="<?php echo JText::_('COM_SmFaq_SAVE'); ?>" />
	<input type="button" class="button" onclick="SmFaq.submitform('cancel', this.form);" value="<?php echo JText::_('COM_SmFaq_CLOSE'); ?>" />
    
	<?php 
	if ($this->comments) : ?>
		<div class="title"><?php echo JText::_('COM_SMFAQ_COMMENTS'); ?></div>
	 	<table class="comments">
	 		<thead>
	 		<tr>
	 			<th width="20%"><?php echo JText::_('COM_SMFAQ_DATE'); ?></th>
	 			<th width="75%"><?php echo JText::_('COM_SMFAQ_COMMENT'); ?></th>
	 			<th width="5%"><?php echo JText::_('COM_SMFAQ_DEL'); ?></th>
	 		</tr>
	 		</thead>
	 		<tbody>
			<?php 
			$i = 0;
			foreach ($this->comments as $comment) : ?>
			<tr class="row<?php echo $i%2; ?>">
			<td class="center">
				<?php echo JHtml::date($comment->created, 'd.m.Y H:i'); ?>
			</td>
			<td>
				<?php echo $this->escape($comment->comment); ?>
			</td>
			<td class="center">
				<div title="<?php echo JText::_('COM_SMFAQ_DEL_COMMENT_TITLE'); ?>" class="del-comment" onclick="SmFaq.delComment(<?php echo $comment->id; ?>,this)"></div>
			</td>
			</tr>
			<?php $i++; ?>
			<?php endforeach; ?>	 		
	 		</tbody>
		</table>
	<?php endif; ?>    

<input type="hidden" name="task" value="" /> 
<?php echo JHTML::_('form.token'); ?>
</form>
</div>
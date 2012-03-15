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

<script type="text/javascript">
//<![CDATA[
	Joomla.submitbutton = function(task)
	{
		if (task == 'smfaq.cancel' || document.formvalidator.isValid(document.id('smfaq-form'))) {
			<?php echo $this->form->getField('answer')->save(); ?>
			Joomla.submitform(task, document.getElementById('smfaq-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

	function SMFAQ_highlight(el) {
		el = $(el);
		var highlight = new Fx.Morph($(el), {
		    duration: 'long',
		    transition: Fx.Transitions.Sine.easeOut,
		    onComplete: function(){
		    	el.destroy();
		    }
		})
		highlight.start({
			'opacity': 0
		});
	}

	function delcomment(id, s) {
		s.className='sm-loader';
		row = 'comment-'+id;
	    new Request({
	        url: 'index.php?option=com_smfaq&task=smfaq.delcomment&format=raw',
	        onSuccess: function(responseText, responseXML) {
            	s.set('class','');
            	s.set('html', responseText);
             	setTimeout("SMFAQ_highlight(row);",2000); 
	        }
	    }).send('id='+id);	
 	}

	function resetvote(id, b) {
		var l = new Element('div', {
		    'class': 'sm-loader2'
			}
		);
		l.inject(b, 'after');
	    new Request({
	        url: 'index.php?option=com_smfaq&task=smfaq.resetvote&format=raw',
	        onSuccess: function(responseText, responseXML) {
	        	$('smfaq-votes').set('html', responseText);
	        }
	    }).send('id='+id);	
	}
// ]]>	
</script>
<form action="<?php echo JRoute::_('index.php?option=com_smfaq&layout=edit&id='. (int) $this->form->getValue('id')); ?>" method="post" name="adminForm" id="smfaq-form">
	<div style="width:70%;float:left">
       <fieldset class="smfaq">
           <legend> <?php echo $this->form->getLabel('question'); ?></legend>
           <?php echo $this->form->getInput('question'); ?>
       </fieldset>
       <fieldset class="smfaq">
           <legend> <?php echo $this->form->getLabel('answer'); ?></legend>
           <?php echo $this->form->getInput('answer'); ?>
       </fieldset>
        
    </div>
    <div style="width:28%;float:right">
    	<?php echo JHtml::_('sliders.start','content-sliders-'.$this->form->getValue('id'), array('useCookie'=>1)); ?>
    	
    	<?php echo JHtml::_('sliders.panel',JText::_('COM_SMFAQ_DETAILS'), 'question-details'); ?>
 		<fieldset class="panelform">
 			<ul class="adminformlist">
 			<?php foreach ($this->form->getFieldset('details') as $field) : ?>
				<li><?php echo $field->label; ?><?php echo $field->input; ?></li>
			<?php endforeach; ?> 
			</ul>			
		</fieldset>

		<?php if ($this->form->getValue('id')) : ?>
			<?php echo JHtml::_('sliders.panel',JText::_('COM_SMFAQ_FIELD_VOTE_LABEL'), 'vote-details'); ?>
			<div>
				<?php echo $this->form->getInput('vote'); ?>
			</div>
		<?php endif;?>
				
		<?php echo JHtml::_('sliders.panel',JText::_('COM_SMFAQ_QUESTION_SEO_LABEL'), 'seo-details'); ?>
 		<fieldset class="panelform">
 			<?php echo JText::_('COM_SMFAQ_QUESTION_SEO_DESC'); ?>
 			<ul class="adminformlist">
 			<?php foreach ($this->form->getFieldset('seo') as $field) : ?>
				<li><?php echo $field->label; ?><?php echo $field->input; ?></li>
			<?php endforeach; ?> 
			</ul>			
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
    </div>
    <div style="clear:both;">
    <fieldset>
    	<legend> <?php echo $this->form->getLabel('comments'); ?></legend>
    	<?php echo $this->form->getInput('comments'); ?>
    </fieldset>
   
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>


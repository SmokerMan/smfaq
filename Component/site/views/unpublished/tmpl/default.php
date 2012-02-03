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
<?php if ($this->items) : 
$Itemid = JRequest::getInt('Itemid', null);
?>
<div>
	<ul>
	<?php foreach ($this->items as $item) : 
	$link = 'index.php?option=com_smfaq&task=smfaq.edit&id='.$item->id.'&catid='.$item->catid.'&Itemid='.$Itemid;
	?>
	<li>
	<div>
		<span class="author"><?php echo JText::sprintf('COM_SMFAQ_CREATED_BY', $this->escape($item->created_by)); ?></span>
		<span class="date"><?php echo JText::sprintf('COM_SMFAQ_CREATED', JHTML::_('date', $item->created, 'd-m-Y H:i')); ?></span>
	</div>
	<div>
		<a title="<?php echo JText::_('COM_SMFAQ_EDIT')?>" href="<?php echo $link; ?>">
		<?php echo $this->escape($item->question); ?></a>
	</div>
	</li>	
	<?php endforeach;;?>
	</ul>
</div>
<?php endif;?>

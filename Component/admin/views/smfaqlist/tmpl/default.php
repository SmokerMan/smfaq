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
// Подключаем Тулбар
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
$user	= JFactory::getUser();

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';
$n = count($this->items);

?>
<form action="<?php echo JRoute::_('index.php?option=com_smfaq'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_SMFAQ_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select id="filter_published" name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false, 'all' => false)), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
			
			<?php echo JHtml::_('state.state', $this->state->get('filter.state')); ?>
			
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_smfaq'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>
		</div>
	</fieldset>
	
	<table class="adminlist">
		<thead>
		<tr>
        <th width="5">
        	№
        </th>
        <th width="20">
        	<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
        </th>                     
        <th width="40%">
        	<?php echo JHtml::_('grid.sort',  'COM_SMFAQ_FIELD_QUESTION_LABEL', 'a.question', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
        	<?php echo JHtml::_('grid.sort',  'COM_SMFAQ_FIELD_CREATED_LABEL', 'a.created', $listDirn, $listOrder); ?>
        </th>
        <th>
        	<?php echo JHtml::_('grid.sort',  'COM_SMFAQ_FIELD_AUTHOR_LABEL', 'a.created_by', $listDirn, $listOrder); ?>
        </th>
        
        <th width="5%">
			<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.published', $listDirn, $listOrder); ?>
		</th>
		<th width="10%">
			<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
			<?php if ($saveOrder) :?>
				<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'smfaqlist.saveorder'); ?>
			<?php endif; ?>
		</th>
		
		<th width="10%">
			<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'category', $listDirn, $listOrder); ?>
		</th>
		<th width="10%">
			<?php echo JHtml::_('grid.sort', 'COM_SMFAQ_FIELD_ANSWER_STATE_LABEL', 'a.answer_state', $listDirn, $listOrder); ?>
		</th>
		
		<th width="10%">
			<?php echo JText::_('COM_SMFAQ_VOTE_LABEL'); ?>
		</th>
		
		
        <th width="5">
        	<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
        </th>

		</tr>
		</thead>
		<tbody>
		<?php if ($this->items): ?>
			<?php foreach($this->items as $i => $item): 
				$ordering	= ($listOrder == 'a.ordering');
				$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
				$canEdit	= $user->authorise('core.edit',	'com_smfaq.category.'.$item->catid);
				?>
		        <tr class="row<?php echo $i % 2; ?>">
	                <td>
	                	<?php echo $i+1; ?>
	                </td>
	                <td class="center">
	                	<?php echo JHtml::_('grid.id', $i, $item->id); ?>
	                </td>
	                <td>
	                	<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, '', $item->checked_out_time, 'SMFAQlist.', $canCheckin); ?>
						<?php endif; ?>
						<?php if ($canEdit) : ?>
		                	<a href="<?php echo JRoute::_('index.php?option=com_smfaq&task=smfaq.edit&id=' . $item->id); ?>">
		                    	<?php echo $this->escape($item->question); ?>
		                    </a>
						<?php else : ?>
								<?php echo $this->escape($item->question); ?>
						<?php endif; ?>
	                    
	                </td>
					<td class="center">
						<?php echo JHTML::_('date',$item->created, JText::_('COM_SMFAQ_DATE_FORMAT')); ?>
					</td>
					<td class="center">
					<?php if ($item->user_id) :?>
						<strong><?php echo $this->escape($item->created_by); ?></strong>
					<?php else :?>
						<?php echo $this->escape($item->created_by); ?>
					<?php endif; ?>
						<br />
						<a title="<?php echo JText::_('COM_SMFAQ_LOOK_IP'); ?>" href="http://www.ripe.net/perl/whois?searchtext=<?php echo $this->escape($item->ip); ?>" target="_blank"><?php echo $this->escape($item->ip); ?></a>
					</td>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'smfaqlist.', true, 'cb', null, null); ?>
					</td>
					<td class="order">
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid),'smfaqlist.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $n, ($item->catid == @$this->items[$i+1]->catid), 'smfaqlist.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid),'smfaqlist.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $n, ($item->catid == @$this->items[$i+1]->catid), 'smfaqlist.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					</td>
					<td align="center">
						<?php echo $item->category; ?>
					</td>
					<td class="center">
						<?php switch ($item->answer_state) {
							default:
							case 0: 
								echo '<span style="color:#FF5F5F;">' . JText::_('COM_SMFAQ_ANSWER_STATE_WAITING') . '</span>';
								break;
							case 1:
								echo '<span style="color:#3FFF7D;">' . JText::_('COM_SMFAQ_ANSWER_STATE_YES') . '</span>';
								break;
							case 2:
								echo '<span style="color:#4F7EFF;">' . JText::_('COM_SMFAQ_ANSWER_STATE_NO') . '</span>';
								break;
						}?>
					</td>

	                <td class="center">
	                	<?php if ($item->vote_yes || $item->vote_no): ?>
	                	<span class="vote-yes-smfaq"><?php echo $item->vote_yes; ?></span>
	                	<span class="vote-no-smfaq"><?php echo $item->vote_no; ?></span>
	                	<?php else: ?>
	                	-
	                	<?php endif; ?>
	                	<?php if ($item->comments) : ?>
	                	<div><?php echo JText::sprintf('COM_SMFAQ_COMMENTS', $item->comments);?></div>
	                	<?php endif; ?>
 	                </td>
					
	                <td>
	                	<?php echo $item->id; ?>
	                </td>
						                
		        </tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
			<td colspan="11" align="center"><strong><?php echo JText::_('COM_SMFAQ_NON_ITEMS'); ?></strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
        	<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />		
		<?php echo JHTML::_('form.token'); ?>
	</div>
</form>

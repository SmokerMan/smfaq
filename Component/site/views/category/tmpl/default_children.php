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
$class = ' class="first"';
if (count($this->children[$this->category->id]) > 0) :
?>
<ul>
<?php foreach($this->children[$this->category->id] as $id => $child) : ?>
	<?php
	//var_dump($child);
	if($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) :
	//$class = isset($this->children[$this->category->id][$id + 1]) == false ? null : 'class = "last"';
	if(!isset($this->children[$this->category->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	
	?>
	<li <?php echo $class; ?>>
			<?php $class = ''; ?>
			<span class="item-title"><a href="<?php echo JRoute::_(SmFaqHelperRoute::getCategoryRoute($child->id));?>"><?php echo $this->escape($child->title); ?></a></span>
			<?php if ($this->params->get('show_subcat_desc') && $child->description) :?>
				<div class="category-desc">
					<?php echo JHtml::_('content.prepare', $child->description); ?>
				</div>
            <?php endif; ?>
 			<?php if ($this->params->get('show_cat_num_links_cat')) :?>
				<dl>
					<dt>
						<?php echo JText::_('COM_SMFAQ_NUM_CAT_ITEMS') ; ?>
					</dt>
					<dd>
						<?php echo $child->getNumItems(true); ?>
					</dd>
				</dl>
			<?php endif; ?>
			<?php if(count($child->getChildren()) > 0 ) :
				$this->children[$child->id] = $child->getChildren();
				$this->category = $child;
				$this->maxLevel--;
				echo $this->loadTemplate('children');
				$this->category = $child->getParent();
				$this->maxLevel++;
			endif; ?>
		</li>
	<?php endif; ?>
	<?php endforeach; ?>
	</ul>
<?php endif;
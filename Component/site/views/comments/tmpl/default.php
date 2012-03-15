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
<div class="title"><?php echo JText::_('COM_SMFAQ_COMMENT_TITLE'); ?></div>
<textarea name="comment"></textarea><br /><br />
<input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>" />
<input type="hidden" name="id" value="<?php echo $this->id; ?>" />
<input type="button" class="button" onclick="SmFaq.Comment(this.form)" value="<?php echo JText::_('COM_SMFAQ_SEND'); ?>" />
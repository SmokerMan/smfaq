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
?>
<div style="width:400px;margin:auto;background:#FFFFFF;padding:20px;-webkit-border-radius:20px;-moz-border-radius:20px;border-radius:20px;border:1px solid #157BAF">
<img align="left" alt="SM FAQ" src="<?php echo JURI::root(); ?>/media/com_smfaq/images/smfaq-logo.png" />
<div style="text-align:center;padding-top:40px;">
<p style="font-size:25px;">
SM FAQ
</p>
<p>
<?php echo JText::sprintf('COM_SMFAQ_VERSION',$this->data['version']); ?>
</p>
</div>
<div style="clear:both;text-align:center;">
<?php echo JText::_($this->data['description']); ?>
</div>
<div style="margin-top:20px;">
<p><?php echo JText::_('COM_SMFAQ_TRANSLATORS'); ?></p>
<ul>
	<li>English - SanyaBerkut</li>
	<li>Ukrain - <a target="_blank" href="http://www.free-lance.ru/users/Mark29/">Mark29</a></li>
</ul>
<p><?php echo JText::_('COM_SMFAQ_DESIGN_LOGO_ICON');  ?> - <a target="_blank" href="http://www.stepindesign.ru/">Alexander Stepin</a></p>
<p> 
<?php echo JText::_('COM_SMFAQ_LICENSE'); ?>
</p>
<p>
<?php $date = JFactory::getDate()->format('Y') > '2009' ? '2009 - '. JFactory::getDate()->format('Y') : '2009'; ?>
&copy; <?php echo $date; ?> <?php echo $this->data['author']; ?>
 | <a target="_blamk" href="<?php echo $this->data['authorUrl']; ?>"><?php echo str_replace('http://','',$this->data['authorUrl']); ?></a>
 </p>
</div>
</div>
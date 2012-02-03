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
<h1>
<?php echo $this->item->question; ?>
</h1>
<div>  
<?php echo $this->item->answer; ?>
</div>
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

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Weblinks Component Category Tree
 *
 * @static
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.6
 */
class SmfaqCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__smfaq';
		$options['extension'] = 'com_smfaq';
		$options['access'] = false;
		$options['statefield'] = 'published';
		//$options['published'] = false;
		$options['countItems'] = true;
		
		parent::__construct($options);
	}
}


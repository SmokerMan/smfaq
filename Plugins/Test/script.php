<?php
/**
 * SMFAQ
 *
 * @package		component for Joomla 1.6.x
 * @version		1.6.0
 * @copyright	(C)2009 - 2011 by SmokerMan (http://joomla-code.ru)
 * @license		GNU/GPL v.3 see http://www.gnu.org/licenses/gpl.html
 */

// защита от прямого доступа
defined('_JEXEC') or die('@-_-@');
 
/**
 * Script file of HelloWorld component
 */
class plgSmfaqTestInstallerScript
{
        /**
         * method to install the component
         *
         * @return void
         */
        function install($parent) 
        {
        	$db = JFactory::getDbo();
        	$query = 'ALTER TABLE #__smfaq ADD `my_field` VARCHAR(100) NOT NULL';
        	$db->setQuery($query);
        	if (!$db->query()) {
        		throw new Exception($db->getErrorMsg());
        	}
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
        function uninstall($parent) 
        {
        	$db = JFactory::getDbo();
        	$query = 'ALTER TABLE #__smfaq DROP `my_field`';
        	$db->setQuery($query);
        	if (!$db->query()) {
        		throw new Exception($db->getErrorMsg());
        	}        	
        }
 
        /**
         * method to update the component
         *
         * @return void
         */
        function update($parent) 
        {
        }
 
        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         */
        function preflight($type, $parent) 
        {

        }
 
        /**
         * method to run after an install/update/uninstall method
         *
         * @return void
         */
        function postflight($type, $parent) 
        {
        }
}

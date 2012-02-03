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

jimport( 'joomla.application.component.view');

class SmfaqViewCategory extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		$document =& JFactory::getDocument();

		$document->link = JRoute::_('index.php?option=com_smfaq&view=category&id='.JRequest::getVar('id',null, '', 'int'));

		JRequest::setVar('limit', $mainframe->getCfg('feed_limit'));
		$siteEmail = $mainframe->getCfg('mailfrom');
		$fromName = $mainframe->getCfg('fromname');
		$document->editor = $fromName;
		$document->editorEmail = $siteEmail;

		// Get some data from the model
		$items		=& $this->get( 'data' );
		$category	=& $this->get( 'category' );
		$i = 1;
		foreach ( $items as $item )
		{
			// strip html from feed item title
			$title = $this->escape( $item->question );
			$title = html_entity_decode( $title );

			// url link to article
			$link = JRoute::_('index.php?option=com_smfaq&view=category&id='.$category->slug.'#p'.$item->id );
			$i++;
			// strip html from feed item description text
			$description = $item->answer;
			$date = ( $item->created ? date( 'r', strtotime($item->created) ) : '' );

			// load individual item creator class
			$feeditem = new JFeedItem();
			$feeditem->title 		= $title;
			$feeditem->link 		= $link;
			$feeditem->description 	= $description;
			$feeditem->date			= $date;
			$feeditem->category   	= $category->title;

			// loads item info into rss array
			$document->addItem( $feeditem );
		}
	}
}
?>

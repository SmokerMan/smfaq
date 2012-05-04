<?php
/**
 * Smfaq Search Plugin
 * 
 * @version 	1.0	
 * @author		SmokerMan kolyamba831@inbox.ru
 * @url			http://joomla-code.ru
 * @copyright	© 2012. All rights reserved. 
 * @license 	GNU/GPL v.3 or later.
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSearchSmfaq extends JPlugin
{
	protected $my_var = null; // if needed


	/**
	 * Constructor
	 *
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		
		
		
	}
	
	/**
	* @return array An array of search areas
	*/
	function onContentSearchAreas()
	{
		$areas = array();
		$areas['smfaq'] = $this->params->get('area_label', 'FAQ');

		return $areas;
	}	
	
	/**
	 * Smfaq Search method
     *
     * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 * @param string 	Target search string
	 * @param string 	mathcing option, exact|any|all
	 * @param string 	ordering option, newest|oldest|popular|alpha|category
	 * @param mixed 	An array if the search it to be restricted to areas, null if search all
	 */
	public function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		require_once JPATH_SITE.'/components/com_smfaq/helpers/route.php';
		require_once JPATH_SITE.'/components/com_smfaq/router.php';
		
		$text = trim($text);
		if ($text == '') {
			return array();
		}		
		
		$db = JFactory::getDbo();
		$limit	= $this->params->get('search_limit', 50);
		$result_link =  $this->params->get('result_link', 1);
		
		switch ($phrase) {
			case 'exact':
				$text = $db->quote('%'.$text.'%', true);
				
				$wheres2	= array();
				$wheres2[]	= 'a.question LIKE '.$text;
				$wheres2[]	= 'a.answer LIKE '.$text;
				$wheres2[]	= 'a.metakey LIKE '.$text;
				$wheres2[]	= 'a.metadesc LIKE '.$text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word = $db->quote('%'.$text.'%', true);
					
					$wheres2	= array();
					$wheres2[]	= 'a.question LIKE '.$word;
					$wheres2[]	= 'a.answer LIKE '.$word;
					$wheres2[]	= 'a.metakey LIKE '.$word;
					$wheres2[]	= 'a.metadesc LIKE '.$word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;				
				
		}
		
		switch ($ordering) {
			case 'oldest':
				$order = 'a.created ASC';
				break;
			case 'alpha':
				$order = 'a.question ASC';
				break;
			case 'category':
				$order = 'c.title ASC, a.question ASC';
				break;
			case 'popular':
			case 'newest':
			default:
				$order = 'a.created DESC';
				break;
		}	

		$query = $db->getQuery(true);
		
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('c.alias');
		$case_when .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $c_id.' END as catslug';
				
		$query->select('a.id, a.question, a.answer AS text, a.created, a.metakey, a.metadesc, c.title AS section, '.$case_when);
		$query->where( '('. $where .' AND a.published = 1 AND c.published = 1 )' );
		$query->from('#__smfaq AS a');
		$query->join('INNER', '#__categories AS c ON c.id=a.catid AND extension='.$db->quote('com_smfaq'));
		$query->group('a.id,  a.question, a.answer, a.metadesc, a.metakey, a.created, c.title, c.alias, c.id');
		$query->order($order);
		
		$db->setQuery($query, 0, $limit);
		
		$list = $db->loadObjectList();
		
		if ($list)
		{
			foreach($list as $key => $item)
			{
				if ($result_link) {
					$list[$key]->href = JRoute::_(SmfaqHelperRoute::getQuestionRoute($item->catslug, $item->id ));
				} else {
					$list[$key]->href = JRoute::_(SmfaqHelperRoute::getCategoryRoute($item->catslug).'&limit=0#p'.$item->id);
				}
				
				$list[$key]->title = $item->question;
				$list[$key]->browsernav = true;
			}
		}	

		return $list;
	}	
}

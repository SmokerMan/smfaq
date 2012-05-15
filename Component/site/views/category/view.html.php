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
	protected $state;
	protected $items;
	protected $category;
	protected $children;
	protected $pagination;
	protected $form = null;

	function display( $tpl = null )
	{
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		JPluginHelper::importPlugin('smfaq');
		$dispatcher = JDispatcher::getInstance();

		// берем данные из модели
		$state		= $this->get('State');
		$category	= $this->get('Category');

		if ($category == false) {
			JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
			return;
		}

		$items		= $this->get('Items');
		$children	= $this->get('Children');
		$parent 	= $this->get('Parent');
		$pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($parent == false) {
			JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
			return; 
		}

		$params = $state->params;

		if (!$params->get('access-view')) {
			JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}
		
		if ($layout = $params->get('category_layout')) {
			$this->setLayout($layout);
		}
		
		//set print layout
		$print = JRequest::getCmd('tmpl');
		if ($print == 'component') {
			$this->assignRef('items', $items);
			parent::display('print');
			return;
		}		
		
		$ret = base64_encode(JFactory::getURI());

		$children = array($category->id => $children);
		$maxLevel =  $params->get('maxLevel', -1);
		
		$this->assignRef('maxLevel',	$maxLevel);
		$this->assignRef('params',		$params);
		$this->assignRef('category',	$category);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('user',		$user);
		$this->assignRef('children',	$children);
		$this->assignRef('parent',		$parent);
		$this->assignRef('ret',			$ret);

		//загрузка формы, если она отображается сразу
		if ($params->get('show_form', 0)) {
			JForm::addFormPath('components/com_smfaq/models/forms');
			JForm::addFieldPath('components/com_smfaq/models/fields');
			$this->form = JForm::getInstance('question', 'question');
			//плагины для формы
			$results = $dispatcher->trigger('onPrepareForm', array($this->form));
			ob_start();
			require_once 'components/com_smfaq/views/form/tmpl/default.php';
			$form = ob_get_contents();
			ob_end_clean();
			$this->assignRef('form',	$form);
		}
		
		$this->_prepareDocument();
		
		//плагины
		$events = $dispatcher->trigger('onSmfaqBeforeDisplay', array('com_smfaq.category', $category, $params));
		if ($events && is_array($events)) {
			foreach ($events as $event) {
				echo $event;
			}
		}
		
		parent::display($tpl);

		$events = $dispatcher->trigger('onSmfaqAfterDisplay', array('com_smfaq.category', $category, $params));
		if ($events && is_array($events)) {
			foreach ($events as $event) {
				echo $event;
			}
		}
		
		
		return;

	}

	/**
	 * Подготовка документа
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title 		= null;

		// Подключение js и стилей
		$catid = $this->category->id;
		$jscript = 'SmFaq.url = \'index.php?option=com_smfaq&amp;catid='.$catid.'&amp;format=raw&amp;task=\';';
		$this->document->addScriptDeclaration($jscript);
		
		$baseurl = $this->document->baseurl;
		$this->document->addStyleSheet($baseurl . "components/com_smfaq/css/smfaq.css");
		$this->document->addScript($baseurl . "components/com_smfaq/js/smfaq.js");


		$menu = $menus->getActive();
		
		if (!$menu) {
			$menus->setActive(JRequest::getInt('Itemid', null));
		}
		if ($menu) {
			$id = (int) $menu->query['id'];
		}
		

		if ($menu && ($menu->query['option'] != 'com_smfaq' || $id != $this->category->id)) {
			$path = array(array('title' => $this->category->title, 'link' => ''));
			$category = $this->category->getParent();

			while (($menu->query['option'] != 'com_smfaq' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => SmfaqHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		$title = $this->params->def('page_title', $title = $this->category->title);
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}

		$this->document->setTitle($title);

		if ($this->category->metadesc) {
			$this->document->setDescription($this->category->metadesc);
		}

		if ($this->category->metakey) {
			$this->document->setMetadata('keywords', $this->category->metakey);
		}

		if ($app->getCfg('MetaTitle') == '1') {
			$this->document->setMetaData('title', $title);
		}

		if ($app->getCfg('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $this->category->getMetadata()->get('author'));
		}

		$mdata = $this->category->getMetadata()->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}

		// Add alternative feed link
		if ($this->params->get('show_feed_link', 1) == 1)
		{
			$link	= '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$this->document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$this->document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}
	}


}

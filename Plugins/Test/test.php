<?php
/**
 * Smfaq_test
 * 
 * @version 	1.1
 * @author		SmokerMan kolyamba831@inbox.ru
 * @url			http://joomla-code.ru
 * @copyright	© 2012. All rights reserved. 
 * @license 	GNU/GPL v.3 or later.
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSmfaqTest extends JPlugin
{

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
	 * Событие перед отправкой формы
	 * @param array $res Массив данных для ответа
	 * @param array $post Массив данных из формы
	 * @param array $params Параметры категории
	 */
	public function onSmfaqBeforeSend(&$res, &$post, &$params)
	{
		//проверяем если длина значения меньше 5 устанавливаем ошибку для ответа
		if (utf8_strlen($post['my_field']) < 5) {
			//устанавливаем ошибку
			$res['valid'] = false;
			//устанавливаем для какого элемента и каую ошибку выводить
			$res['items'][] = array('name' => 'my_field', 'msg' => JText::_('PLG_SMFAQ_TEST_MY_FIELD_WRONG_MSG'));
		} 
		
		return;
	}

	/**
	 * Метод для подготовки формы на фронте
	 * @param object $form объект Jform
	 */
	public function onPrepareForm($form)
	{
		
		if ($form->getName() !== 'question') {
			return;
		}
	
		JForm::addFormPath(dirname(__FILE__).DS.'fields');
		$form->loadFile('fields', false);
	
		return;
	
	}
		
	/**
	 * Метод для подготовки формы редактирования
	 * @param object $form объект Jform
	 * @param array $data данные
	 */
	public function onContentPrepareForm($form, $data)
	{	
		if ($form->getName() !== 'com_smfaq.smfaq') {
			return;
		}

		JForm::addFormPath(dirname(__FILE__).DS.'fields');
		$form->loadFile('fields', false);
		
		return;
		
	}
}

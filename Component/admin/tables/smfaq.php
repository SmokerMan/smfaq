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
// подключаем билбеотеку таблиц Joomla
jimport('joomla.database.table');
/**
 * Создаем класс TableSmFAQ
 */
class TableSmFAQ extends JTable
{
	/**
	 * Конструктор
	 *
	 * @param коннектор БД объект
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__smfaq', 'id', $db);
	}
	
	
	public function check()
	{
		if (trim($this->question) == '')
		{
			$this->setError(JText::_('COM_SMFAQ_QUESTION_ERROR_MSG'));
			return false;
		}	
		
		return parent::check();
	}
	
	public function store($updateNulls = false)
	{
		if (isset($this->alias)) {
			$this->alias = JApplication::stringURLSafe($this->alias);
		}
		
		if (!empty($this->metakey))
		{
			// Only process if not empty
			$bad_characters = array("\n", "\r", "\"", "<", ">"); // array of characters to remove
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey); // remove bad characters
			$keys = explode(',', $after_clean); // create array using commas as delimiter
			$clean_keys = array();
		
			foreach ($keys as $key)
			{
				if (trim($key))
				{
					// Ignore blank keywords
					$clean_keys[] = trim($key);
				}
			}
			$this->metakey = implode(", ", $clean_keys); // put array back together delimited by ", "
		}
		
		return parent::store($updateNulls);
	}	

}

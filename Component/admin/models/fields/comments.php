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

jimport('joomla.form.formfield');

class JFormFieldComments extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'Comments';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$id = JRequest::getInt('id');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('comment, created, id');
		$query->from('#__smfaq_comments');
		$query->where('question_id =' . (int) $id);
		$db->setQuery($query);
		$comments = $db->loadObjectList();
		if ($comments) {
			$html = '<table class="adminlist"><thead><tr>
				<th width="10">'.JText::_('COM_SMFAQ_FIELD_CREATED_LABEL').'</th>
        		<th>'.JText::_('COM_SMFAQ_COMMENT').'</th>
        		<th width="10">'.JText::_('COM_SMFAQ_DELETE').'</th>
        		</tr></thead><tbody>';
			$i = 1;
			foreach ($comments as $comment) {
				$html .= '<tr id="comment-'.$comment->id.'" class="row'. $i % 2 .'">';
				$html .= '<td>'.JHTML::_('date',$comment->created, JText::_('COM_SMFAQ_DATE_FORMAT')).'</td>';
				$html .= '<td>'.$comment->comment.'</td>';
				$html .= '<td class="center"><div title="'.JText::_('COM_SMFAQ_DELETE_DESC').'" onclick="return SmFaq.delcomment('.$comment->id.',this)" class="smfaq-delete"></div></td>';
				$html .= '</tr>';
				$i++;
			}
			$html .= '</tbody></table>';
		} else {
			$html = JText::_('COM_SMFAQ_NO_RESULT_VOTES');
		}
		return $html;
	}
}
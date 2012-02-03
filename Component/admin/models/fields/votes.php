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

class JFormFieldVotes extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'Votes';

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
		$query->select('vote_yes, vote_no');
		$query->from('#__smfaq_votes');
		$query->where('question_id =' . (int) $id);
		$db->setQuery($query);
		$votes = $db->loadObject();
		if ($votes) {
			$html = '<div id="smfaq-votes"><span class="vote-yes-smfaq">' . JText::sprintf('COM_SMFAQ_ANSWER_HELP', $votes->vote_yes) . '</span>';
			$html .= '<span class="vote-no-smfaq">' . JText::sprintf('COM_SMFAQ_ANSWER_NOT_HELP', $votes->vote_no) . '</span>';
			$html .= '<div class="clr"></div>';
			$html .= '<button onclick="resetvote('.$id.', this)" type="button">' . JText::_('COM_SMFAQ_RESET_VOTES') . '</button></div>';
		} else {
			$html = '<div id="smfaq-votes">'.JText::_('COM_SMFAQ_NO_RESULT_VOTES').'</div>';
		}
		return $html;
	}
}
<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

/**
 * Renders a text element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JFormFieldNoticeBox extends FormField
{
	public $type = 'NoticeBox';
	public function getInput(){
		
		// Output		
		return '
		
		<div class="noticeBox">
			'.Text::_($this->value).'
		</div>';
	}

	public function getLabel() {
		return false;
	}
}

?>
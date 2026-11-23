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

class JFormFieldTitleBox extends FormField
{
	public $type = 'TitleBox';
	public function getInput(){
		// Output		
		return '
		
		<div class="titleBox">
			'.Text::_($this->value).'
		</div>';
	}

	public function getLabel() {
		return false;
	}
}

?>
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

class JFormFieldInfoBox extends FormField
{
	public $type = 'InfoBox';
	public function getInput(){
		// Output		
		return '
		
		<div class="infoBox">
			'.Text::_($this->value).'
		</div>';
	}

	public function getLabel() {
		return false;
	}
}

?>
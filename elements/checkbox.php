<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\URI;

\defined('_JEXEC') or die;

/**
 * Form Field class for the Joomla Platform.
 * Single check box field.
 * This is a boolean field with null for false and the specified option for true
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.checkbox.html#input.checkbox
 * @see         JFormFieldCheckboxes
 * @since       11.1
 */

class JFormFieldCheckbox extends FormField
{

	public $type = 'Checkbox';

	
	protected function getInput()
	{
		
		
		$document = Factory::getApplication()->getDocument();	

		// determine template filepath 
		$uri = str_replace(DIRECTORY_SEPARATOR,"/",str_replace( JPATH_SITE, URI::base (), dirname(dirname(__FILE__)) ));
		$uri = str_replace("/administrator/", "", $uri);
		$uriParts = explode('/', $uri);
		$this->template = end($uriParts);

		// add scripts 
		$document->getWebAssetManager()->registerAndUseScript('tpl.checkbox.ibutton', $uri.'/elements/js/ibutton.js');


		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$checked = ((string) $this->element['value'] == $this->value) ? ' checked="checked"' : '';

		// Initialize JavaScript field attributes.
		$onclick = $this->element['onclick'] ? ' onclick="' . (string) $this->element['onclick'] . '"' : '';

		return '<input type="checkbox" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars((string) $this->element['value'], ENT_COMPAT, 'UTF-8') . '"' . $class . $checked . $disabled . $onclick . '/>';
	}
}
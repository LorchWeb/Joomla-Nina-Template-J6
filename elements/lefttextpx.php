<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\FormField;
 
 
class JFormFieldlefttextpx extends FormField {
 
        protected $type = 'lefttextpx';
 
        // getLabel() left out
 
        public function getInput() {

            return 	'<div class="input-group">'.
            		'<span class="input-group-text">Left</span>'.
                        '<input class="form-control" type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
                        . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/>'.
                        '<span class="input-group-text">px</span>'.
                        '</div>';
        }
}
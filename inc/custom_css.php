<?php
defined('_JEXEC') or die('Restricted index access');

// Custom.css
$custom_css_inline = (string) $this->params->get('custom_css', '');
if (trim($custom_css_inline) !== '') {
	$wa->addInlineStyle($custom_css_inline);
}
if($this->params->get('customcss_sw') == "1") {
	$wa->useStyle('template.custom');
}

// Responsive Custom CSS
if($this->params->get('tabport_css') != "1") {
	$wa->addInlineStyle('@media only screen and (min-width: 768px) and (max-width: 959px) {'.$this->params->get('tabport_css').'}');
}
if($this->params->get('mobland_css') != "1") {
	$wa->addInlineStyle('@media only screen and ( max-width: 767px ) {'.$this->params->get('mobland_css').'}');
}
if($this->params->get('mobport_css') != "1") {
	$wa->addInlineStyle('@media only screen and (max-width: 440px) {'.$this->params->get('mobport_css').'}');
}

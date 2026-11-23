<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;


HTMLHelper::_('bootstrap.tooltip');

$params = $displayData['params'];
$legacy = $displayData['legacy'];

?>
<?php if ($params->get('show_icons')) : ?>
	<span class="icon-plus" aria-hidden="true"></span>
	<?php echo Text::_('JNEW'); ?>
<?php else : ?>
	<?php echo Text::_('JNEW') . '&#160;'; ?>
<?php endif; ?>

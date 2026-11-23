<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('bootstrap.framework');

$canEdit = $displayData['params']->get('access-edit');
$articleId = $displayData['item']->id;

?>

<div class="icons">
	<?php if (empty($displayData['print'])) : ?>
		<?php if ($canEdit || $displayData['params']->get('show_print_icon') || $displayData['params']->get('show_email_icon')) : ?>
			<div class="group">
				<?php if ($displayData['params']->get('show_print_icon')) : ?>
					<div class="btn-action print-icon"> <?php echo HTMLHelper::_('icon.print_popup', $displayData['item'], $displayData['params']); ?> </div>
				<?php endif; ?>
				<?php if ($displayData['params']->get('show_email_icon')) : ?>
					<div class="btn-action email-icon"> <?php echo HTMLHelper::_('icon.email', $displayData['item'], $displayData['params']); ?> </div>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<div class="btn-action edit-icon"> <?php echo HTMLHelper::_('icon.edit', $displayData['item'], $displayData['params']); ?> </div>
				<?php endif; ?>
				</div>
		<?php endif; ?>

	<?php else : ?>
		<div class="pull-right">
			<?php echo HTMLHelper::_('icon.print_screen', $displayData['item'], $displayData['params']); ?>
		</div>
	<?php endif; ?>
</div>

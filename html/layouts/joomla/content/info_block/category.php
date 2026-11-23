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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

?>
<dd class="category-name">
	<span class="j51-inline-icon">
		<svg class="svg-primary" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 128H272l-54.63-54.63c-6-6-14.14-9.37-22.63-9.37H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zm0 272H48V112h140.12l54.63 54.63c6 6 14.14 9.37 22.63 9.37H464v224z"></path></svg>
	</span>
	<?php $title = $this->escape($displayData['item']->category_title); ?>
	<?php if ($displayData['params']->get('link_category') && !empty($displayData['item']->catid)) : ?>
			<?php $url = '<a href="' . Route::_(
					RouteHelper::getCategoryRoute($displayData['item']->catid, $displayData['item']->category_language)
			)
					. '">' . $title . '</a>'; ?>
			<?php echo Text::sprintf('COM_CONTENT_CATEGORY', $url); ?>
	<?php else : ?>
			<?php echo Text::sprintf('COM_CONTENT_CATEGORY', '<span>' . $title . '</span>'); ?>
	<?php endif; ?>
</dd>
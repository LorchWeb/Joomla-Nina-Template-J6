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
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

?>
			<dd class="parent-category-name">
				<span class="j51-inline-icon">
					<svg class="svg-primary" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="folder" class="svg-inline--fa fa-folder fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 128H272l-64-64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48z"></path></svg>
				</span>
				<?php $title = $this->escape($displayData['item']->parent_title); ?>
				<?php if ($displayData['params']->get('link_parent_category') && !empty($displayData['item']->parent_slug)) : ?>
					<?php $url = '<a href="' . Route::_(RouteHelper::getCategoryRoute($displayData['item']->parent_slug)) . '" itemprop="genre">' . $title . '</a>'; ?>
					<?php echo Text::sprintf('COM_CONTENT_PARENT', $url); ?>
				<?php else : ?>
					<?php echo Text::sprintf('COM_CONTENT_PARENT', '<span itemprop="genre">' . $title . '</span>'); ?>
				<?php endif; ?>
			</dd>
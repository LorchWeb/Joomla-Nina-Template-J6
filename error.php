<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$app             = Factory::getApplication();
$doc             = $this;
$user            = $app->getIdentity();
$this->language  = $doc->language;
$this->direction = $doc->direction;

$wa  = $this->getWebAssetManager();

$wa->useStyle('template.nexus.min');
$wa->useStyle('fontawesome.joomla');
$wa->useStyle('fontawesome.base');
$wa->useStyle('fontawesome.shims');
$wa->useStyle('fontawesome.regular');
$wa->useStyle('fontawesome.solid');

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->getInput()->getCmd('option', '');
$view     = $app->getInput()->getCmd('view', '');
$layout   = $app->getInput()->getCmd('layout', '');
$task     = $app->getInput()->getCmd('task', '');
$itemid   = $app->getInput()->getCmd('Itemid', '');
$sitename = $app->get('sitename');

// Titel wie bisher: Dokumenttitel + Fehlermeldung (Ausgabe über jdoc:include type="metas")
$this->setTitle(trim($this->title . ' ' . $this->error->getMessage()));

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/error.css" />
	<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
?>">

	<!-- Body -->
	<div class="error-wrapper">
		<div class="error-code"><?php echo $this->error->getCode(); ?></div>
		<div class="error-message">
			<h2><?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8');?></h2>
			<p><strong><?php echo Text::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
			<p><?php echo Text::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
			<ul>
				<li><?php echo Text::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
				<li><?php echo Text::_('JERROR_LAYOUT_MISTYPED_ADDRESS'); ?></li>
				<li><?php echo Text::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
				<li><?php echo Text::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
			</ul>
	      	<blockquote>
				<span class="label label-inverse"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8');?>
				<?php if ($this->debug) : ?>
					<br/><?php echo htmlspecialchars($this->error->getFile(), ENT_QUOTES, 'UTF-8');?>:<?php echo $this->error->getLine(); ?>
				<?php endif; ?>
			</blockquote>
		</div>
	</div>

	<div class="page-redirect">
		<?php echo Text::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>
		<a href="<?php echo $this->baseurl; ?>/index.php"><span class="fa fa-home"></span> <?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?></a>
	</div>

	<?php if ($this->debug) : ?>
		<div class="debug-trace">
			<?php echo $this->renderBacktrace(); ?>
			<?php // Check if there are more Exceptions and render their data as well ?>
			<?php if ($this->error->getPrevious()) : ?>
				<?php $loop = true; ?>
				<?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
				<?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
				<?php $this->setError($this->_error->getPrevious()); ?>
				<?php while ($loop === true) : ?>
					<p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
					<p>
						<?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
						<br/><?php echo htmlspecialchars($this->_error->getFile(), ENT_QUOTES, 'UTF-8');?>:<?php echo $this->_error->getLine(); ?>
					</p>
					<?php echo $this->renderBacktrace(); ?>
					<?php $loop = $this->setError($this->_error->getPrevious()); ?>
				<?php endwhile; ?>
				<?php // Reset the main error object to the base error ?>
				<?php $this->setError($this->error); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>

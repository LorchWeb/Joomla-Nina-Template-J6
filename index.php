<?php
/*================================================================*\
|| # Copyright (C) 2022  Joomla51. All Rights Reserved.           ||
|| # license - PHP files are licensed under  GNU/GPL V2           ||
|| # license - CSS  - JS - IMAGE files are Copyrighted material   ||
|| # Website: http://www.joomla51.com                             ||
\*================================================================*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

// The application
$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Loading the autoload file of composer
require_once __DIR__ . '/vendor/autoload.php';

$document        = $this;
$user            = $app->getIdentity();
$this->language  = $document->language;
$this->direction = $document->direction;

$siteName = $this->params->get('siteName');

$document->setHtml5(true);
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option    = $app->getInput()->getCmd('option', '');
$view      = $app->getInput()->getCmd('view', '');
$layout    = $app->getInput()->getCmd('layout', '');
$task      = $app->getInput()->getCmd('task', '');
$itemid    = $app->getInput()->getCmd('Itemid', '');
$sitename  = $app->get('sitename');
$menu      = $app->getMenu()->getActive();
$menuParams = new \Joomla\Registry\Registry();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';
$editing   = false;
if (($option === 'com_config' && $view === 'modules') || ($layout === 'edit')) {
	$editing = true;
}
require_once("inc/helper.php");
require_once("inc/variables.php");

// require_once("vendor/ciar4n/j51_framework/src/Helper/BlockHelper.php");
$helper = new \J51\Helper\BlockHelper();
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
	<?php include("inc/head.php"); ?>
	<?php include("inc/scripts.php"); ?>
	<?php echo $this->params->get('head_custom_code'); ?>
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ' ' . $pageclass
	. ($params->get('fluidContainer') ? ' fluid' : '');
	echo ($this->direction === 'rtl' ? ' rtl' : '');
?>">
	<div class="unsupported-browser"></div>
	<div id="back-to-top"></div>
	<div class="body_bg"></div>

	<div id="mobile-menu" class="mobile-menu">
		<?php if ($this->params->get('hornavPosition') == '1') : ?>
			<jdoc:include type="modules" name="hornav" />
		<?php else : ?>
			<?php echo $hornav; ?>
		<?php endif; ?>
	</div>

	<?php if ($this->countModules('header-1') || $this->countModules('header-2')) : ?>
		<div class="header_top">
			<?php if ($this->countModules('header-1')) : ?>
				<div class="header-1 header-mod">
					<jdoc:include type="modules" name="header-1" style="mod_simple" />
				</div>
			<?php endif; ?>
			<?php if ($this->countModules('header-2')) : ?>
				<div class="header-2 header-mod">
					<jdoc:include type="modules" name="header-2" style="mod_simple" />
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="outside_header">
		<header id="container_header" class="container_header">
			<div id="header_main" class="header_main">
				<?php require("inc/layouts/logo.php"); ?>
				<?php require("inc/layouts/hornav.php"); ?>
				<a href="#mobile-menu" class="menu-toggle" aria-haspopup="true" role="button" tabindex="0">
					<span></span>
				</a>
			</div>
			<div class="container_subheader">
				<?php require("inc/layouts/social_icons.php"); ?>
			</div>
		</header>

		<?php if ($helper->blockExists($this, 'showcase-1') && (!$editing)) { ?>
			<div id="container_showcase1_modules" class="module_block border_block">
				<div class="wrapper960">
					<?php $helper->renderBlock($this, 'showcase-1'); ?>
				</div>
			</div>
		<?php } ?>
	</div>

	<?php if ($helper->blockExists($this, 'top-1') && (!$editing)) { ?>
		<div id="container_top1_modules" class="module_block <?php if ($this->params->get('top1_parallax') == "1") { echo 'jarallax';} ?>" style="background-position: 50% 0">
			<div class="wrapper960">
				<?php $helper->renderBlock($this, 'top-1'); ?>
			</div>
		</div>
	<?php } ?>

	<?php if ($helper->blockExists($this, 'top-2') && (!$editing)) { ?>
		<div id="container_top2_modules" class="module_block <?php if ($this->params->get('top2_parallax') == "1") {
																														echo 'jarallax';
																													} ?>" style="background-position: 50% 0">
			<div class="wrapper960">
				<?php $helper->renderBlock($this, 'top-2'); ?>
			</div>
		</div>
	<?php } ?>
	<?php if ($this->countModules('breadcrumb') || $helper->blockExists($this, 'top-3') && (!$editing)) { ?>
		<div id="container_top3_modules" class="module_block <?php if ($this->params->get('top3_parallax') == "1") {
																														echo 'jarallax';
																													} ?>" style="background-position: 50% 0">
			<div class="wrapper960">
				<?php $helper->renderBlock($this, 'top-3'); ?>
				<?php if ($this->countModules('breadcrumb')) : ?>
					<jdoc:include type="modules" name="breadcrumb" style="mod_simple" />
				<?php endif; ?>
			</div>
		</div>
	<?php } ?>

	<?php if ($this->params->get('hide_component') == "0") { ?>
		<div id="container_main" class="component_block">
			<div class="wrapper960">
				<?php require("inc/layouts/main.php"); ?>
			</div>
		</div>
	<?php } ?>
	<?php if ($helper->blockExists($this, 'bottom-1') && (!$editing)) { ?>
		<div id="container_bottom1_modules" class="module_block <?php if ($this->params->get('bottom1_parallax') == "1") {
																															echo 'jarallax';
																														} ?>" style="background-position: 50% 0">
			<div class="wrapper960">
				<?php $helper->renderBlock($this, 'bottom-1'); ?>
			</div>
		</div>
	<?php } ?>
	<?php if ($helper->blockExists($this, 'bottom-2') && (!$editing)) { ?>
		<div id="container_bottom2_modules" class="module_block <?php if ($this->params->get('bottom2_parallax') == "1") {
																															echo 'jarallax';
																														} ?>" style="background-position: 50% 0">
			<div class="wrapper960">
				<?php $helper->renderBlock($this, 'bottom-2'); ?>
			</div>
		</div>
	<?php } ?>
	<?php if ($helper->blockExists($this, 'bottom-3') && (!$editing)) { ?>
		<div id="container_bottom3_modules" class="module_block <?php if ($this->params->get('bottom3_parallax') == "1") {
																															echo 'jarallax';
																														} ?>" style="background-position: 50% 0">
			<div class="wrapper960">
				<?php $helper->renderBlock($this, 'bottom-3'); ?>
			</div>
		</div>
	<?php } ?>

	<?php require("inc/layouts/base.php"); ?>

	<footer id="container_footer" class="container_footer">
		<div class="wrapper960">
			<div class="footerlogo">
				<?php if (!empty($footerlogoimagefile)) :?>
					<img class="logo-image footer-logo-image" src="<?php echo $this->baseurl ?>/<?php echo $footerlogoimagefile; ?>" alt="Logo" />
				<?php endif; ?>
			</div>
			<div class="footermeta">
				<div class="copyright">
					<p><?php echo $this->params->get('copyright'); ?></p>
				</div>
				<?php if ($this->params->get('footermenuPosition') == '1') : ?>
					<?php if ($this->countModules('footermenu')) : ?>
						<div class="footermenu">
							<jdoc:include type="modules" name="footermenu" />
						</div>
					<?php endif; ?>
				<?php else : ?>
					<div class="footermenu">
						<?php echo $footermenu; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</footer>

	<?php if ($this->params->get('back_to_top', '1') == '1') { ?>
		<a href="#back-to-top" class="smooth-scroll" data-scroll>
			<div class="back-to-top">
				<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
					<path d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"></path>
				</svg>
			</div>
		</a>
	<?php } ?>

	<?php echo $this->params->get('body_custom_code'); ?>

	<?php if($parallax_sw != "0") { ?>
		<?php if (!$this->params->get('top1_bg') || !$this->params->get('top2_bg') || !$this->params->get('top3_bg') || !$this->params->get('bottom1_bg') || !$this->params->get('bottom2_bg') || !$this->params->get('bottom3_bg')) { ?> 
		<?php $wa->useScript('template.jarallax'); ?>
		<script>
			jarallax(document.querySelectorAll('.jarallax'), {
				speed: 0.5,
				disableParallax: /iPad|iPhone|iPod|Android/,
				disableVideo: /iPad|iPhone|iPod|Android/
			});
		</script>
		<?php } ?>
	<?php } ?>
	
	<jdoc:include type="modules" name="debug" />
</body>

</html>
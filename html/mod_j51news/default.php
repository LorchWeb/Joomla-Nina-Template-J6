<?php
/**
* J51_News
* Created by	: Joomla51
* Email			: info@joomla51.com
* URL			: www.joomla51.com
* License GPLv2.0 - http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

if (!$list) {
    return;
}

$countcol 			= $params->get('countcol', 2);
$show_img 			= $params->get('show_img', 1);
$show_date 			= $params->get('show_date', 1);
$show_title 		= $params->get('show_title', 1);
$show_category 	= $params->get('show_category', 1);
$title_tag 	    = $params->get('title_tag', 'h3');
$show_text 			= $params->get('show_text', 1);
$text_tag 	    = $params->get('text_tag', 'p');
$show_button 		= $params->get('show_button', 1);
$length_text 		= $params->get('length_text', 300);
$date_format 		= $params->get('date_format','d.m.Y');
$columns 				= $params->get('columns', 100);
$masonry 				= $params->get('masonry', 0);
$layout_type    = $params->get('layout_type', 'grid');
$j51_trans_speed = $params->get('j51_trans_speed', 1000);
$j51_autoplay   = $params->get('j51_autoplay', 'true');
$j51_autoplay_delay = $params->get('j51_autoplay_delay', 3000);
$item_margin_x 	= $params->get('item_margin_x', 10);
$item_margin_y 	= $params->get('item_margin_y', 10);
$item_button 	= $params->get('item_button');
$columns_tabl	= $params->get( 'columns_tabl', 3);
$columns_tabp	= $params->get( 'columns_tabp', 2);
$columns_mobl	= $params->get( 'columns_mobl', 1);
$columns_mobp	= $params->get( 'columns_mobp', 1);
$image_width	= $params->get( 'image_width', '50%');
$max_width		= $params->get( 'max_width');
$overlay_type 	= $params->get( 'overlay_type', 'overlay-fade-out');
$j51_news_layout = $params->get( 'j51_news_layout', 'col-i-c');
$j51_moduleid 	= $module->id;
$title_color    = $params->get( 'title_color', '');
$text_color     = $params->get( 'text_color', '');
$bg_color       = $params->get( 'bg_color', '');
$svg_code       = $params->get( 'svg_code', '');
$columns_num   	= $params->get( 'columns_num', 100);


// Load CSS/JS
$document = Factory::getApplication()->getDocument();
$wa       = $document->getWebAssetManager();
HTMLHelper::_('stylesheet', 'mod_j51news/style.css', array('relative' => true, 'version' => 'auto'));

// Masonry
if(($masonry) || ($layout_type == 'masonry'))  {
	if (file_exists('media/j51_assets/js/imagesloaded.pkgd.min.js')) { 
	    HTMLHelper::_('script', 'j51_assets/imagesloaded.pkgd.min.js', array('relative' => true, 'version' => 'auto'), array('defer' => true, 'async' => false));
	} else { 
	    HTMLHelper::_('script', 'mod_j51news/imagesloaded.pkgd.min.min.js', array('relative' => true, 'version' => 'auto'), array('defer' => true, 'async' => false));
	}
	if (file_exists('media/j51_assets/js/masonry.pkgd.min.js')) { 
	    HTMLHelper::_('script', 'j51_assets/masonry.pkgd.min.js', array('relative' => true, 'version' => 'auto'), array('defer' => true, 'async' => false));
	} else { 
	    HTMLHelper::_('script', 'mod_j51news/masonry.pkgd.min.min.js', array('relative' => true, 'version' => 'auto'), array('defer' => true, 'async' => false));
	}
	$wa->addInlineScript('
		document.addEventListener("DOMContentLoaded", function() {
			var elem = document.querySelector(".j51news'.$j51_moduleid.' .j51news_inside");
			imagesLoaded( elem, function() {
				var msnry = new Masonry( elem, {
				  itemSelector: ".newsitem"
				});
			});
		});
	');
}

if($layout_type == 'carousel') {
	if (file_exists('media/j51_assets/js/tiny-slider.min.js')) { 
	    HTMLHelper::_('script', 'j51_assets/tiny-slider.min.js', array('relative' => 'auto', 'version' => 'auto')); 
	} else { 
	    HTMLHelper::_('script', 'mod_j51news/tiny-slider.min.js', array('relative' => 'auto', 'version' => 'auto'));
	}

	if (file_exists('media/j51_assets/css/tiny-slider.min.css')) { 
	    HTMLHelper::_('stylesheet', 'j51_assets/tiny-slider.min.css', array('relative' => 'auto', 'version' => 'auto')); 
	} else { 
	    HTMLHelper::_('stylesheet', 'mod_j51news/tiny-slider.min.css', array('relative' => 'auto', 'version' => 'auto'));
	}

	$wa->addInlineScript('
		document.addEventListener("DOMContentLoaded", () => {
		    var slider = tns({
				container: "#j51news'.$j51_moduleid.'",
				items: 1,
				slideBy: "page",
				nav: false,
				autoplay: '.$j51_autoplay.',
				autoplayTimeout: '.$j51_autoplay_delay.',
				speed: '.$j51_trans_speed.',
				mouseDrag: true,
				controlsContainer: "#j51news'.$j51_moduleid.'-controls",
				autoplayButton: "#j51news'.$j51_moduleid.'-play",
				mouseDrag: true,
				responsive : {
		            0 : {
		                items: '.(int)(100 / $columns_mobp).',
		            },
		            440 : {
		                items: '.(int)(100 / $columns_mobl).',
		            },
		            767 : {
		                items: '.(int)(100 / $columns_tabp).',
		            },
		            960 : {
		                items: '.(int)(100 / $columns_tabl).',
		            },
		            1280 : {
		                items: '.(int)(100 / $columns_num).'
		            }
		        },
		    });
		});
	'); 
}

// Styling from module parameters
$wa->addInlineStyle('
.j51news'.$j51_moduleid.' .j51news_inside {
	margin: 0 -'.($item_margin_x / 2).'px;
}
.j51news'.$j51_moduleid.' .newsitem {
	width:'.$columns_num.'%;
	padding-top: '.($item_margin_y / 2).'px;
	padding-bottom: '.($item_margin_y / 2).'px;
	padding-left: '.($item_margin_x / 2).'px;
	padding-right: '.($item_margin_x / 2).'px;
}

.j51news'.$j51_moduleid.'.j51_news_layout_row-i-c .newsimg,
.j51news'.$j51_moduleid.'.j51_news_layout_row-c-i .newsimg {
	flex-basis: '.$image_width.'%;
}

@media only screen and (min-width: 960px) and (max-width: 1280px) {
.j51news'.$j51_moduleid.' .newsitem {width:'.$columns_tabl.'%;}
}
@media only screen and (min-width: 768px) and (max-width: 959px) {
.j51news'.$j51_moduleid.' .newsitem {width:'.$columns_tabp.'%;}
}
@media only screen and ( max-width: 767px ) {
.j51news'.$j51_moduleid.' .newsitem {width:'.$columns_mobl.'%;}
}
@media only screen and (max-width: 440px) {
.j51news'.$j51_moduleid.' .newsitem {width:'.$columns_mobp.'%;}
}
');

if($show_button && !$show_title && !$show_date && !$show_text)  {
 $wa->addInlineStyle('
 	.j51news'.$j51_moduleid.' figcaption {
 		padding-top: 0;
 	}
');
}

if (!empty($text_color)) {
	$wa->addInlineStyle('
		.j51news'.$j51_moduleid.' {
			color: '.$text_color.' !important;
		}
	');
} 

if (!empty($title_color)) {
	$wa->addInlineStyle('
		.j51news'.$j51_moduleid.' .j51news-title {
			color: '.$title_color.' !important;
		}
	');
} 

if (!empty($bg_color)) {
	$wa->addInlineStyle('
		.j51news'.$j51_moduleid.' figcaption {
			--j51news-figcation-bg: '.$bg_color.';
			background-color: var(--j51news-figcation-bg);
		}
	');
} 

?>

<div class="j51news j51news<?php echo $j51_moduleid; ?> j51_news_layout_<?php echo $j51_news_layout; ?><?php if ($j51_news_layout === 'overlay') {echo ' j51_news_overlay_'.$overlay_type;} ?> " <?php  if ($max_width != '') { ?>style="max-width: <?php echo $max_width ?>px;" <?php } ?>>
	<div id="j51news<?php echo $j51_moduleid; ?>" class="j51news_inside<?php if($layout_type == 'carousel') { ?> owl-carousel<?php } ?>">
		<?php $i=1; foreach ($list as $item) :  ?><div class="newsitem item ter-<?php  echo $i; ?> <?php if ($item->featured) { ?>featured<?php } ?>" itemscope itemtype="http://schema.org/Article">
			<figure>
				<?php $images = json_decode($item->images); ?>
				<?php  if (($show_img == 1) && !empty($images->image_intro)) : ?>	
				<div class="j51news-image newsimg">
					<a href="<?php echo $item->link; ?>" aria-label="<?php echo $item->title; ?>">
						<div class="hover-overlay"></div>
						<div class="link-icon-outer">
							<div class="link-icon">
								<?php if (!empty($svg_code)) {
									echo $svg_code;
								} else { ?>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M436 238H242V44c0-6.6-5.4-12-12-12h-12c-6.6 0-12 5.4-12 12v194H12c-6.6 0-12 5.4-12 12v12c0 6.6 5.4 12 12 12h194v194c0 6.6 5.4 12 12 12h12c6.6 0 12-5.4 12-12V274h194c6.6 0 12-5.4 12-12v-12c0-6.6-5.4-12-12-12z"/></svg>
								<?php } ?>
							</div>
						</div>
						<img src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" />
						<?php if ($show_date || $show_category) { ?>
						<div class="j51news-meta">
							<?php  if ($show_date) : ?>
							<span class="j51news-date newsdate">
								<svg class="svg-primary" aria-hidden="true" focusable="false" data role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 96v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm192 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-260v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"></path></svg>
								<?php echo HTMLHelper::_('date', $item->displayDate, 'd M Y') ?>
							</span>
							<?php endif; ?>
							<?php  if ($show_category) : ?>
							<span class="j51news-category">
								<svg class="svg-primary" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 128H272l-54.63-54.63c-6-6-14.14-9.37-22.63-9.37H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zm0 272H48V112h140.12l54.63 54.63c6 6 14.14 9.37 22.63 9.37H464v224z"></path></svg>
								<?php echo $item->category_title; ?>
							</span>
							<?php endif; ?>
						</div>
						<?php } ?>
					</a>
				</div>
				<?php endif; ?>
				<?php if ($show_title || $show_text || $show_button)  : ?>
					<figcaption>
						<?php  if ($show_title) : ?>
						<<?php echo $title_tag; ?> itemprop="name" class="newstitle j51news-title">
							<?php echo $item->title; ?>
						</<?php echo $title_tag; ?>>
						<?php  endif; ?>
						<?php  if (empty($images->image_intro)) : ?>	
							<?php if ($show_date || $show_category) { ?>
								<div class="j51news-meta">
									<?php  if ($show_date) : ?>
									<span class="j51news-date newsdate">
										<svg class="svg-primary" aria-hidden="true" focusable="false" data role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 96v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm192 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-260v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"></path></svg>
										<?php echo HTMLHelper::_('date', $item->displayDate, 'd M Y') ?>
									</span>
									<?php endif; ?>
									<?php  if ($show_category) : ?>
									<span class="j51news-category">
										<svg class="svg-primary" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 128H272l-54.63-54.63c-6-6-14.14-9.37-22.63-9.37H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zm0 272H48V112h140.12l54.63 54.63c6 6 14.14 9.37 22.63 9.37H464v224z"></path></svg>
										<?php echo $item->category_title; ?>
									</span>
									<?php endif; ?>
								</div>
							<?php } ?>
						<?php  endif; ?>
						<?php if ($show_text) : ?>
						<<?php echo $text_tag; ?> class="newstext j51news-caption"><?php
						$introb = strip_tags($item->introtext);
						$intro = HTMLHelper::_('string.truncate', $introb, $length_text);
						echo $intro; ?></<?php echo $text_tag; ?>>
						<?php endif; ?>
						<?php  if ($show_button) { ?>
							<a class="btn j51news-btn" href="<?php echo $item->link; ?>" itemprop="url"><?php echo $item_button; ?></a>
						<?php } ?>
					</figcaption>
				<?php endif; ?>
				<?php  if (!($show_button)) { ?>
					<a class="newslink" href="<?php echo $item->link; ?>" itemprop="url" aria-label="<?php echo $item->title; ?>"></a>
				<?php } ?>
			</figure>
		</div><?php $i++; endforeach; ?>
	</div>
	<?php if($layout_type == 'carousel') { ?>
		<div id="j51news<?php echo $j51_moduleid; ?>-play" style="display:none;"></div>
		<div class="j51news-nav" id="j51news<?php echo $j51_moduleid; ?>-controls">
			<a type="button" role="presentation" id="j51news-prev<?php echo $j51_moduleid; ?>" class="j51news-prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"></path></svg></a><a type="button" role="presentation" id="j51news-next<?php echo $j51_moduleid; ?>" class="j51news-next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg></a>
		</div>
	<?php } ?>
</div>



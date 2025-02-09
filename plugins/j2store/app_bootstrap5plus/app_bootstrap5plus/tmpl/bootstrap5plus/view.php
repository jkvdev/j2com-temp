<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 *
 * Bootstrap 2 layout of product detail
 */

 // Import Joomla packages
 use Joomla\CMS\Language\Text;

// No direct access
defined('_JEXEC') or die;
?>
<!-- Render Product Details Page -->
<div
	class="j2store-single-product pt-3 <?= $this->product->product_type; ?> detail <?= $this->product->params->get('product_css_class', ''); ?>">
	<?php

	// If heading is enabled
	if ($this->params->get('item_show_page_heading')) : ?>
		<!-- Page Header container -->
		<div class="page-header">
			<!-- Header -->
			<h1>
				<!-- Text -->
				<?= $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif;

	// Load modules in the Top Position
	echo J2Store::modules()->loadposition('j2store-single-product-top');

	// Optional: Show back button
	if (
		$this->params->get('item_show_back_to', 0)
		&& isset($this->back_link)
		&& !empty($this->back_link)
	): ?>
		<!-- Back button container -->
		<div class="j2store-view-back-button">
			<!-- Back link -->
			<a
				href="<?= $this->back_link; ?>"
				class="j2store-product-back-btn btn btn-small btn-info">
				<!-- Icon -->
				<i class="fa fa-chevron-left"> </i>

				<!-- Text -->
				<?= Text::_('J2STORE_PRODUCT_BACK_TO') . ' ' . $this->back_link_title; ?>
			</a>
		</div>
	<?php endif;

	// Load Product template
	echo $this->loadTemplate($this->product->product_type);

	// Trigger plugins after product is displayed
	echo J2Store::plugin()->eventWithHtml(
		'AfterProductDisplay',
		array(
			$this->product,
			$this
		)
	);

	// Load modules in Bottom Position
	echo J2Store::modules()->loadposition('j2store-single-product-bottom'); ?>
</div>
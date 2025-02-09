<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;

// No direct access
defined('_JEXEC') or die;

// Retrieving the images template
$images = $this->loadTemplate('images');

// Event Hook: Running plugins before displaying images 
J2Store::plugin()->event(
	'BeforeDisplayImages',
	array(
		&$images,
		$this,
		'com_j2store.products.list.bootstrap'
	)
);

// Rendering images
echo $images;

// Rendering title
echo $this->loadTemplate('title');

// If a plugin modifies the title after display
if (isset($this->product->event->afterDisplayTitle)) {
	// Run plugin
	echo $this->product->event->afterDisplayTitle;
}

// If a plugin modifies the title before display
if (isset($this->product->event->beforeDisplayContent)) {
	// Run plugin
	echo $this->product->event->beforeDisplayContent;
}

// Render description
echo $this->loadTemplate('description');

// Check if product price display is enabled
if (J2Store::product()->canShowprice($this->params)) {
	// Display price
	echo $this->loadTemplate('price');
}

// Show product SKU if enabled
if (
	$this->params->get('list_show_product_sku', 1)
	&&  J2Store::product()->canShowSku($this->params)
) {
	// Display SKU
	echo $this->loadTemplate('sku');
}

// If enabled, get product stock
if (
	$this->params->get('list_show_product_stock', 1)
	&& J2Store::product()->managing_stock($this->product->variant)
) {
	// Display stock
	echo $this->loadTemplate('stock');
}

// Render add to cart if enabled
if (J2Store::product()->canShowCart($this->params)): ?>
	<!-- Add to cart form -->
	<form
		method="post"
		action="<?= $this->product->cart_form_action; ?>"
		id="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
		class="j2store-addtocart-form"
		name="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
		data-product_id="<?= $this->product->j2store_product_id; ?>"
		data-product_type="<?= $this->product->product_type; ?>"
		enctype="multipart/form-data">

		<?php
		// Get Cart Type
		$cart_type = $this->params->get('list_show_cart', 1);

		// If type is 1 / Standard
		if ($cart_type == 1) :
			// Load configurable options
			echo $this->loadTemplate('configurableoptions');
			//  Load add to cart button
			echo $this->loadTemplate('cart');

		// If cart type is 2 or 3, redirect to product page
		elseif (
			(
				$cart_type == 2
				&& count($this->product->options)
			)
			|| $cart_type == 3
		): ?>
			<!-- Render redirect link to product details page -->
			<a
				href="<?= $this->product->product_link; ?>"
				class="<?= $this->params->get(
									'choosebtn_class',
									'btn btn-success'
								); ?>">
				<!-- Link Text -->
				<?= Text::_('J2STORE_VIEW_PRODUCT_DETAILS'); ?>
			</a>
		<?php else:
			// Default: Just render cart
			echo $this->loadTemplate('cart');
		endif; ?>

	</form>

<?php endif;

// Event Hook: After Content Display
if (isset($this->product->event->afterDisplayContent)) {
	// Run Plugins
	echo $this->product->event->afterDisplayContent;
} ?>
<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;

// No direct access
defined('_JEXEC') or die;

// Load the images template
$images = $this->loadTemplate('images');
// Event Handler: Trigger Joomla Plugins before rendering images
J2Store::plugin()->event(
	'BeforeDisplayImages',
	array(
		&$images,
		$this,
		'com_j2store.products.list.bootstrap'
	)
);
// Render Images
echo $images;

// Load the title template
echo $this->loadTemplate('title');

// Check if there are any custom Joomla events after the title
if (isset($this->product->event->afterDisplayTitle)) {
	// Render the events
	echo $this->product->event->afterDisplayTitle;
}

// Check if there are any Joomla Plugins before the content
if (isset($this->product->event->beforeDisplayContent)) {
	// Render the plugins
	echo $this->product->event->beforeDisplayContent;
}

// Load the description template
echo $this->loadTemplate('description');

// Check if the price can be shown
if (J2Store::product()->canShowprice($this->params)) {
	// Load the price template
	echo $this->loadTemplate('price');
}

// Check if the SKU is enabled and can be showed
if ($this->params->get('list_show_product_sku', 1) &&  J2Store::product()->canShowSku($this->params)) {
	// Load the SKU template
	echo $this->loadTemplate('sku');
}

// Check if the product stock can be displayed
if ($this->params->get('list_show_product_stock', 1) && J2Store::product()->managing_stock($this->product->variant)) {
	// Load the Stock template
	echo $this->loadTemplate('stock');
}

// Check if the add to cart section is enabled
if (J2Store::product()->canShowCart($this->params)): ?>

	<!-- Create Add to cart form -->
	<form
		method="post"
		action="<?= $this->product->cart_form_action; ?>"
		name="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
		id="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
		class="j2store-addtocart-form"
		data-product_id="<?= $this->product->j2store_product_id; ?>"
		data-product_type="<?= $this->product->product_type; ?>"
		enctype="multipart/form-data">

		<?php
		// Retrieve the cart display setting
		$cart_type = $this->params->get('list_show_cart', 1);

		// If the cart is of type one
		if ($cart_type == 1) :
			// Load Product options template
			echo $this->loadTemplate('options');
			// Load Cart Button template
			echo $this->loadTemplate('cart');

		// If the cart is of type 2 and has options or of type 3
		elseif (($cart_type == 2 && count($this->product->options)) || $cart_type == 3): ?>
			<!-- Render redirect link to product details -->
			<a
				href="<?= $this->product->product_link; ?>"
				class="<?= $this->params->get('choosebtn_class', 'btn btn-success'); ?>">
				<!-- Render Link Text -->
				<?= Text::_('J2STORE_VIEW_PRODUCT_DETAILS'); ?>
			</a>
		<?php else:
			// Default: Render cart template
			echo $this->loadTemplate('cart');
		endif; ?>

	</form>

<?php endif;

// Check if there are any product events after the content
if (isset($this->product->event->afterDisplayContent)) {
	// Trigger Joomla events/plugins
	echo $this->product->event->afterDisplayContent;
} ?>
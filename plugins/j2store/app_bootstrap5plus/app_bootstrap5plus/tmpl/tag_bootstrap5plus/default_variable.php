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

// Load Images template
$images = $this->loadTemplate('images');

// Run plugins before displaying images
J2Store::plugin()->event(
	'BeforeDisplayImages',
	array(
		&$images,
		$this,
		'com_j2store.products.list.bootstrap'
	)
);

// Render images template
echo $images;

// Render title template
echo $this->loadTemplate('title');

// If after title plugins are available
if (isset($this->product->event->afterDisplayTitle)) {
	// Render plugins
	echo $this->product->event->afterDisplayTitle;
}

// If before content plugins are available
if (isset($this->product->event->beforeDisplayContent)) {
	// Run plugins
	echo $this->product->event->beforeDisplayContent;
}

//  Render description template
echo $this->loadTemplate('description');

// If price is enabled
if (J2Store::product()->canShowprice($this->params)) {
	// Render price template
	echo $this->loadTemplate('price');
}

// If SKU is enabled
if (
	$this->params->get('list_show_product_sku', 1)
	&&  J2Store::product()->canShowSku($this->params)
) {
	// Render SKU template
	echo $this->loadTemplate('sku');
}

// If product stock is enabled
if ($this->params->get('list_show_product_stock', 1)) {
	// Render stock template
	echo $this->loadTemplate('stock');
}

// If price is enabled
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
		<?php
		// If product has variants
		// Pass them
		if (isset($this->product->variant_json)): ?>
		data-product_variants="<?= $this->escape($this->product->variant_json); ?>"
		<?php endif; ?>
		enctype="multipart/form-data">

		<?php
		// Get cart type
		$cart_type = $this->params->get('list_show_cart', 1);

		// If cart is of type 1 / Standard
		if ($cart_type == 1) :
			// Load variable options
			echo $this->loadTemplate('variableoptions');
			// Render cart
			echo $this->loadTemplate('cart'); ?>

		<?php else: ?>
			<!-- Redirect to product details page -->
			<a
				href="<?= $this->product->product_link; ?>"
				class="<?= $this->params->get(
									'choosebtn_class',
									'btn btn-success'
								); ?>">
				<!-- Link text -->
				<?= Text::_('J2STORE_VIEW_PRODUCT_DETAILS'); ?>
			</a>
		<?php endif; ?>

		<!-- Hidden input for variant ID -->
		<input
			type="hidden"
			name="variant_id"
			value="<?= $this->product->variant->j2store_variant_id; ?>" />
	</form>
<?php endif;

// Event Hook: for after display content
if (isset($this->product->event->afterDisplayContent)) {
	// Run plugins
	echo $this->product->event->afterDisplayContent;
} ?>
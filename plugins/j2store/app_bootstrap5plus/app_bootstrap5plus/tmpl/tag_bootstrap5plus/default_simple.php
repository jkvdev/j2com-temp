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

// Loading the simple product image Template
$images = $this->loadTemplate('images');

// Loading the simple product images and passes it through a plugin that may modify the content
J2Store::plugin()->event(
	'BeforeDisplayImages',
	array(
		&$images,
		$this,
		'com_j2store.products.list.bootstrap'
	)
);

// Displaying the images
echo $images; ?>

<!-- Wrapper DIV for the content -->
<div class="p-3 d-flex flex-column justify-content-between">

	<!-- Grouping main Content with a DIV -->
	<div>
		<?php
		//  Loading the title template
		echo $this->loadTemplate('title');

		//	Check if there is any content rendered after the title
		if (isset($this->product->event->afterDisplayTitle)) {
			//	Displays the content after the title
			echo $this->product->event->afterDisplayTitle;
		}

		//	Check if there is any content rendered before the title
		if (isset($this->product->event->beforeDisplayContent)) {
			//	Displays the content before the title
			echo $this->product->event->beforeDisplayContent;
		}

		// Load and display product description
		echo $this->loadTemplate('description');

		// Check whether the price should be displayed
		if (J2Store::product()->canShowprice($this->params)) {
			// Load the price template
			echo $this->loadTemplate('price');
		}

		// Check whether to show SKU or not
		if (
			$this->params->get('list_show_product_sku', 1)
			&& J2Store::product()->canShowSku($this->params)
		) {
			// Load SKU template
			echo $this->loadTemplate('sku');
		}

		// Check whether or not to show the product stock
		if (
			$this->params->get('list_show_product_stock', 1)
			&& J2Store::product()->managing_stock($this->product->variant)
		) {
			// Load the product stock template
			echo $this->loadTemplate('stock');
		} ?>
		<!-- End DIV -->
	</div>

	<?php
	// Separating Buttons
	// Check whether to display add to cart button
	if (J2Store::product()->canShowCart($this->params)): ?>
		<!-- Add to cart form -->
		<!-- Passing form data -->
		<form
			action="<?= $this->product->cart_form_action; ?>"
			method="post"
			id="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
			class="j2store-addtocart-form"
			name="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
			data-product_id="<?= $this->product->j2store_product_id; ?>"
			data-product_type="<?= $this->product->product_type; ?>"
			enctype="multipart/form-data">

			<?php
			// Retrieve the cart type
			$cart_type = $this->params->get('list_show_cart', 1);

			// If the cart type is one
			if ($cart_type == 1) :
				// Load the product options
				echo $this->loadTemplate('options');
				// Load Add to Cart Button
				echo $this->loadTemplate('cart');

			// If the cart is of type 2 & 3
			elseif (
				(
					$cart_type == 2
					&& count($this->product->options)
				)
				|| $cart_type == 3
			): ?>
				<!-- Redirect to product details page -->
				<!-- Render Link -->
				<a
					href="<?= $this->product->product_link; ?>"
					class="<?= $this->params->get('choosebtn_class', 'btn btn-success'); ?>">
					<!-- Render Link text -->
					<?= Text::_('J2STORE_VIEW_PRODUCT_DETAILS'); ?>
				</a>
			<?php else:
				// Default: Just show the add to cart button
				echo $this->loadTemplate('cart');
			endif; ?>
			<!-- End Form -->
		</form>

	<?php endif;

	// Check whether to display content after the product description
	if (isset($this->product->event->afterDisplayContent)) {
		// Display the content
		echo $this->product->event->afterDisplayContent;
	}
	?>

</div>
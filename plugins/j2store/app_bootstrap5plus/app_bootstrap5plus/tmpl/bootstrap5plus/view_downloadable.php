<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 *
 * Bootstrap 2 layout of product detail
 */
// No direct access
defined('_JEXEC') or die;
?>

<!-- Main Downloadable product div -->
<div
	class="product-<?= $this->product->j2store_product_id; ?> <?= $this->product->product_type; ?>-product">
	<!-- Grid -->
	<div class="row">
		<!-- 1 / 2 Grid -->
		<div class="col-sm-6">
			<?php

			// Load images template
			$images = $this->loadTemplate('images');
			// Trigger plugins before displaying images
			J2Store::plugin()->event(
				'BeforeDisplayImages',
				array(
					&$images,
					$this,
					'com_j2store.products.view.bootstrap'
				)
			);

			// Render images
			echo $images;
			?>
		</div>

		<!-- 1 / 2 Grid -->
		<div class="col-sm-6">
			<?php

			// Render title template
			echo $this->loadTemplate('title');

			// If there are plugins after the title
			if (isset($this->product->source->event->afterDisplayTitle)) {
				// Run plugins
				echo $this->product->source->event->afterDisplayTitle;
			} ?>

			<!-- Price, SKU & Brand Container -->
			<div class="price-sku-brand-container row">

				<?php
				// If price is enabled
				if (J2Store::product()->canShowprice($this->params)): ?>
					<!-- 1 / 2 Grid -->
					<div class="col-sm-6">
						<!-- Load price template -->
						<?= $this->loadTemplate('price'); ?>
					</div>
				<?php endif; ?>

				<!-- 1 / 2 Grid -->
				<div class="col-sm-6">
					<?php

					// Check if there are any plugins before content
					if (isset($this->product->source->event->beforeDisplayContent)) {
						// Run plugins
						echo $this->product->source->event->beforeDisplayContent;
					}

					// Check if SKU is enabled
					if (J2Store::product()->canShowSku($this->params)) {
						// Load SKU template
						echo $this->loadTemplate('sku');
					}

					// Load brand template
					echo $this->loadTemplate('brand');

					// Check if stock is enabled
					if (
						$this->params->get('item_show_product_stock', 1)
						&& J2Store::product()->managing_stock(
							$this->product->variant
						)
					) {
						// Render stock template
						echo $this->loadTemplate('stock');
					} ?>
				</div>
			</div>
			<?php

			// Check if cart is enabled
			if (J2Store::product()->canShowCart($this->params)): ?>
				<!-- Add to cart Form -->
				<form
					method="post"
					action="<?= $this->product->cart_form_action; ?>"
					id="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
					class="j2store-addtocart-form"
					name="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
					data-product_id="<?= $this->product->j2store_product_id; ?>"
					data-product_type="<?= $this->product->product_type; ?>"
					enctype="multipart/form-data">

					<!-- Load cart template -->
					<?= $this->loadTemplate('cart'); ?>

				</form>
			<?php endif; ?>
		</div>
	</div>

	<?php

	// Check if tabs are enabled
	if ($this->params->get('item_use_tabs', 1)) {
		// Load tab template
		echo $this->loadTemplate('tabs');
	} else {
		// Load no tabs
		echo $this->loadTemplate('notabs');
	}

	// Check if after content plugins are available
	if (isset($this->product->source->event->afterDisplayContent)) {
		// Run plugins
		echo $this->product->source->event->afterDisplayContent;
	} ?>
</div>

<?php

// Check if up sells are enabled
if (
	$this->params->get('item_show_product_upsells', 0)
	&& count($this->up_sells)
) {
	// Load up sells template
	echo $this->loadTemplate('upsells');
}

// Check if cross sells are enabled
if (
	$this->params->get('item_show_product_cross_sells', 0)
	&& count($this->cross_sells)
) {
	// Load cross sells template
	echo $this->loadTemplate('crosssells');
} ?>
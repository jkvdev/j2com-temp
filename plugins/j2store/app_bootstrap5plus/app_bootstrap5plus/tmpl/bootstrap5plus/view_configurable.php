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
<!-- Configurable product detail container -->
<div class="product-<?= $this->product->j2store_product_id; ?> <?= $this->product->product_type; ?>-product">
	<!-- Grid layout -->
	<div class="row">
		<!-- 1 / 2 Grid -->
		<div class="col-sm-6">
			<?php

			// Load images template
			$images = $this->loadTemplate('images');
			// Run plugins before displaying images
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

			// Render title
			echo $this->loadTemplate('title');

			// If there are any after title display plugins
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
						<!-- Render price template -->
						<?= $this->loadTemplate('price'); ?>
					</div>
				<?php endif; ?>

				<!-- 1 / 2 Grid -->
				<div class="col-sm-6">
					<?php

					// If there are any plugins before displaying content
					if (isset($this->product->source->event->beforeDisplayContent)) {
						// Run plugins
						echo $this->product->source->event->beforeDisplayContent;
					}

					// If SKU is enabled
					if (J2Store::product()->canShowSku($this->params)) {
						// Render SKU Template
						echo $this->loadTemplate('sku');
					}

					// Render brand template
					echo $this->loadTemplate('brand');

					// If stock management is enabled
					if (
						$this->params->get('item_show_product_stock', 1)
						&& J2Store::product()->managing_stock($this->product->variant)
					) {
						// Render stock template
						echo $this->loadTemplate('stock');
					} ?>
				</div>
			</div>

			<?php
			// If cart is enabled
			if (J2Store::product()->canShowCart($this->params)): ?>
				<!-- Main Add to cart form -->
				<form
					action="<?= $this->product->cart_form_action; ?>"
					method="post"
					class="j2store-addtocart-form"
					id="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
					name="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
					data-product_id="<?= $this->product->j2store_product_id; ?>"
					data-product_type="<?= $this->product->product_type; ?>"
					enctype="multipart/form-data">

					<?php
					// Render product options
					echo $this->loadTemplate('configurableoptions');
					// Render cart
					echo $this->loadTemplate('cart'); ?>
				</form>
			<?php endif; ?>
		</div>
	</div>

	<?php if ($this->params->get('item_use_tabs', 1)) {
		// Render tabs template
		echo $this->loadTemplate('tabs');
	} else {
		// Default: Render no tabs template
		echo $this->loadTemplate('notabs');
	}

	// If after display content plugins exist
	if (isset($this->product->source->event->afterDisplayContent)) {
		// Run plugins
		echo $this->product->source->event->afterDisplayContent;
	} ?>
</div>

<?php

// If up sells are enabled
if (
	$this->params->get('item_show_product_upsells', 0)
	&& count($this->up_sells)
) {
	// render up sells template
	echo $this->loadTemplate('upsells');
}

//  If cross sells are enabled
if (
	$this->params->get('item_show_product_cross_sells', 0)
	&& count($this->cross_sells)
) {
	// Render cross sells template
	echo $this->loadTemplate('crosssells');
} ?>
<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 *
 * Bootstrap 2 layout of product detail
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;

// No direct access
defined('_JEXEC') or die;

?>
<!-- Main Flex-Variable Product Details -->
<div
	class="product-<?= $this->product->j2store_product_id; ?> <?= $this->product->product_type; ?>-product">
	<!-- Grid -->
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

			// Render title template
			echo $this->loadTemplate('title');

			// If after title display plugins exist 
			if (isset($this->product->source->event->afterDisplayTitle)) {
				// Run plugins
				echo $this->product->source->event->afterDisplayTitle;
			} ?>

			<!-- Price, SKU & Grand Container -->
			<div class="price-sku-brand-container row">
				<?php

				// If price is enabled
				if (J2Store::product()->canShowprice($this->params)): ?>
					<!-- 1 / 2 Grid -->
					<div class="col-sm-6">
						<!-- Render flex price -->
						<?= $this->loadTemplate('flexiprice'); ?>
					</div>
				<?php endif; ?>

				<!-- 1 / 2 Grid -->
				<div class="col-sm-6">
					<?php

					// If there are any before content display plugins 
					if (isset($this->product->source->event->beforeDisplayContent)) {
						// Run plugins
						echo $this->product->source->event->beforeDisplayContent;
					}

					// If SKU is enabled
					if (
						$this->params->get('item_show_product_sku', 1)
						&&  J2Store::product()->canShowSku($this->params)
						&& isset($this->product->variant->sku)
						&& !empty($this->product->variant->sku)
					) : ?>
						<!-- Main SKU DIV -->
						<div class="product-sku">
							<!-- SKU text -->
							<span class="sku-text">
								<?= Text::_('J2STORE_SKU') ?> :
							</span>

							<!-- Product SKU -->
							<span class="sku">
								<?= $this->escape($this->product->variant->sku); ?>
							</span>
						</div>
					<?php

					// If SKU is enabled but not available
					elseif (
						$this->params->get('item_show_product_sku', 1)
						&&  J2Store::product()->canShowSku($this->params)
					) : ?>
						<!-- Main SKU DIV -->
						<div class="product-sku">
							<!-- SKU Text -->
							<span class="sku-text">
								<?= Text::_('J2STORE_SKU') ?>
							</span>

							<!-- Empty SKU -->
							<span class="sku"></span>
						</div>
					<?php endif;

					// Render brand template
					echo $this->loadTemplate('brand');

					// If Stock is enabled
					if ($this->params->get('item_show_product_stock', 1)) : ?>
						<!-- Main Stock container -->
						<div class="product-stock-container">
							<?php

							// If product has variants
							if (
								isset($this->product->variant)
								&& J2Store::product()->managing_stock($this->product->variant)
							):

								// If product is available
								if ($this->product->variant->availability): ?>
									<!-- Stock availability -->
									<span
										class="<?= $this->product->variant->availability ? 'instock' : 'outofstock'; ?>">
										<!-- Display stock -->
										<?= J2Store::product()->displayStock(
											$this->product->variant,
											$this->params
										); ?>
									</span>
								<?php else: ?>
									<!-- Out of stock tag -->
									<span class="outofstock">
										<!-- Text -->
										<?= Text::_('J2STORE_OUT_OF_STOCK'); ?>
									</span>
								<?php endif; ?>
							<?php else: ?>
								<!-- If stock management is empty, render empty spans -->
								<span class="instock"></span>
								<span class="outofstock"></span>
							<?php endif; ?>
						</div>

						<?php
						// Check if back order notifications are allowed
						if (
							isset($this->product->variant->allow_backorder)
							&& $this->product->variant->allow_backorder == 2
							&& !$this->product->variant->availability
						): ?>
							<!-- Back order notifications -->
							<span class="backorder-notification">
								<!-- Notification Text -->
								<?= Text::_('J2STORE_BACKORDER_NOTIFICATION'); ?>
							</span>
						<?php else: ?>
							<!-- Empty notifications -->
							<span class="backorder-notification"></span>
					<?php endif;
					endif; ?>
				</div>
			</div>

			<?php
			// If add to cart is enabled
			if (J2Store::product()->canShowCart($this->params)): ?>
				<!-- Add to cart form -->
				<form
					action="<?= $this->product->cart_form_action; ?>"
					method="post" class="j2store-addtocart-form"
					id="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
					name="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
					data-product_id="<?= $this->product->j2store_product_id; ?>"
					data-product_type="<?= $this->product->product_type; ?>"
					<?php
					// If product has variants, save them as json
					if (isset($this->product->variant_json)): ?>
					data-product_variants="<?= $this->escape($this->product->variant_json); ?>"
					<?php endif; ?>
					enctype="multipart/form-data">

					<?php
					// Load product options
					echo $this->loadTemplate('flexivariableoptions');
					// Load add to cart
					echo $this->loadTemplate('cart'); ?>

					<!-- Hidden input to store variant id -->
					<input
						type="hidden"
						name="variant_id"
						value="<?= isset($this->product->variant->j2store_variant_id)
											? $this->product->variant->j2store_variant_id
											: ''; ?>" />
				</form>
			<?php endif; ?>
		</div>
	</div>
	<?php

	// If tabs are enabled
	if ($this->params->get('item_use_tabs', 1)) {
		// Render tabs
		echo $this->loadTemplate('tabs');
	} else {
		// Render no tabs
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
	// Render up sells
	echo $this->loadTemplate('upsells');
}

// If cross sells are enabled
if (
	$this->params->get('item_show_product_cross_sells', 0)
	&& count($this->cross_sells)
) {
	// Render cross sells
	echo $this->loadTemplate('crosssells');
} ?>
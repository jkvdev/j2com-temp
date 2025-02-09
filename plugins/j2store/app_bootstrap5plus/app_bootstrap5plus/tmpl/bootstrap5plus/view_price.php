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

// Trigger plugins before the product price
echo J2Store::plugin()->eventWithHtml(
	'BeforeRenderingProductPrice',
	array($this->product)
);

// If price is enabled
if (
	$this->params->get('item_show_product_base_price', 1)
	|| $this->params->get('item_show_product_special_price', 1)
): ?>
	<!-- Main Price Container -->
	<div class="product-price-container d-flex flex-row-reverse justify-content-between my-3">
		<?php

		// If base price is enabled
		if (
			$this->params->get('item_show_product_base_price', 1)
			&& isset($this->product->pricing->base_price)
			&& isset($this->product->pricing->price)
			&& $this->product->pricing->base_price
			!= $this->product->pricing->price
		):

			// Set class to empty
			$class = '';

			// If there is a discount set the class to strike
			if (isset($this->product->pricing->is_discount_pricing_available)) $class = 'strike';

			// getting base price
			$base_price = J2Store::product()->displayPrice(
				$this->product->pricing->base_price,
				$this->product,
				$this->params
			); ?>
			<!-- Base price container -->
			<div class="base-price <?= $class ?> fs-5 fw-medium">
				<!-- Base price text -->
				<?= $base_price; ?>
			</div>
		<?php endif;

		// If there is a special price
		if ($this->params->get('item_show_product_special_price', 1)): ?>
			<!-- Sale price DIV -->
			<div class="sale-price fs-1">
				<?php

				// If sale price exists, display it
				if (isset(
					$this->product->pricing->price
				)) echo J2Store::product()->displayPrice(
					$this->product->pricing->price,
					$this->product,
					$this->params
				); ?>
			</div>
		<?php endif;

		// Optional: If tax info is enabled
		if ($this->params->get('display_price_with_tax_info', 0)): ?>
			<!-- Main tax div -->
			<div class="tax-text">
				<!-- Tax Text -->
				<?= J2Store::product()->get_tax_text(); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif;

// Trigger plugins after rendering the price
echo J2Store::plugin()->eventWithHtml(
	'AfterRenderingProductPrice',
	array($this->product)
);

// If discount percentage is set
if ($this->params->get('item_show_discount_percentage', 1)): ?>
	<!-- Discount percentage div -->
	<div class="discount-percentage d-flex justify-content-end fs-5 fw-semibold">
		<?php

		// Check if discount is available
		if (
			isset($this->product->pricing->is_discount_pricing_available)
			&& isset($this->product->pricing->base_price)
			&& !empty($this->product->pricing->base_price)
			&& $this->product->pricing->base_price > 0
		) {
			// Calculate discount
			$discount = (1 - ($this->product->pricing->price / $this->product->pricing->base_price)) * 100;

			// If discount exists
			if ($discount > 0) {
				echo Text::sprintf(
					'J2STORE_PRODUCT_OFFER',
					round($discount) . '%'
				);
			}
		} ?>
	</div>
<?php endif; ?>
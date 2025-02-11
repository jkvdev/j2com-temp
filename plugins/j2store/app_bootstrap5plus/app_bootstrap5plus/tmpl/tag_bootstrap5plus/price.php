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

// Get Product details
$product = $this->singleton_product;
// Get Price configuration settings
$params = $this->singleton_params;

// Trigger plugin events before rendering the price
echo J2Store::plugin()->eventWithHtml(
	'BeforeRenderingProductPrice',
	array($product)
);

// Checking price display settings
if (
	$params->get('item_show_product_base_price', 1)
	|| $params->get('item_show_product_special_price', 1)
):
?>
	<!-- Price Container -->
	<div class="product-price-container">
		<?php

		// Check if base price should be shown
		if (
			$params->get('item_show_product_base_price', 1)
			&& isset($product->pricing->base_price)
			&& isset($product->pricing->price)
			&& $product->pricing->base_price
			!= $product->pricing->price
		):
			// Set class to empty
			$class = '';

			// If discount is available
			// Set class to strike
			if (isset($product->pricing->is_discount_pricing_available)) $class = 'strike'; ?>
			<!-- Base price container -->
			<div class="base-price <?= $class ?>">
				<!-- Format and display price -->
				<span class="product-element-value">
					<?= J2Store::product()->displayPrice(
						$product->pricing->base_price,
						$product,
						$params
					); ?>
				</span>
			</div>
		<?php endif;

		// Checking if sale price is enabled
		if (
			$params->get('item_show_product_special_price', 1)
			&& isset($product->pricing->price)
		): ?>
			<!-- Sale price div -->
			<div class="sale-price">
				<!-- Render formatted price -->
				<span class="product-element-value">
					<?= J2Store::product()->displayPrice(
						$product->pricing->price,
						$product,
						$params
					); ?>
				</span>
			</div>
		<?php endif;

		// Optional: Display tax information
		if ($params->get('display_price_with_tax_info', 0)): ?>
			<!-- Tax Information -->
			<div class="tax-text">
				<!-- Text -->
				<?= J2Store::product()->get_tax_text(); ?>
			</div>
		<?php endif; ?>

	</div>
<?php endif;

// Trigger plugin events after price rendering
echo J2Store::plugin()->eventWithHtml(
	'AfterRenderingProductPrice',
	array($product)
);

// Display discount percentage if available
if ($params->get('item_show_discount_percentage', 1)): ?>
	<!-- Discount percentage DIV -->
	<div class="discount-percentage">
		<?php

		// Check if product has a valid base price and discount
		if (
			isset($product->pricing->is_discount_pricing_available)
			&& isset($product->pricing->base_price)
			&& !empty($product->pricing->base_price)
		) {

			// Calculate discount
			$discount = (1 - ($product->pricing->price / $product->pricing->base_price)) * 100;

			// If discount exists
			if ($discount > 0) {
				// Display discount percentage
				echo Text::sprintf(
					'J2STORE_PRODUCT_OFFER',
					round($discount) . '%'
				);
			}
		} ?>
	</div>
<?php endif; ?>
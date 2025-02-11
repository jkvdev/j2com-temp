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


// DISPLAYING SALE AND BASE RICE -->
// Plugin Event: modifying / extending display price through custom plugins -->
echo J2Store::plugin()->eventWithHtml(
	'BeforeRenderingProductPrice',
	array(
		$this->product
	)
);

// Check if either the base or special price should be displayed
if (
	$this->params->get('list_show_product_base_price', 1)
	|| $this->params->get('list_show_product_special_price', 1)
): ?>
	<!-- Main Product Price -->
	<div class="product-price-container d-flex flex-row w-full justify-content-between">
		<?php

		// Checks when to show the base price
		if (
			$this->params->get('list_show_product_base_price', 1)
			&& isset($this->product->pricing->base_price)
			&& isset($this->product->pricing->price)
			&& $this->product->pricing->base_price
			!= $this->product->pricing->price
		):
			// Set class to empty
			$class = '';

			// Check for available discounts
			if (isset($this->product->pricing->is_discount_pricing_available)) {
				$class = 'strike';
			}
		?>
			<!-- Base Price Div -->
			<div class="base-price <?= $class ?>">
				<!-- Display the price -->
				<?= J2Store::product()->displayPrice(
					$this->product->pricing->base_price,
					$this->product,
					$this->params
				); ?>
			</div>
		<?php endif;

		// Check if there is a special price
		if ($this->params->get('list_show_product_special_price', 1)): ?>
			<!-- Sale Price Div -->
			<div class="sale-price">
				<?php

				// Display the sale price
				if (
					isset(
						$this->product->pricing->price
					)
				) echo J2Store::product()->displayPrice(
					$this->product->pricing->price,
					$this->product,
					$this->params
				); ?>
			</div>
		<?php endif;

		// Optional: Display Price with Tax
		if ($this->params->get('display_price_with_tax_info', 0)): ?>
			<!-- Tax Price Div -->
			<div class="tax-text">
				<!-- Render Tax Price -->
				<?= J2Store::product()->get_tax_text(); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif;

// DISPLAYING DISCOUNT PERCENTAGE 
// Plugin Event: modifying / extending display price through custom plugins
echo J2Store::plugin()->eventWithHtml(
	'AfterRenderingProductPrice',
	array(
		$this->product
	)
);

// Checking if there is a discount percentage
if ($this->params->get('list_show_discount_percentage', 1)): ?>
	<!-- Discount Percentage Div -->
	<div class="discount-percentage">
		<?php

		// Check if discount is available
		if (
			isset(
				$this->product->pricing->is_discount_pricing_available
			)
			&& isset($this->product->pricing->base_price)
			&& !empty($this->product->pricing->base_price)
			&& $this->product->pricing->base_price > 0
		) {

			// Calculating Discount %
			$discount = (1 - ($this->product->pricing->price / $this->product->pricing->base_price)) * 100;

			// If the discount is bigger than 0
			if ($discount > 0) {
				// Display the discount percentage
				echo Text::sprintf('J2STORE_PRODUCT_OFFER', round($discount) . '%');
			}
		}
		?>
	</div>
<?php endif; ?>
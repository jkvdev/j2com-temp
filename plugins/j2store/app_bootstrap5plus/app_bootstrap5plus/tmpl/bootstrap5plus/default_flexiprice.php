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

// Get currency instance
$currency = J2Store::currency();

// Run plugins before price rendering
echo J2Store::plugin()->eventWithHtml(
    'BeforeRenderingProductPrice',
    array($this->product)
);

// Get Minimum price
$min_price = (isset($this->product->min_price)
    && !empty($this->product->min_price))
    ? J2Store::product()->displayPrice(
        $this->product->min_price,
        $this->product,
        $this->params
    )
    : $currency->format(0);

// Get Maximum price
$max_price = (isset($this->product->max_price)
    && !empty($this->product->max_price))
    ? J2Store::product()->displayPrice(
        $this->product->max_price,
        $this->product,
        $this->params
    )
    : $currency->format(0); ?>

<!-- Display price range -->
<div class="flexi-product-price-range">
    <strong>
        <?= Text::_('J2STORE_PRODUCT_PRICE_RANGE'); ?>
    </strong>
    <strong>
        <?= Text::sprintf('J2STORE_PRICE_RANGE_FROM_TO', $min_price, $max_price); ?>
    </strong>
</div>

<?php
// Display base price & special price
if (
    $this->params->get('list_show_product_base_price', 1)
    || $this->params->get('list_show_product_special_price', 1)
): ?>
    <!-- Product price container -->
    <div class="product-price-container">
        <?php

        // Check if base price should be displayed
        if (
            $this->params->get('list_show_product_base_price', 1)
            && isset($this->product->pricing->base_price)
            && isset($this->product->pricing->price)
            &&  $this->product->pricing->base_price
            != $this->product->pricing->price
        ):

            // Set class to empty
            $class = '';

            // If product is on discount
            // Set class to strike
            if (isset(
                $this->product->pricing->is_discount_pricing_available
            )) $class = 'strike'; ?>
            <!-- Base price DIV -->
            <div class="base-price <?= $class ?>">
                <!-- Display price -->
                <?= J2Store::product()->displayPrice(
                    $this->product->pricing->base_price,
                    $this->product,
                    $this->params
                ); ?>
            </div>
        <?php

        // If only base price is available
        elseif ($this->params->get('list_show_product_base_price', 1)):
            // Set class to empty 
            $class = ''; ?>
            <!-- Base class DIV -->
            <div class="base-price <?= $class ?>">
            </div>
        <?php endif;

        // Check if to display special sale price
        if ($this->params->get('list_show_product_special_price', 1)): ?>
            <!-- Sale price DIV -->
            <div class="sale-price">
                <?php
                // Display price
                if (isset($this->product->pricing->price)) echo J2Store::product()->displayPrice(
                    $this->product->pricing->price,
                    $this->product,
                    $this->params
                ); ?>
            </div>
        <?php endif;

        // Display tax information if enabled
        if ($this->params->get('display_price_with_tax_info', 0)): ?>
            <!-- Tax DIV -->
            <div class="tax-text">
                <!-- Render Text -->
                <?= J2Store::product()->get_tax_text(); ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif;

// Run Plugins after rendering product price
echo J2Store::plugin()->eventWithHtml(
    'AfterRenderingProductPrice',
    array($this->product)
);

// If discount percentage is enabled
if ($this->params->get('list_show_discount_percentage', 1)): ?>
    <!-- Discount Percentage -->
    <div class="discount-percentage">
        <?php

        // Validating discount conditions
        if (
            isset($this->product->pricing->is_discount_pricing_available)
            && isset($this->product->pricing->base_price)
            && !empty($this->product->pricing->base_price)
            && $this->product->pricing->base_price > 0
        ):
            // Calculating discount percentage
            $discount = (1 - ($this->product->pricing->price / $this->product->pricing->base_price)) * 100;

            // If discount exists
            if ($discount > 0):
                // Render discount value rounded
                echo Text::sprintf(
                    'J2STORE_PRODUCT_OFFER',
                    round($discount) . '%'
                );
            endif;
        endif; ?>
    </div>
<?php endif; ?>
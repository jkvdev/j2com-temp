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

// Get currency settings from J2Store
$currency = J2Store::currency();

// Run plugins before rendering price
echo J2Store::plugin()->eventWithHtml(
  'BeforeRenderingProductPrice',
  array(
    $this->product
  )
);

// Get min price
$min_price = (isset($this->product->min_price)
  && !empty($this->product->min_price))
  ? J2Store::product()->displayPrice(
    $this->product->min_price,
    $this->product,
    $this->params
  )
  : $currency->format(0);

// Get max price
$max_price = (isset($this->product->max_price)
  && !empty($this->product->max_price))
  ? J2Store::product()->displayPrice(
    $this->product->max_price,
    $this->product,
    $this->params
  )
  : $currency->format(0); ?>

<!-- Display product price range -->
<div class="flexi-product-price-range">
  <!-- Product range text -->
  <strong><?= Text::_('J2STORE_PRODUCT_PRICE_RANGE'); ?></strong>

  <!-- Show product range -->
  <strong><?= Text::sprintf('J2STORE_PRICE_RANGE_FROM_TO', $min_price, $max_price); ?> </strong>
</div>

<?php
// Check if base or special price should be shown
if (
  $this->params->get('item_show_product_base_price', 1)
  || $this->params->get('item_show_product_special_price', 1)
): ?>
  <!-- Product price container -->
  <div class="product-price-container">
    <?php

    // If base price exists and is different from final price
    if (
      $this->params->get('item_show_product_base_price', 1)
      && isset($this->product->pricing->base_price)
      && isset($this->product->pricing->price)
      && $this->product->pricing->base_price
      != $this->product->pricing->price
    ):
      // Set class to empty
      $class = '';

      // Apply strike class if discount is available
      if (isset(
        $this->product->pricing->is_discount_pricing_available
      )) $class = 'strike';

      // Get base price
      $base_price = J2Store::product()->displayPrice(
        $this->product->pricing->base_price,
        $this->product,
        $this->params
      ); ?>

      <!-- Base price DIV -->
      <div class="base-price <?= $class ?>">
        <!-- Base price -->
        <?= $base_price; ?>
      </div>
    <?php

    // If base price is enabled
    elseif ($this->params->get('item_show_product_base_price', 1)):

      // Set class to empty
      $class = ''; ?>
      <!-- Empty Base Price DIV -->
      <div class="base-price <?= $class ?>"></div>
    <?php endif;

    // If special price is enabled
    if ($this->params->get('item_show_product_special_price', 1)): ?>
      <!-- Sale Price DIV -->
      <div class="sale-price">
        <?php
        // Display final / sale price
        if (isset($this->product->pricing->price)) {
          // Display price
          echo J2Store::product()->displayPrice(
            $this->product->pricing->price,
            $this->product,
            $this->params
          );
        } ?>
      </div>
    <?php endif;

    // Optional: Display tax information
    if ($this->params->get('display_price_with_tax_info', 0)): ?>
      <!-- Tax Text DIV -->
      <div class="tax-text">
        <!-- Text -->
        <?= J2Store::product()->get_tax_text(); ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif;

// Run plugins after rendering product price
echo J2Store::plugin()->eventWithHtml(
  'AfterRenderingProductPrice',
  array(
    $this->product
  )
);

// If discount is enabled
if ($this->params->get('item_show_discount_percentage', 1)): ?>
  <!-- Main Discount DIV -->
  <div class="discount-percentage">
    <?php

    // Check if discount can be calculate
    if (
      isset($this->product->pricing->is_discount_pricing_available)
      && isset($this->product->pricing->base_price)
      && !empty($this->product->pricing->base_price)
      && $this->product->pricing->base_price > 0
    ):
      // Calculate Discount
      $discount = (1 - ($this->product->pricing->price / $this->product->pricing->base_price)) * 100;
      // If discount exists
      if ($discount > 0): ?>
        <!-- Discount percentage DIV -->
        <div class="discount-percentage">
          <!-- Render Discount -->
          <?= Text::sprintf('J2STORE_PRODUCT_OFFER', round($discount) . '%'); ?>
        </div>
    <?php endif;
    endif; ?>
  </div>
<?php endif; ?>
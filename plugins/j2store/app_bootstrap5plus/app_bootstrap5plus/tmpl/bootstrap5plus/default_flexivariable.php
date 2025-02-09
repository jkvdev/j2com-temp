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

// Render images
echo $images;

// Render title template
echo $this->loadTemplate('title');

// If there are any plugins after the title
if (isset($this->product->event->afterDisplayTitle)) {
    // Run plugins
    echo $this->product->event->afterDisplayTitle;
}

// If there are any plugins before the content
if (isset($this->product->event->beforeDisplayContent)) {
    // Run plugins
    echo $this->product->event->beforeDisplayContent;
}

// Render description template
echo $this->loadTemplate('description');

// If price is enabled
if (J2Store::product()->canShowprice($this->params)) {
    // SHow flex price
    echo $this->loadTemplate('flexiprice');
}

// If SKU is enabled
if (
    $this->params->get('list_show_product_sku', 1)
    &&  J2Store::product()->canShowSku($this->params)
    && isset($this->product->variant->sku)
    && !empty($this->product->variant->sku)
) : ?>
    <!-- SKU DIV -->
    <div class="product-sku">
        <!-- SKU Text -->
        <span class="sku-text">
            <!-- Text -->
            <?= Text::_('J2STORE_SKU') ?> :
        </span>

        <!-- Product SKU -->
        <span class="sku">
            <!-- Text -->
            <?= $this->escape($this->product->variant->sku); ?>
        </span>
    </div>
<?php

// If SKU is available 
// Display it
elseif (
    $this->params->get('list_show_product_sku', 1)
    &&  J2Store::product()->canShowSku($this->params)
) : ?>
    <!-- Product SKU -->
    <div class="product-sku">
        <!-- SKU text -->
        <span class="sku-text">
            <!-- Text -->
            <?= Text::_('J2STORE_SKU') ?>
        </span>
        <!-- Empty DIV -->
        <span class="sku"></span>
    </div>
<?php endif;

// If product stock is enabled
if ($this->params->get('list_show_product_stock', 1)) : ?>
    <!-- Product stock DIV -->
    <div class="product-stock-container">
        <?php

        // Check if stock management is enbaled and variant exists
        if (
            isset($this->product->variant)
            && J2Store::product()->managing_stock(
                $this->product->variant
            )
        ):
            // Check if product is available
            if ($this->product->variant->availability): ?>
                <!-- Tag -->
                <span
                    class="<?= $this->product->variant->availability
                                ? 'instock'
                                : 'outofstock'; ?>">
                    <!-- Stock Quantity -->
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
            <!-- If no stock management -->
            <!-- Leave empty -->
            <span class="instock"></span>
            <span class="outofstock"></span>
        <?php endif; ?>
    </div>
    <?php

    // Display backdor notifications if enabled
    if (
        isset($this->product->variant->allow_backorder)
        && isset($this->product->variant->availability)
        && $this->product->variant->allow_backorder == 2
        && !$this->product->variant->availability
    ): ?>
        <!-- Notification span -->
        <span class="backorder-notification">
            <!-- Notification text -->
            <?= Text::_('J2STORE_BACKORDER_NOTIFICATION'); ?>
        </span>
    <?php else: ?>
        <!-- If no notifications -->
        <!-- Leave empty -->
        <span class="backorder-notification"></span>
    <?php endif;
endif;

// If price cart is enabled
if (J2Store::product()->canShowCart($this->params)): ?>
    <!-- Add to cart form -->
    <form
        action="<?= $this->product->cart_form_action; ?>"
        method="post"
        id="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
        class="j2store-addtocart-form"
        name="j2store-addtocart-form-<?= $this->product->j2store_product_id; ?>"
        data-product_id="<?= $this->product->j2store_product_id; ?>"
        data-product_type="<?= $this->product->product_type; ?>"
        <?php

        // If product has variants
        // Store them as JSON data
        if (isset($this->product->variant_json)): ?>
        data-product_variants="<?= $this->escape($this->product->variant_json); ?>"
        <?php endif; ?>
        enctype="multipart/form-data">

        <?php

        // Get Cart Type
        $cart_type = $this->params->get('list_show_cart', 1);

        // If cart is of type 1 / Standard
        if ($cart_type == 1) :
            // Load product options
            echo $this->loadTemplate('flexivariableoptions');
            // Load cart
            echo $this->loadTemplate('cart'); ?>

        <?php else: ?>
            <!-- Default: Redirect to product details page -->
            <a
                href="<?= $this->product->product_link; ?>"
                class="<?= $this->params->get(
                            'choosebtn_class',
                            'btn btn-success'
                        ); ?>">
                <!-- Link Text -->
                <?= Text::_('J2STORE_VIEW_PRODUCT_DETAILS'); ?>
            </a>
        <?php endif; ?>

        <!-- Hidden input: store variant ID -->
        <input
            type="hidden"
            name="variant_id"
            value="<?= isset($this->product->variant->j2store_variant_id)
                        ? $this->product->variant->j2store_variant_id
                        : ''; ?>" />
    </form>
<?php endif;

// Event Hook: plugins after display content
if (isset($this->product->event->afterDisplayContent)) {
    // Run plugins
    echo $this->product->event->afterDisplayContent;
} ?>
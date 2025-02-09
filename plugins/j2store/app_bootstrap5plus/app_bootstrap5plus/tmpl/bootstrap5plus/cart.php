<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// No direct access
defined('_JEXEC') or die;

// Product & Parameter initialization
$product = $this->singleton_product;
$params = $this->singleton_params;
// Cart action URL
$action = "index.php?option=com_j2store&view=carts&task=addItem&product_id={$product->j2store_product_id}";

// Before Add to cart event
echo J2Store::plugin()->eventWithHtml(
	'BeforeAddToCartButton',
	array(
		$product,
		J2Store::utilities()->getContext('cart')
	)
);
?>
<!-- Cart Action Confirmation Message - Initially hidden -->
<div class="cart-action-complete" style="display:none;">
	<!-- Success Message -->
	<p class="text-success">
		<!-- Render Message -->
		<?= Text::_('J2STORE_ITEM_ADDED_TO_CART'); ?>
		<!-- Render Checkout Link -->
		<a
			href="<?= $product->checkout_link; ?>"
			class="j2store-checkout-link">
			<!-- Render Link Text -->
			<?= Text::_('J2STORE_CHECKOUT'); ?>
		</a>
	</p>
</div>

<!-- Main Add to cart button -->
<a
	class="<?= $params->get('addtocart_button_class', 'btn btn-primary'); ?> j2store_add_to_cart_button"
	href="<?= Route::_($action); ?>"
	data-quantity="1"
	data-product_id="<?= $product->j2store_product_id; ?>"
	rel="nofollow">
	<!-- Button Text -->
	<?= $this->singleton_cartext; ?>
</a>

<?php
// Allow Plugins to load content after the button
echo J2Store::plugin()->eventWithHtml('AfterAddToCartButton', array($product, J2Store::utilities()->getContext('cart'))); ?>
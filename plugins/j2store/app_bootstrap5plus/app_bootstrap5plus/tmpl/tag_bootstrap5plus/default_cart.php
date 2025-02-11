<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

// No direct access
defined('_JEXEC') or die;

// Check if the product has a custom "Add to Cart" button text
if (!empty($this->product->addtocart_text)) {
	// Use custom text
	$cart_text = Text::_(
		$this->product->addtocart_text
	);
} else {
	// Use default text
	$cart_text = Text::_('J2STORE_ADD_TO_CART');
}

// Check if the product is available for purchase
// If the product has different variables set it to true
$show = J2Store::product()->validateVariableProduct($this->product);

// Event Hook: allows plugins to inject content before the Add to Cart Button
echo J2Store::plugin()->eventWithHtml(
	'BeforeAddToCartButton',
	array(
		$this->product,
		J2Store::utilities()->getContext('default_cart')
	)
);

// Display the add to cart section
// If the product has variables show:
if ($show): ?>
	<!-- Display a success message when the item is added to cart -->
	<!-- Initially hidden and dynamically displayed -->
	<div class="cart-action-complete" style="display:none;">
		<p class="text-success">
			<!-- Success message -->
			<?= Text::_('J2STORE_ITEM_ADDED_TO_CART'); ?>
			<!-- Checkout Link -->
			<a href="<?= $this->product->checkout_link; ?>" class="j2store-checkout-link">
				<!-- Checkout text -->
				<?= Text::_('J2STORE_CHECKOUT'); ?>
			</a>
		</p>
	</div>

	<!-- Add to cart div with a unique id -->
	<div
		id="add-to-cart-<?= $this->product->j2store_product_id; ?>"
		class="j2store-add-to-cart d-flex gap-2 align-items-stretch m-0 flex-row-reverse">

		<!-- 
		//	TODO: EDIT BOOTSTRAP HERE
		-->

		<!-- Display quantity selector -->
		<?= J2Store::product()->displayQuantity(
			'com_j2store.productlist.bootstrap3',
			$this->product,
			$this->params,
			array(
				'class' => 'input-mini form-control p-1',
				'style' => ' padding: 2px 4px !important;'
			)
		); ?>

		<!-- Hidden field for product id -->
		<input
			type="hidden"
			name="product_id"
			value="<?= $this->product->j2store_product_id; ?>" />

		<!-- Main Add to Cart Button -->
		<input
			type="submit"
			value="<?= $cart_text; ?>"
			data-cart-action-always="<?= Text::_('J2STORE_ADDING_TO_CART'); ?>"
			data-cart-action-done="<?= $cart_text; ?>"
			data-cart-action-timeout="1000"
			class="j2store-cart-button w-100 fw-semibold <?= $this->params->get('addtocart_button_class', 'btn btn-primary'); ?>" />
	</div>
<?php
// If product is out of stock
else: ?>
	<!-- Display an Out of Stock Button -->
	<input
		type="button"
		value="<?= Text::_('J2STORE_OUT_OF_STOCK'); ?>"
		class="j2store_button_no_stock btn btn-warning" />
<?php endif;

// Event Hook: allow plugins to modify / add content content after the add to cart
echo J2Store::plugin()->eventWithHtml('AfterAddToCartButton', array($this->product, J2Store::utilities()->getContext('default_cart'))); ?>

<!-- Hidden Fields for submission -->
<!-- Target J2STore -->
<input type="hidden" name="option" value="com_j2store" />
<!-- Modify Cart -->
<input type="hidden" name="view" value="carts" />
<!-- Specify Action -->
<input type="hidden" name="task" value="addItem" />
<!-- Disable AJAX -->
<input type="hidden" name="ajax" value="0" />

<?php
// Generate a hidden security token to Cross-Site Request Forgery (CSRF) attacks
echo HTMLHelper::_('form.token'); ?>
<!-- Redirect after adding to cart -->
<input type="hidden" name="return" value="<?= base64_encode(Uri::getInstance()->toString()); ?>" />

<!-- Empty div for notifications -->
<div class="j2store-notifications"></div>
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
use Joomla\CMS\Factory;

// No direct access
defined('_JEXEC') or die;

// Define add to cart button text
// If product has custom text
if (!empty($this->product->addtocart_text)) {
	// Set custom text
	$cart_text = Text::_($this->product->addtocart_text);
} else {
	// Get default text
	$cart_text = Text::_('J2STORE_ADD_TO_CART');
}

// Check if product can be added to cart
$show = J2Store::product()->validateVariableProduct($this->product);

// Trigger plugins before add to cart
echo J2Store::plugin()->eventWithHtml(
	'BeforeAddToCartButton',
	array(
		$this->product,
		J2Store::utilities()->getContext('view_cart')
	)
);

// If product is available for purchase
if ($show): ?>
	<!-- Main SUccess message container -->
	<div
		class="cart-action-complete"
		style="display:none;">
		<!-- Success message -->
		<p class="text-success">
			<?php

			// Render text
			echo Text::_('J2STORE_ITEM_ADDED_TO_CART');

			// If quick view is enabled
			// Display a checkout button
			if (
				$this->params->get('list_enable_quickview', 0)
				&& Factory::getApplication()->input->getString('tmpl')
				== 'component'
			): ?>
				<!-- Quick View Link -->
				<a
					href="<?= $this->product->checkout_link; ?>" class="j2store-checkout-link"
					target="_top">
				<?php else: ?>
					<!-- Normal Link -->
					<a href="<?= $this->product->checkout_link; ?>" class="j2store-checkout-link">
					<?php endif;
				// Render Link Text
				echo Text::_('J2STORE_CHECKOUT'); ?>
					</a>
		</p>
	</div>

	<!-- Main Add to cart form -->
	<div
		id="add-to-cart-<?= $this->product->j2store_product_id; ?>"
		class="j2store-add-to-cart d-flex flex-row-reverse justify-content-end gap-3">

		<!-- Render quantity selector -->
		<?= J2Store::product()->displayQuantity(
			'com_j2store.product.bootstrap3',
			$this->product,
			$this->params,
			array(
				'class' => 'input-mini form-control '
			)
		); ?>

		<!-- Hidden input for product ID -->
		<input
			type="hidden"
			id="j2store_product_id"
			name="product_id"
			value="<?= $this->product->j2store_product_id; ?>" />

		<!-- Main Add to cart Button -->
		<button
			type="submit"
			class="j2store-cart-button w-100 fw-medium fs-5 py-2
			<?= $this->params->get(
				'addtocart_button_class',
				'btn btn-primary'
			); ?>"
			data-cart-action-always="<?= Text::_('J2STORE_ADDING_TO_CART'); ?>"
			data-cart-action-done="<?= $cart_text; ?>"
			data-cart-action-timeout="1000">
			<!-- Button Text -->
			<?= $cart_text; ?>
		</button>


	</div>
<?php
// Display an out of stock warning
else: ?>
	<!-- Out of stock warning -->
	<input
		value="<?= Text::_('J2STORE_OUT_OF_STOCK'); ?>"
		type="button"
		class="j2store_button_no_stock btn btn-warning" />
<?php endif;

// Trigger plugins after Add to cart
echo J2Store::plugin()->eventWithHtml(
	'AfterAddToCartButton',
	array(
		$this->product,
		J2Store::utilities()->getContext('view_cart')
	)
); ?>

<!-- Hidden fields for form submission -->
<input type="hidden" name="option" value="com_j2store" />
<input type="hidden" name="view" value="carts" />
<input type="hidden" name="task" value="addItem" />
<input type="hidden" name="ajax" value="0" />

<?php
// Joomla security token
echo HTMLHelper::_('form.token'); ?>

<!-- Store current URL -->
<!-- User is redirected after adding the product -->
<input
	type="hidden"
	name="return"
	value="<?= base64_encode(Uri::getInstance()->toString()); ?>" />

<!-- Empty div for notification -->
<div class="j2store-notifications"></div>
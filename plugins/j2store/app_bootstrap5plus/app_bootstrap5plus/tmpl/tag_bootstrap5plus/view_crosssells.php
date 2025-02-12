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

// Get number of columns
$columns = $this->params->get('item_related_product_columns', 3);
// Get total of products
$total = count($this->cross_sells);
// Initialize a counter
$counter = 0;
// Get Image width
$cross_image_width = $this->params->get(
	'item_product_cross_image_width',
	'100'
);
// Get instance of J2STore platform
$platform = J2Store::platform();
?>

<!-- Main Cross sells DIV -->
<div class="row product-crosssells-container">
	<!-- Full span -->
	<div class="col-sm-12">
		<!-- Title -->
		<h3>
			<!-- Text -->
			<?= Text::_('J2STORE_RELATED_PRODUCTS_CROSS_SELLS'); ?>
		</h3>

		<?php

		// Loop through cross sell products
		foreach ($this->cross_sells as $cross_sell_product):

			// Generate product page link
			$cross_sell_product->product_link = $platform->getProductUrl(
				array(
					'task' => 'view',
					'id' => $cross_sell_product->j2store_product_id
				),
				true
			);

			// Getting the add to cart text
			if (!empty($cross_sell_product->addtocart_text)) {
				// Set custom if exists
				$cart_text = Text::_($cross_sell_product->addtocart_text);
			} else {
				// Set default text otherwise
				$cart_text = Text::_('J2STORE_ADD_TO_CART');
			}

			// Get product name
			$cross_product_name = $this->escape(
				$cross_sell_product->product_name
			);

			// Calculate row count
			$rowcount = ((int) $counter % (int) $columns) + 1;

			// If this is the first product in the row
			if ($rowcount == 1) :
				// Get row number
				$row = $counter / $columns; ?>
				<!-- Render new row -->
				<div class="crosssell-product-row <?= 'row-' . $row; ?> row">
				<?php endif;

			// Initialize custom css class
			$cross_sell_css = '';

			// If the product doesn't have variants
			if (!in_array(
				$cross_sell_product->product_type,
				array(
					'variable',
					'flexivariable'
				)
			)) {
				// Get custom product css
				$cross_sell_css = $cross_sell_product->params->get(
					'product_css_class',
					''
				);
			} ?>

				<!-- Main Product DIV -->
				<div
					class="col-sm-<?= round((12 / $columns)); ?> crosssell-product product-<?= $cross_sell_product->j2store_product_id; ?> <?= isset($cross_sell_css) ? $cross_sell_css : ''; ?>  ">

					<!-- Product Image -->
					<span class="cross-sell-product-image">
						<?php

						// Initialize thumbnail image
						$thumb_image = '';

						// If thumbnail image exists
						if (
							isset($cross_sell_product->thumb_image)
							&& $cross_sell_product->thumb_image
						) {
							// Get thumbnail image
							$thumb_image = $platform->getImagePath(
								$cross_sell_product->thumb_image
							);
						}

						// If thumbnail image exists
						if (isset($thumb_image) &&  !empty($thumb_image)): ?>
							<!-- Image Link -->
							<a
								href="<?= $cross_sell_product->product_link; ?>">
								<!-- Image -->
								<img
									title="<?= $cross_product_name; ?>"
									alt="<?= $cross_product_name; ?>"
									class="j2store-product-thumb-image-<?= $cross_sell_product->j2store_product_id; ?>"
									src="<?= $thumb_image; ?>"
									width="<?= intval($cross_image_width); ?>" />
							</a>
						<?php endif; ?>
					</span>

					<!-- Product Title -->
					<h3 class="cross-sell-product-title">
						<!-- Link to details page -->
						<a
							href="<?= $cross_sell_product->product_link; ?>">
							<!-- Text -->
							<?= $cross_product_name; ?>
						</a>
					</h3>

					<?php
					// If price is set to show
					if (J2Store::product()->canShowprice($this->params)) {
						// Get product
						$this->singleton_product = $cross_sell_product;
						// Get params
						$this->singleton_params = $this->params;

						// Load price template
						echo $this->loadAnyTemplate(
							'site:com_j2store/products/price'
						);
					}

					// If cart is enabled
					if (J2Store::product()->canShowCart($this->params)):

						// If product has options
						if (
							count($cross_sell_product->options)
							|| $cross_sell_product->product_type == 'variable'
						): ?>
							<!-- Link to product page -->
							<a class="<?= $this->params->get(
													'choosebtn_class',
													'btn btn-success'
												); ?>"
								href="<?= $cross_sell_product->product_link; ?>">
								<!-- Render text -->
								<?= Text::_('J2STORE_CART_CHOOSE_OPTIONS'); ?>
							</a>
					<?php else:
							// Get product
							$this->singleton_product = $cross_sell_product;
							// Get params
							$this->singleton_params = $this->params;
							// Get cart custom text
							$this->singleton_cartext = $this->escape($cart_text);

							// Load cart template
							echo $this->loadAnyTemplate(
								'site:com_j2store/products/cart'
							);

						endif;
					endif; ?>
				</div>

				<?php
				// Increment counter
				$counter++;
				// If this is the last product in the row or the last item
				if (($rowcount == $columns) or ($counter == $total)) : ?>
					<!-- End row -->
				</div>
		<?php endif;
			endforeach; ?>
	</div>
</div>
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

// Retrieving up sell product parameters
// Get how many products to display per row
$columns = $this->params->get('item_related_product_columns', 3);
// Get total number of products
$total = count($this->up_sells);
// Initialize a counter
$counter = 0;
// Get the up sell image width
$upsell_image_width = $this->params->get(
	'item_product_upsell_image_width',
	'100'
);
// Initialize J2STore platform / session
$platform = J2Store::platform();
?>
<!-- Main Up Sell Container -->
<div class="row product-upsells-container">
	<!-- Full Span -->
	<div class="col-sm-12">
		<!-- Up Sell Heading -->
		<h3>
			<!-- Text -->
			<?= Text::_('J2STORE_RELATED_PRODUCTS_UPSELLS'); ?>
		</h3>

		<?php
		// Loop over all the up sell products
		foreach ($this->up_sells as $upsell_product):

			// Get the product link
			$upsell_product->product_link = $platform->getProductUrl(
				array(
					'task' => 'view',
					'id' => $upsell_product->j2store_product_id
				)
			);

			// If the add to cart button has custom text
			if (!empty($upsell_product->addtocart_text)) {
				// Get custom text
				$cart_text = Text::_($upsell_product->addtocart_text);
			} else {
				// Set default text
				$cart_text = Text::_('J2STORE_ADD_TO_CART');
			}

			// Get product name
			$upsell_product_name = $this->escape(
				$upsell_product->product_name
			);

			// Handling bootstrap row system
			// Calculate row count
			$rowcount = ((int) $counter % (int) $columns) + 1;

			// If this is the first row
			if ($rowcount == 1) :
				// Calculate row
				$row = $counter / $columns; ?>
				<!-- Main up sell product row -->
				<div class="upsell-product-row <?= 'row-' . $row; ?> row">
				<?php endif;

			// Initialize custom css to empty
			$upsell_css = '';

			// If product type is not variable or flexible
			if (!in_array(
				$upsell_product->product_type,
				array(
					'variable',
					'flexivariable'
				)
			)) {
				// Get css from product paramteres
				$upsell_css = $upsell_product->params->get(
					'product_css_class',
					''
				);
			} ?>
				<!-- Main Up sell product div -->
				<div
					class="col-sm-<?= round((12 / $columns)); ?> upsell-product product-<?= $upsell_product->j2store_product_id; ?> <?= isset($upsell_css) ? $upsell_css : ''; ?>  ">

					<!-- Up Sell image span -->
					<span class="upsell-product-image">
						<?php

						// Set thumbnail image to empty
						$thumb_image = '';

						// If thumbnail exists
						if (
							isset($upsell_product->thumb_image)
							&& $upsell_product->thumb_image
						) {
							// Get thumbnail through params
							$thumb_image = $platform->getImagePath(
								$upsell_product->thumb_image
							);
						}

						// If image exists
						if (isset($thumb_image) &&  !empty($thumb_image)): ?>

							<!-- Image Link -->
							<a
								href="<?= $upsell_product->product_link; ?>">

								<!-- Thumbnail Image -->
								<img
									title="<?= $upsell_product_name; ?>"
									alt="<?= $upsell_product_name; ?>"
									class="j2store-product-thumb-image-<?= $upsell_product->j2store_product_id; ?>"
									src="<?= $thumb_image; ?>"
									width="<?= intval($upsell_image_width); ?>" />
							</a>
						<?php endif; ?>

					</span>

					<!-- Product title -->
					<h3 class="upsell-product-title">
						<!-- Link to product -->
						<a
							href="<?= $upsell_product->product_link; ?>">
							<!-- Title text -->
							<?= $upsell_product_name; ?>
						</a>
					</h3>

					<?php
					// If price is enabled
					if (J2Store::product()->canShowprice($this->params)) {

						// Get Product
						$this->singleton_product = $upsell_product;
						// Get params
						$this->singleton_params = $this->params;

						// Load price template
						echo $this->loadAnyTemplate(
							'site:com_j2store/products/price'
						);
					}

					// Check if cart is enabled
					if (J2Store::product()->canShowCart($this->params)):

						// Check & count number of options
						$upsell_option = isset($upsell_product->options)
							&& is_array($upsell_product->options)
							? count($upsell_product->options)
							: 0;

						// Display choose options button if
						// product has options or is variable
						if (
							$upsell_option
							|| $upsell_product->product_type == 'variable'
						): ?>
							<!-- Options Link -->
							<a
								class="<?= $this->params->get(
													'choosebtn_class',
													'btn btn-success'
												); ?>"
								href="<?= $upsell_product->product_link; ?>">
								<!-- Link Text -->
								<?= Text::_('J2STORE_CART_CHOOSE_OPTIONS'); ?>
							</a>
					<?php else:
							// If product has no options
							// Get product
							$this->singleton_product = $upsell_product;
							// Get params
							$this->singleton_params = $this->params;
							// Get custom cart text
							$this->singleton_cartext = $this->escape($cart_text);

							// Render cart template
							echo $this->loadAnyTemplate(
								'site:com_j2store/products/cart'
							);

						endif;
					endif; ?>
				</div>
				<?php

				// Increment counter
				$counter++;

				// If row and columns are the same or it is the last item
				if (($rowcount == $columns) or ($counter == $total)) : ?>
					<!-- Close Up Sells -->
				</div>
		<?php endif;
			endforeach; ?>
	</div>
</div>
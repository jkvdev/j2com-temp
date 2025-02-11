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

?>
<!-- Main Stock DIV -->
<div class="product-stock-container">
	<?php
	// Check if stock management is enabled
	if (J2Store::product()->managing_stock($this->product->variant)):
		// Check if the product variant is available
		if ($this->product->variant->availability): ?>
			<!-- Span with dynamic class -->
			<span class="<?= $this->product->variant->availability ? 'instock' : 'outofstock'; ?>">
				<!-- Display stock level -->
				<?= J2Store::product()->displayStock($this->product->variant, $this->params); ?>
			</span>
		<?php
		// Check if the product is sold out
		elseif (!isset($this->product->all_sold_out) || (isset($this->product->all_sold_out) && $this->product->all_sold_out)): ?>
			<!-- Render an out of stock span -->
			<span class="outofstock">
				<!-- Render Text -->
				<?= Text::_('J2STORE_OUT_OF_STOCK'); ?>
			</span>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php
// Check if back orders are allowed and if the product is not in stock
if ($this->product->variant->allow_backorder == 2 && !$this->product->variant->availability): ?>
	<!-- Render a span notification -->
	<span class="backorder-notification">
		<!-- Display back order message -->
		<?= Text::_('J2STORE_BACKORDER_NOTIFICATION'); ?>
	</span>
<?php endif; ?>
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
?>
<!-- Main Stock DIV -->
<div class="product-stock-container">
	<?php

	// Check if stock management is enabled
	if (J2Store::product()->managing_stock($this->product->variant)): ?>
		<?php

		// Check if product is available in stock
		if ($this->product->variant->availability): ?>
			<!-- Stock tag -->
			<span
				class="<?= $this->product->variant->availability
									? 'instock'
									: 'outofstock'; ?>">
				<!-- Stock Text -->
				<?= J2Store::product()->displayStock(
					$this->product->variant,
					$this->params
				); ?>
			</span>
		<?php else: ?>
			<!-- Out of stock -->
			<span class="outofstock">
				<!-- Text -->
				<?= Text::_('J2STORE_OUT_OF_STOCK'); ?>
			</span>
	<?php endif;
	endif; ?>
</div>

<?php

// Check if back orders are allowed
if (
	$this->product->variant->allow_backorder == 2
	&& !$this->product->variant->availability
): ?>
	<!-- Show back order notifications -->
	<span class="backorder-notification">
		<!-- Back order text -->
		<?= Text::_('J2STORE_BACKORDER_NOTIFICATION'); ?>
	</span>
<?php endif; ?>
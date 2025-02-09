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

// If SKU is enabled
if (
	$this->params->get('item_show_product_sku', 1)
	&& !empty($this->product->variant->sku)
) : ?>
	<!-- Main SKU Container -->
	<div class="product-sku d-flex justify-content-end my-2">
		<!-- SKU Text -->
		<span class="sku-text fw-bold text-primary">
			<!-- Text -->
			<?= Text::_('J2STORE_SKU') ?>:
		</span>

		<!-- Product SKU -->
		<span class="sku fw-medium text-dark">
			<!-- Text -->
			<?= $this->escape($this->product->variant->sku); ?>
		</span>
	</div>
<?php endif; ?>
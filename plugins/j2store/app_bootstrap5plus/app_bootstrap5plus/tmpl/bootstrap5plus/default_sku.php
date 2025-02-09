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

<!-- Check Whether to display SKU -->
<?php if (!empty($this->product->variant->sku)) : ?>
	<!-- Main SKU div -->
	<div class="product-sku my-3">
		<!-- SKU Tag -->
		<span class="sku-text text-primary fw-bold"><?php echo Text::_('J2STORE_SKU') ?></span>
		<!-- Product Specific SKU Tag -->
		<span class="sku fw-medium text-gray"> <?php echo $this->escape($this->product->variant->sku); ?> </span>
	</div>
<?php endif; ?>
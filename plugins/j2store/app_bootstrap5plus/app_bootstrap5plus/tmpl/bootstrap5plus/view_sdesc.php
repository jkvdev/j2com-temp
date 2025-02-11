<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 * 
 * Bootstrap 2 layout of product detail
 */

// No direct access
defined('_JEXEC') or die;

// If short description is enabled
if ($this->params->get('item_show_sdesc', 1)): ?>
	<!-- Short Description DIV -->
	<div class="product-sdesc mt-2">
		<!-- Render description -->
		<?= $this->product->product_short_desc; ?>
	</div>
<?php endif; ?>
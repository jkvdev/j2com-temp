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

// If long description is enabled
if ($this->params->get('item_show_ldesc', 1)): ?>
	<!-- Long description DIV -->
	<div class="product-ldesc mt-2">
		<!-- Render description -->
		<?= $this->product->product_long_desc; ?>
	</div>
<?php endif; ?>
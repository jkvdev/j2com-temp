<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

// No direct access
defined('_JEXEC') or die;

// Check if the short description should be displayed
if ($this->params->get('list_show_short_desc', 1)): ?>
	<!-- Render a short version of the product description -->
	<div class="product-short-description">
		<!-- Render Description -->
		<?= $this->product->product_short_desc; ?>
	</div>
<?php endif;

// Check if the long description should be displayed
if ($this->params->get('list_show_long_desc', 0)): ?>
	<!-- Render a long version of the product description -->
	<div class="product-long-description">
		<!-- render Description -->
		<?= $this->product->product_long_desc; ?>
	</div>
<?php endif; ?>
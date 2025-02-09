<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 * 
 * Bootstrap 2 layout of product detail
 */
// No direct access
defined('_JEXEC') or die;

// If title is enabled
if ($this->params->get('item_show_title', 1)): ?>
	<!-- Check what type of heading tag to display -->
	<?php printf(
		'<h%s class="product-title text-primary fw-bold">',
		$this->params->get(
			'item_title_headertag',
			'2'
		)
	); ?>

	<!-- Display title -->
	<?= $this->escape($this->product->product_name); ?>

	<!-- Check what type of heading tag to display -->
	<?php printf(
		'</h%s>',
		$this->params->get(
			'item_title_headertag',
			'2'
		)
	); ?>

<?php endif; ?>
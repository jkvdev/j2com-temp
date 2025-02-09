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

// Check if manufacturer name should be displayed
if (
	$this->params->get('item_show_product_manufacturer_name', 1)
	&& !empty($this->product->manufacturer)
): ?>
	<!-- Main Manufacturer / Brand Container -->
	<span class="manufacturer-brand">
		<!-- Brand name -->
		<?= Text::_('J2STORE_PRODUCT_MANUFACTURER_NAME'); ?>:

		<?php
		// Check if manufacturer has a brand description article
		if (
			isset($this->product->brand_desc_id)
			&& !empty($this->product->brand_desc_id)
		):
			// Create link if exists
			$url = J2Store::article()->getArticleLink(
				$this->product->brand_desc_id
			); ?>
			<!-- Brand Description Link -->
			<a
				href="<?= $url; ?>"
				target="_blank">
				<!-- Text -->
				<?= $this->escape($this->product->manufacturer); ?>
			</a>
		<?php else:
			// Display plain text if no brand description exists
			echo $this->escape($this->product->manufacturer);
		endif; ?>
	</span>
<?php endif; ?>
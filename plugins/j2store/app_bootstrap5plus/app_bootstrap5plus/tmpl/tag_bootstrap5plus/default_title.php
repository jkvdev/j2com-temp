<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Router\Route;

// No direct access
defined('_JEXEC') or die;

// Check if the product title should be showed 
if ($this->params->get('list_show_title', 1)): ?>
	<!-- The product title is displayed as a H2 -->
	<h2 class="product-title">
		<!-- If title is Clickable / Link: -->
		<?php if ($this->params->get('list_link_title', 1)): ?>
			<!-- Start & Construct Product Link -->
			<!-- Call J2Store component, Load product tag view, call the view task, pass product ID and ensure menu assignment -->
			<!-- Escape special characters in the title. -->
			<a
				href="<?= Route::_(
								'index.php?option=com_j2store&view=producttags&task=view&id=' . $this->product->j2store_product_id . '&Itemid=' . $this->active_menu->id
							); ?>"
				title="<?= $this->escape($this->product->product_name); ?>"
				class="text-primary text-decoration-none h2">
			<?php endif;

		// Display the product name
		echo $this->escape($this->product->product_name);

		// If title is a Link
		if ($this->params->get('list_link_title', 1)): ?>
				<!-- End Link -->
			</a>
		<?php endif; ?>
	</h2>
<?php endif; ?>
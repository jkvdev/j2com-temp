<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 *
 * Bootstrap 2 layout of product detail
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;

// No direct access
defined('_JEXEC') or die;
?>
<!-- Grid Layout -->
<div class="row">
	<!-- Full Span -->
	<div class="col-sm-12">
		<!-- Setting up tabs -->
		<ul
			class="nav nav-tabs"
			id="j2store-product-detail-tab"
			role="tablist">
			<?php

			// Control active tab
			// Set specifications tab to active
			$set_specification_active = true;

			// If description is enabled
			if (
				$this->params->get('item_show_sdesc')
				||  $this->params->get('item_show_ldesc')
			) {
				// Set specification to inactive
				$set_specification_active = false;
			}

			// If description is enabled
			if (
				$this->params->get('item_show_sdesc')
				|| $this->params->get('item_show_ldesc')
			): ?>
				<!-- Create tab item for description -->
				<li class="nav-item">
					<!-- Link to make default tab active -->
					<a
						class="nav-link active"
						href="#description"
						data-bs-toggle="tab">
						<!-- Link Text -->
						<?= Text::_('J2STORE_PRODUCT_DESCRIPTION') ?>
					</a>
				</li>
			<?php endif;

			// Check if product specifications are enabled
			if ($this->params->get('item_show_product_specification')): ?>
				<!-- Create tab item for specifications -->
				<li class="nav-item">
					<!-- Link to make tab active -->
					<a
						href="#specs"
						class="nav-link <?= isset($set_specification_active)
															&& $set_specification_active
															? 'active'
															: ''; ?>"
						data-bs-toggle="tab">
						<!-- Link text -->
						<?= Text::_('J2STORE_PRODUCT_SPECIFICATIONS') ?>
					</a>
				</li>
			<?php endif; ?>
		</ul>

		<!-- Main Tab Container -->
		<div class="tab-content">
			<?php

			// Check if descriptions are enabled
			if (
				$this->params->get('item_show_sdesc')
				|| $this->params->get('item_show_ldesc')
			): ?>

				<!-- Create tab pane -->
				<div
					class="tab-pane fade show active"
					id="description">
					<?php

					// Load Short Description
					echo $this->loadTemplate('sdesc');
					// Load Long Description
					echo $this->loadTemplate('ldesc'); ?>
				</div>
			<?php endif;

			// Check if product specifications are enabled
			if ($this->params->get('item_show_product_specification')): ?>
				<div
					id="specs"
					class="tab-pane fade show <?= isset($set_specification_active)
																			&& $set_specification_active
																			? 'active'
																			: ''; ?>">
					<!-- Load specs template -->
					<?= $this->loadTemplate('specs'); ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</div>
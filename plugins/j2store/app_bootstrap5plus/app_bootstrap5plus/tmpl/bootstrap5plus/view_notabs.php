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
?>

<!-- Grid -->
<div class="row">
	<!-- Full span -->
	<div class="col-sm-12">
		<?php

		// if descriptions are enabled
		if (
			$this->params->get('item_show_sdesc')
			|| $this->params->get('item_show_ldesc')
		): ?>
			<div class="product-description">
				<?php

				// load short description
				echo $this->loadTemplate('sdesc');
				// load long description
				echo $this->loadTemplate('ldesc'); ?>
			</div>
		<?php endif;

		// If product specifications are enabled
		if ($this->params->get('item_show_product_specification')): ?>
			<!-- Product specs DIV -->
			<div class="product-specs">
				<!-- Load specs template -->
				<?= $this->loadTemplate('specs'); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
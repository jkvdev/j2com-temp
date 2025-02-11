<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 *
 * Bootstrap 2 layout of products
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

// No direct access
defined('_JEXEC') or die;

// Setting & Declaring Variables
// Initializing J2STore
$platform = J2Store::platform();
// Get Joomla Application Instance
// $app = $platform->application();
// Retrieve Joomla document object for managing scripts and styles
// $document = $app->getDocument();

// Load JavaScript File from the Media Folder
// TODO: search the media folder
$platform->addScript(
	'j2store-filter',
	'/media/j2store/js/filter.js'
);

// Construct url parameters for the active menu
$url_params = array();
$item_id = '';
$active_link = $platform->getProductUrl($url_params);

// If there is an active menu item, add its ID to the URL
if (isset($this->active_menu->id)) {
	// Get item id
	$item_id = $this->active_menu->id;
	// set url params to active menu id
	$url_params['Itemid'] = $this->active_menu->id;
	// get the active link
	$active_link = $platform->getProductUrl($url_params);
}

// Store the active Link
$actionURL = $active_link;
// Retrieve filter position settings
$filter_position = $this->params->get(
	'list_filter_position',
	'right'
);
?>

<!-- Main Div for the product list -->
<!-- Update to bootstrap 5 -->
<div
	class="j2store-product-list"
	data-link="<?= $active_link; ?>">

	<?php
	// Load Joomla Modules and Page Content
	// Load Plugins
	echo J2Store::plugin()->eventWithHtml(
		'BeforeViewProductListDisplay',
		array($this->products)
	);
	// Load Module
	echo J2Store::modules()->loadposition('j2store-product-list-top');

	// Display page heading if enabled
	if ($this->params->get('show_page_heading')) : ?>
		<!-- Page Header -->
		<div class="page-header">
			<!-- Main H1 -->
			<h1>
				<!-- Text -->
				<?= $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<!-- Prepare Bootstrap Rows -->
	<div class="row">
		<?php
		//	Check if filtering is enabled
		if ($this->params->get('list_show_filter', 0)):
			// If filtering is positioned on the left:
			if ($filter_position == 'left'): ?>
				<!-- Load Filter div -->
				<div
					class="j2store-sidebar-filters-container col-sm-3">
					<?php

					// Load Top Modules
					echo J2Store::modules()->loadposition('j2store-filter-left-top');

					// Load Filters Template
					// TODO: Revise filter Template
					echo $this->loadTemplate('filters');

					// Load Bottom Modules
					echo J2Store::modules()->loadposition('j2store-filter-left-bottom'); ?>
				</div>
		<?php endif;
		endif; ?>

		<!-- Div For content styled based on filters -->
		<div class="col-sm-<?= $this->params->get('list_show_filter', 0) ? 9 : 12; ?>">

			<?php
			// Load Sorting & Filtering UI
			if ($this->params->get('list_show_top_filter', 1)) {
				// Loads Sort Filter Template
				echo $this->loadTemplate('sortfilter');
			}

			// Loop through products
			// Check if there are any products available
			if (isset($this->products) && $this->products):

				// Retrieve number of columns, default = 3
				$col = $this->params->get('list_no_of_columns', 3);
				// Get number of products
				$total = count($this->products);
				// Initialize a counter
				$counter = 0;

				// Loop through all the products and display them in a row
				foreach ($this->products as $product):

					// Retrieve Product Params
					$product->params = $platform->getRegistry($product->params);

					// Determine the column number within the row
					$rowcount = ((int) $counter % (int) $col) + 1;

					// If this is the first row, start an new one
					if ($rowcount == 1) :
						// calculate row number
						$row = $counter / $col; ?>

						<!-- Start new Product row -->
						<div class="j2store-products-row <?= "row-{$row}" ?> row d-flex mb-4">
						<?php endif; ?>

						<!-- Create Dynamic Grid Layout -->
						<div
							class="col-xl-<?= round(12 / max(1, $col)); ?> col-lg-<?= round(12 / max(1, min($col, 4))); ?> col-md-<?= round(12 / max(1, min($col, 2))); ?> col-12">

							<!-- Assign unique product classes -->
							<!-- 
							// TODO: ADD CARD LAYOUT
							-->
							<div
								class="j2store-single-product multiple j2store-single-product-<?= $product->j2store_product_id; ?> product-<?= $product->j2store_product_id; ?> pcolumn-<?= $rowcount; ?> <?= $product->params->get('product_css_class', ''); ?> border rounded-3 mt-4 h-100">

								<!-- Product Content -->
								<?php
								// Generate product link & Load Template
								// Get Product
								$this->product = $product;
								// Generate product details page URL
								$this->product_link =
									$this->product->product_link =
									$platform->getProductUrl(
										array(
											'task' => 'view',
											'id' =>
											$this->product->j2store_product_id,
											'Itemid' => $item_id
										)
									);

								// Loads the correct template based on the product type
								try {
									// Retrieves product type
									$type = $product->product_type;
									// If product type is valid
									if (isset($type) && !empty($type)) {
										// Load the template type
										echo $this->loadTemplate(strtolower($type));
									}
									// In case of an error
								} catch (Exception $e) {
									// Echo the error message
									echo $e->getMessage();
								}

								// QUICK VIEW OPTION
								// If quick view is enabled
								if ($this->params->get('list_enable_quickview', 0)): ?>
									<!-- Render Link -->
									<a
										href="javascript:;"
										class="btn btn-default"
										data-fancybox
										data-type="iframe"
										data-src="<?= $platform->getProductUrl(
																array(
																	'task' => 'view',
																	'id' => $this->product->j2store_product_id,
																	'tmpl' => 'component'
																)
															); ?>">
										<!-- Load Quick View Icon -->
										<i class="fa fa-eye"></i>
										<!-- Load Text -->
										<?= Text::_('J2STORE_PRODUCT_QUICKVIEW'); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>

						<?php
						// Close the product rows
						// Increment the counter
						$counter++;

						// If the row count is the same as the columns or this is the last item, close the div
						if (($rowcount == $col) or ($counter == $total)) : ?>
						</div>
				<?php endif;
					endforeach; ?>

				<!-- Implement Pagination -->
				<!-- Pagination Form -->
				<form
					id="j2store-pagination"
					name="j2storepagination"
					action="<?= $platform->getProductUrl(
										array(
											'filter_catid' => $this->filter_catid,
											'Itemid' => $item_id
										)
									); ?>"
					method="post">

					<?php
					// Generate hidden input fields
					echo J2Html::hidden('option', 'com_j2store');
					echo J2Html::hidden('view', 'products');
					echo J2Html::hidden('task', 'browse', array(
						'id' => 'task'
					));
					echo J2Html::hidden('boxchecked', '0');
					echo J2Html::hidden('filter_order', '');
					echo J2Html::hidden('filter_order_Dir', '');
					echo J2Html::hidden('filter_catid', $this->filter_catid);

					// Generate hidden form token to prevent CSRF
					echo HTMLHelper::_('form.token'); ?>

					<!-- Display pagination links -->
					<div class="pagination">
						<?= $this->pagination->getPagesLinks(); ?>
					</div>
				</form>

			<?php else: ?>
				<!-- No product found... -->
				<div class="row">
					<div class="col-sm-12">
						<!-- Error Message -->
						<h5> <?= Text::_('J2STORE_NO_RESULTS_FOUND'); ?></h5>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php
		// Check if filter exists and is set to show
		if ($this->params->get('list_show_filter')):
			// Check if position is set top right
			if ($filter_position == 'right'): ?>
				<!-- Generate a sidebar for right site filter -->
				<div
					class="j2store-sidebar-filters-container col-sm-3">
					<?php
					// Load Top Modules
					echo J2Store::modules()->loadposition('j2store-filter-right-top');

					// Load Main Filters Template
					echo $this->loadTemplate('filters'); ?>

					<!-- Load Bottom Modules -->
					<?php echo J2Store::modules()->loadposition('j2store-filter-right-bottom'); ?>
				</div>
		<?php endif;
		endif; ?>

	</div> <!-- end of row-fluid -->
	<!-- Load and display the module positioned at 'j2store-product-list-bottom' -->
	<?= J2Store::modules()->loadposition('j2store-product-list-bottom'); ?>
</div> <!-- end of product list -->
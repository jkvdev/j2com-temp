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
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;

// No direct access
defined('_JEXEC') or die;

// TODO: Might need to remove redundancies

// Currency Setup & Section Filters
// Retrieve currency object
$currency_object = J2Store::currency();
// Retrieve currency symbol
$currency = $currency_object->getSymbol();
// Get Currency Position
$currency_position = $currency_object->getSymbolPosition();

// TODO: Check if needed
// Get category Id from URL
$catid = Factory::getApplication()->input->get(
	'catid',
	array(),
	'Array'
);

// Session Access & IDs
// Access session
// New migration
$session = Factory::getContainer()->get('session');
// Retrieve Manufacturer IDs
$session_manufacturer_ids = $session->get('manufacturer_ids', array(), 'j2store');
// Retrieve Vendor IDs
$session_vendor_ids = $session->get('vendor_ids', array(), 'j2store');
// Retrieve Product Filter iDs
$session_productfilter_ids = $session->get('productfilter_ids', array(), 'j2store');

// Currency Formatting
// Get Symbol
$currency = $this->currency->getSymbol();
// Get Conversion Rate
$currency_value = $this->currency->getValue();
// Get Thousand Separator
$thousand = $this->currency->getThousandSymbol();
// Get Decimal Places
$decimal_place = $this->currency->getDecimalPlace();

// Get tag Id from URL
$tagid = Factory::getApplication()->input->getInt(
	'tagid',
	0
);

// Initialize retrieved tag Id
$default_tagid = '';

// Check if there are any tag filters available
if (
	!empty($this->filters['filter_tag'])
	&& count($this->filters['filter_tag'])
) {
	// If tags exist, set the default to the first one
	$default_tagid = $this->filters['filter_tag'][0]->id;
}

// Check if a tag filter is selected
// If yes get the value, otherwise " "
$filter_tag = isset($this->filter_tag) ? $this->filter_tag : '';
?>

<!-- Display a loading animation for products -->
<div id="j2store-product-loading" style="display:none;"></div>

<!-- Form Initialization -->
<form
	method="post"
	action="<?= Route::_('index.php'); ?>"
	id="productsideFilters"
	class="form-horizontal"
	name="productsideFilters"
	data-link="<?= $this->active_menu->link . '&Itemid=' . $this->active_menu->id; ?>"
	enctype="multipart/form-data">

	<!-- Hidden Input for tag filter -->
	<input
		type="hidden"
		name="filter_tag"
		id="filter_tag"
		value="<?= $filter_tag; ?>" />

	<!-- Price Filters Starts Here -->
	<?php
	// Check if price filtering is enabled & There are filters available
	if (
		$this->params->get('list_show_filter_price', 0)
		&& isset($this->filters['pricefilters'])
		&& count($this->filters['pricefilters'])
	):

		// Extracting Min & Max Price Values
		$min_price = $this->filters['pricefilters']['min_price'];
		$max_price = $this->filters['pricefilters']['max_price'];
		// Get Range
		$range = $this->filters['pricefilters']['range'];
		// Get Prices From and To values
		$pricefrom = isset($this->state->pricefrom)
			&& $this->state->pricefrom
			? $this->state->pricefrom
			: $min_price;
		$priceto = isset($this->state->priceto)
			&& $this->state->priceto
			? $this->state->priceto
			: $max_price;
		// Format Currency
		$d_pricefrom = $this->currency->format(
			$pricefrom,
			$this->currency->getCode(),
			$this->currency->getValue(),
			false
		);
		$d_priceto = $this->currency->format(
			$priceto,
			$this->currency->getCode(),
			$this->currency->getValue(),
			false
		);
	?>
		<!-- //	TODO: COME BACK HERE & EDIT STYLING -->
		<!-- Price Slider & Submit Button -->
		<div
			id="j2store-price-filter-container"
			class="j2store-product-filters price-filters">
			<!-- Filter Heading/Title -->
			<h4 class="product-filter-heading fw-bold text-dark">
				<!-- Render Text -->
				<?= Text::_('J2STORE_PRODUCT_FILTER_PRICE_TITTLE'); ?>
			</h4>
			<!-- Render Price bar + Section Break -->
			<div id="j2store-slider-range" class='bg-light border border-primary rounded-3 w-100 mt-3'></div>
			<br>
			<!-- Price Filters Ends Here -->

			<!-- Dynamic price filtering -->
			<div
				id="j2store-slider-range-box"
				class="price-input-box d-flex flex-column gap-3">

				<!-- Display and store price values on the bar -->
				<div class="pull-right fw-medium text-dark text-center">
					<!-- Store Max and Min numerical values only raw and hidden -->
					<!-- Min Price -->
					<span id="min_price" style="display: none"><?= $pricefrom; ?></span>
					<!-- Max Price -->
					<span id="max_price" style="display: none"><?= $priceto; ?></span>

					<!-- Display the currency before or after for Min -->
					<?php if ($currency_position == 'pre') echo $currency; ?>
					<!-- Main Min Currency -->
					<span id="min_price_display">
						<!-- Price -->
						<?= $d_pricefrom; ?>
					</span>
					<?php if ($currency_position == 'post') echo $currency; ?>

					<!-- Display Text -->
					<!-- <?= Text::_('J2STORE_TO_PRICE'); ?> -->
					<!-- — -->
					/

					<!-- Display the currency before or after for MAx -->
					<?php if ($currency_position == 'pre') echo $currency; ?>
					<!-- Main Max Currency -->
					<span id="max_price_display">
						<!-- Price -->
						<?= $d_priceto; ?>
					</span>
					<?php if ($currency_position == 'post') echo $currency;

					// Generate Hidden Fields
					echo J2Html::hidden(
						'pricefrom',
						$pricefrom,
						array('id' => 'min_price_input')
					);
					echo J2Html::hidden(
						'priceto',
						$priceto,
						array('id' => 'max_price_input')
					); ?>
				</div>

				<!-- Submit Button -->
				<!-- 
				<?= Text::_('J2STORE_FILTER_GO'); ?>
					-->
				<button
					id="filterProductsBtn"
					class="btn btn-primary bg-dark bg-gradient d-flex justify-content-center align-items-center gap-2 fw-medium"
					type="submit">
					<!-- Text -->
					Filter

					<!-- Icon -->
					<i class="fas fa-filter"></i>
				</button>
			</div>
		</div>
		<?php endif;

	// Load J2STore Tag Filters
	echo J2Store::modules()->loadposition('j2store-tag-filter');

	// Manufacturer 
	// Check if manufacturer filter is enabled
	if ($this->params->get('list_show_manfacturer_filter', 0)):
		// Check if manufacturers are available
		if (count($this->filters['manufacturers'])): ?>
			<!-- Brand / Manufacturer Filters -->
			<div class="j2store-product-filters manufacturer-filters">
				<!-- Display Heading -->
				<div class="j2store-product-filter-title j2store-product-brand-title">
					<!-- Heading -->
					<h4 class="product-filter-heading">
						<!-- Text -->
						<?= Text::_('J2STORE_PRODUCT_FILTER_BY_BRAND'); ?>
					</h4>

					<!-- Manufacturer filters -->
					<span>
						<?php
						// If manufacturer filters are displayed: 
						if (!empty($session_manufacturer_ids)): ?>
							<!-- Clear Link -->
							<a
								href="javascript:void(0);"
								onclick="resetJ2storeBrandFilter();">
								<!-- Text -->
								<?= Text::_('J2STORE_CLEAR'); ?>
							</a>
						<?php endif; ?>
					</span>
				</div>


				<!-- Hold manufacturer filter checkboxes -->
				<div id="j2store-brand-filter-container" class="control-group">
					<?php
					// Base filtering products url
					$url = 'index.php?option=com_j2store&view=producttags';

					// Loop through all manufacturers and gets display details
					foreach ($this->filters['manufacturers'] as $k => $brand):
						// Initialize checked as empty
						$checked = '';

						// Check if manufacturer id is selected in the session
						if (
							!empty($session_manufacturer_ids)
							&&  in_array(
								$brand->j2store_manufacturer_id,
								$session_manufacturer_ids
							)
						) {
							// If true, set checked
							$checked = "checked ='checked'";
						}
					?>
						<!-- Label for manufacturer -->
						<label class="j2store-product-brand-label">
							<!-- Manufacturer Checkbox -->
							<input
								type="checkbox"
								class="j2store-brand-checkboxes" name="manufacturer_ids[]"
								id="brand-input-<?= $brand->j2store_manufacturer_id; ?>"
								<?= $checked; ?>
								value="<?= $brand->j2store_manufacturer_id; ?>" />
							<?php
							//onclick="document.getElementById('j2store-product-loading').style.display='block';document.getElementById('productsideFilters').submit()"

							// Display Manufacturer Name
							echo $this->escape($brand->company); ?>
						</label>
					<?php
					endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<!-- Vendors -->
	<?php
	// Check if vendor filter is enabled
	if (
		$this->params->get('list_show_vendor_filter', 0)
		&& !empty($this->filters['vendors'])
	): ?>
		<!-- MAIN VENDOR FILTER DIV -->
		<div class="j2store-product-filters j2store-product-vendor-filters">
			<!-- Vendor filter header -->
			<div class="j2store-product-filters-header">
				<!-- Title -->
				<h4 class="product-filter-heading">
					<!-- Text -->
					<?= Text::_('J2STORE_PRODUCT_FILTER_BY_VENDOR'); ?>
				</h4>
				<?php
				// If any vendor filters are applied
				if (!empty($session_vendor_ids)): ?>
					<!-- Clear Link -->
					<a
						href="javascript:void(0);"
						onclick="resetJ2storeVendorFilter();">
						<!-- Text -->
						<?= Text::_('J2STORE_CLEAR'); ?>
					</a>
				<?php endif; ?>
			</div>

			<!-- Div for vendor checkboxes -->
			<div id="j2store-vendor-filter-container" class="control-group">
				<?php
				// Loop through all filters, extract details
				foreach ($this->filters['vendors'] as $key => $vendor):
					// initialize checked as empty
					$checked = '';

					// if vendor id exists in session
					if (
						!empty($session_vendor_ids)
						&& in_array(
							$vendor->j2store_vendor_id,
							$session_vendor_ids
						)
					) {
						// Set checked
						$checked = "checked ='checked'";
					}
				?>
					<label class="j2store-product-vendor-label">
						<!-- Vendor Checkbox -->
						<input
							type="checkbox"
							class="j2store-vendor-checkboxes"
							id="vendor-input-<?= $vendor->j2store_vendor_id; ?>"
							name="vendor_ids[]"
							<?= $checked; ?>
							value="<?= $vendor->j2store_vendor_id; ?>" />

						<?php
						// onclick="document.getElementById('j2store-product-loading').style.display='block';document.getElementById('productsideFilters').submit()"
						// onchange="jQuery('#j2store-product-loading').show();this.form.submit()"

						// Display vendor name
						echo $this->escape($vendor->first_name . ' ' . $vendor->last_name); ?>
					</label>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif;

	// Check if product filter is enabled in settings
	if ($this->params->get('list_show_product_filter', 0)): ?>
		<!-- Product Filters  -->
		<div class="j2store-product-filters productfilters-list">
			<?php

			// Set active class to empty
			$active_class = '';

			// Loop over all product filters
			foreach (
				$this->filters['productfilters'] as $pf_key => $filtergroup
			):
				// Generate a unique filter id
				$filter_script_id = J2Store::utilities()->generateId(
					$filtergroup['group_name']
				) . '_' . $pf_key; ?>

				<!-- Main Filter Group DIV -->
				<div class="product-filter-group <?= $filter_script_id; ?>">
					<!-- Filter Group Header -->
					<h4 class="product-filter-heading">
						<!-- Text -->
						<?= $this->escape(Text::_(
							$filtergroup['group_name']
						)); ?>
					</h4>

					<!-- Filter Group Toggle -->
					<span>
						<?php

						// If the filter is collapsable
						if (
							$this->params->get(
								'list_filter_productfilter_toggle',
								1
							) == 1
						): ?>
							<!-- Collapse Toggle -->
							<span
								id="pf-filter-icon-minus-<?= $filter_script_id; ?>"
								onclick="getPFFilterToggle('<?= $filter_script_id; ?>');">
								<!-- Minus Icon -->
								<i class="icon-minus"></i>
							</span>

							<!-- Expand Toggle -->
							<span
								id="pf-filter-icon-plus-<?= $filter_script_id; ?>"
								onclick="getPFFilterToggle('<?= $filter_script_id; ?>');"
								style="display:none;">
								<!-- Plus Icon -->
								<i class="icon-plus"></i>
							</span>
						<?php

						// If filter is expandable
						elseif (
							$this->params->get(
								'list_filter_productfilter_toggle',
								1
							) == 2
						): ?>
							<!-- Expand Toggle -->
							<span
								id="pf-filter-icon-plus-<?= $filter_script_id; ?>"
								onclick="getPFFilterToggle('<?= $filter_script_id; ?>');">
								<!-- Plus Icon -->
								<i class="icon-plus"></i>
							</span>

							<!-- Collapsable Toggle -->
							<span
								id="pf-filter-icon-minus-<?= $filter_script_id; ?>"
								onclick="getPFFilterToggle('<?= $filter_script_id; ?>');"
								style="display:none;">
								<!-- Minus Icon -->
								<i class="icon-minus"></i>
							</span>
						<?php endif;

						// If there are any filters, generate clear filters button
						if (!empty($session_productfilter_ids)): ?>
							<!-- Clear Link -->
							<a
								href="javascript:void(0);"
								id="product-filter-group-clear-<?= $filter_script_id; ?>"
								style="display:none;"
								data-class="j2store-pfilter-checkboxes-<?= $filter_script_id; ?>"
								onclick="resetJ2storeProductFilter('j2store-pfilter-checkboxes-<?= $filter_script_id; ?>');">

								<!-- Link Text -->
								<?= Text::_('J2STORE_CLEAR'); ?>
							</a>
						<?php endif; ?>
					</span>
				</div>
				<?php

				// Product filter group visibility
				if (
					$this->params->get(
						'list_filter_productfilter_toggle',
						1
					) == 1
				): ?>
					<!-- Visible Filter Group -->
					<div
						id="j2store-pf-filter-<?= $filter_script_id; ?>"
						class="control-group j2store-productfilter-list" style="display:block;">
					<?php

				// Invisible Group
				elseif (
					$this->params->get(
						'list_filter_productfilter_toggle',
						1
					) == 2
				): ?>
						<!-- Invisible parent div -->
						<div
							id="j2store-pf-filter-<?= $filter_script_id; ?>" class="control-group j2store-productfilter-list"
							style="display:none;">
						<?php else: ?>
							<!-- Display default group div -->
							<div
								id="j2store-pf-filter-<?= $filter_script_id; ?>"
								class="control-group j2store-productfilter-list">
							<?php endif;

						// For each filter
						foreach ($filtergroup['filters'] as $i => $filter):

							// Set checked state to empty
							$checked = '';

							// If set by default, set cehcked
							if (
								!empty($session_productfilter_ids)
								&& in_array($filter->filter_id, $session_productfilter_ids)
							) {
								$checked = "checked ='checked'";
							}
							?>
								<!-- Filter Label -->
								<label
									class="j2store-productfilter-label">

									<!-- Checkbox Input -->
									<input
										class="j2store-pfilter-checkboxes-<?= $filter_script_id; ?>"
										id="j2store-pfilter-<?= $filter_script_id; ?>-<?= $filter->filter_id; ?>"
										type="checkbox"
										name="productfilter_ids[]"
										<?= $checked; ?>
										value="<?= $filter->filter_id; ?>" />

									<!-- Checkbox text -->
									<?= $this->escape(
										Text::_(
											$filter->filter_name
										)
									); ?>
								</label>
							<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
						</div>
					<?php endif;

				// Generate hidden form fields
				echo J2Html::hidden('option', 'com_j2store');
				echo J2Html::hidden('view', 'producttags');
				echo J2Html::hidden('task', 'browse');
				echo J2Html::hidden(
					'Itemid',
					Factory::getApplication()->input->getUint('Itemid')
				);
				echo HTMLHelper::_('form.token'); ?>
</form>

<script type="text/javascript">
	/**
	 * Method to Submit the form when search Btn clicked
	 */
	jQuery("#filterProductsBtn").on('click', function() {
		// Display a loading indicator
		jQuery("#j2store-product-loading").show();
		// Submit filters
		jQuery("#productsideFilters").submit();
	});

	// When doc is fully loaded
	jQuery('document').ready(function() {
		<?php
		// Loop through product filters
		foreach ($this->filters['productfilters'] as $pf_key => $filtergroup):

			// Generate unique ids for each
			$filter_script_id = J2Store::utilities()->generateId(
				$filtergroup['group_name']
			) . '_' . $pf_key;

			// Check for specific filters
			foreach ($filtergroup['filters'] as $i => $filter): ?>
				// Count number of selected checkboxes
				var size = jQuery('.j2store-pfilter-checkboxes-<?= $filter_script_id; ?>:checked').length;
				// If any are selected
				if (size > 0) {
					// Log the size
					console.log(size);
					// SHow clear filter
					jQuery('#product-filter-group-clear-<?= $filter_script_id; ?>').show();
					// Set filter group visible
					jQuery('#j2store-pf-filter-<?= $filter_script_id; ?>').show();
					// Hide plus
					jQuery('#pf-filter-icon-plus-<?= $filter_script_id; ?>').hide();
					// Show minus
					jQuery('#pf-filter-icon-minus-<?= $filter_script_id; ?>').show();
				}
			<?php endforeach; ?>
		<?php endforeach; ?>
	});
</script>

<!-- Price range filter -->
<?php
// Check if price filtering is enabled
if (
	$this->params->get('list_show_filter_price', 0)
	&& isset($this->filters['pricefilters'])
	&& count($this->filters['pricefilters'])
): ?>
	<script type="text/javascript">
		//assign the values for price filters
		var min_value = jQuery("#min_price").html();
		var max_value = jQuery("#max_price").html();
		var format_value = <?= $currency_value; ?>;

		// Formatting currency
		function formatCurrency(format_amount) {
			if (format_amount < 0) {
				format_amount = Math.abs(format_amount);
			}

			var decimal_place = '<?= $decimal_place; ?>';
			if (decimal_place == 0) {
				format_amount = format_amount + ".";
			}

			if (format_amount == '') {
				format_amount = 0.0;
			}

			// Formatting decimal places
			format_amount = parseFloat(format_amount);
			format_amount = format_amount.toFixed(decimal_place);
			format_amount = format_amount.toString();

			// Format Thousands
			var replace_string = "$1<?= $thousand; ?>";
			format_amount = format_amount
				.replace(/(\d)(?=(\d{3})+\.)/g, replace_string)
				.toString();
			format_amount = format_amount
				.substring(0, format_amount.length);

			//format_amount = format_amount.toFloat();
			return format_amount;
		}

		// Update Price Display
		jQuery("#max_price_display").html(formatCurrency(max_value * format_value));
		jQuery("#min_price_display").html(formatCurrency(min_value * format_value));

		// Implement Price Slider
		(function($) {
			// jQuery  UI Slider
			$("#j2store-slider-range").slider({
				range: true,
				min: <?= $min_price; ?>,
				max: <?= $max_price; ?>,
				values: [min_value, max_value],
				slide: function(event, ui) {

					// Update Visible price range
					// Add price symbol before or after
					$("#amount1")
						.val('<?php if ($currency_position == 'pre') echo $currency; ?>' +
							ui.values[0] +
							' <?php if ($currency_position == 'post') echo $currency; ?>  - <?php if ($currency_position == 'pre') echo $currency; ?>' +
							ui.values[1] +
							' <?php if ($currency_position == 'post') echo $currency; ?>');

					// Update display price
					$("#min_price").html(ui.values[0]);
					$("#max_price").html(ui.values[1]);

					// Update HTML Hidden input prices
					$("#min_price_input").attr('value', ui.values[0]);
					$("#max_price_input").attr('value', ui.values[1]);

					// Converts newly selected prices based on currency rate
					var min_format = ui.values[0] * format_value;
					var max_format = ui.values[1] * format_value;

					// Format display values
					$("#min_price_display").html(formatCurrency(min_format));
					$("#max_price_display").html(formatCurrency(max_format));
				}
			});

		})(j2store.jQuery);
	</script>
<?php endif; ?>
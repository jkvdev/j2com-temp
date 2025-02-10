<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
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

// Initialize Joomla Session
// New way to get the session
$session = Factory::getContainer()->get('session');
// Retrieve the selected category filter if any
$filter_catid = isset($this->filter_catid) ? $this->filter_catid : '';

// Retrieve the currency symbol for product prices
$currency = $this->currency->getSymbol(); ?>

<!-- Generate a inline form -->
<form
	method="post"
	id="productFilters"
	class="form-inline d-flex mt-2"
	name="productfilters"
	action="<?= Route::_('index.php'); ?>"
	data-link="<?= Route::_(
								$this->active_menu->link . '&Itemid=' . $this->active_menu->id
							); ?>">

	<!-- Main row for 8/4 grid -->
	<div class="row w-100">

		<!-- Category Filter Hidden Field -->
		<input
			type="hidden"
			name="filter_catid"
			id="sort_filter_catid"
			value="<?= $filter_catid; ?>" />

		<?php
		// Display a search input field if enabled
		if ($this->params->get('list_show_filter_search')):
			// Get search Params
			$search = htmlspecialchars($this->state->search);

			// TODO: CHANGE STYLING HERE
		?>
			<div class="col-8 d-flex flex-row gap-2 pe-0">
				<?php
				// Generate input box
				echo J2html::text(
					'search',
					$search,
					array(
						'class' => 'j2store-product-search-input border rounded-3 px-3 py-2 text-dark w-100',
						'placeholder' => 'Search products...'
					)
				); ?>

				<!-- Submit Button -->
				<button
					type="button"
					class="btn btn-primary bg-dark bg-gradient fw-medium"
					onclick="jQuery(this.form).submit();">
					<?php
					// echo JText::_('J2STORE_FILTER_GO'); 
					?>
					<i class="fas fa-search"></i>
				</button>

				<!-- Reset Button -->
				<button
					type="button"
					class="btn btn-outline-primary fw-medium border-2 d-flex gap-2 align-items-center justify-content-center"
					onclick="resetJ2storeFilter();">
					<?= Text::_('J2STORE_FILTER_RESET'); ?>
					<i class="fas fa-refresh"></i> <!-- Reset Icon -->
				</button>
			</div>

		<?php endif;

		// Sorting
		// Check if sorting is available
		if ($this->params->get('list_show_filter_sorting')): ?>
			<!-- Filter Column -->
			<div class="col-4 pe-0 ps-4">
			<?php
			// Generate a Dropdown & Submit on selection change
			// echo J2Html::select()->clearState()
			// 	->type('genericlist')
			// 	->name('sortby')
			// 	->attribs(array(
			// 		'class' => 'input h-100 w-100 border text-dark px-3 py-2 rounded-3',
			// 		'onchange' => 'jQuery(this.form).submit()'
			// 	))
			// 	->value($this->state->sortby)
			// 	->setPlaceHolders($this->filters['sorting'])->getHtml();

			// Initialize options array
			$options = array();

			// Loop over all the filters
			foreach ($this->filters['sorting'] as $value => $label) {
				// Store the option filter
				$options[] = HTMLHelper::_('select.option', $value, $label);
			}

			// Get HTML attributes
			$attributes = array(
				'class' => 'input h-100 w-100 border text-dark px-3 py-2 rounded-3',
				'onChange' => 'jQuery(this.form).submit()',
			);

			// Generate dropdown menu
			echo HTMLHelper::_(
				'select.genericlist',
				$options,
				'sortby',
				$attributes,  // Pass the attributes as an associative array
				'value',  // Option values key
				'text',   // Option labels key
				$this->state->sortby // Selected value
			);
		endif;

		// Hidden Fields for Joomla Routing
		echo J2Html::hidden('option', 'com_j2store');
		echo J2Html::hidden('view', 'products');
		echo J2Html::hidden('task', 'browse');
		echo J2Html::hidden(
			'Itemid',
			Factory::getApplication()->input->getUint('Itemid')
		);

		// Create Token for CSRF protection
		echo HTMLHelper::_('form.token'); ?>
			</div>
	</div>
</form>

<script type="text/javascript">
	// Javascript Reset Function
	// Clear the search & submit the form
	function resetJ2storeFilter() {
		jQuery(".j2store-product-search-input").val("");
		jQuery("#productFilters").submit();
	}
</script>
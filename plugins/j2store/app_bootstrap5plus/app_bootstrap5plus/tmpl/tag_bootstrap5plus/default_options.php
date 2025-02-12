<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

// No direct access
defined('_JEXEC') or die;

// Load J2STore Platform
$platform = J2Store::platform();
// Get Product Options
$options = $this->product->options;
// Get Product ID
$product_id = $this->product->j2store_product_id;
// Get Product helper
$product_helper = J2Store::product();
// Set AJAX URL
$ajax_url = Route::_('index.php', false);

// Checking if the product has options
if ($options): ?>

	<!-- Main Options DIV -->
	<div class="options">
		<?php
		// Go through all the options
		foreach ($options as $option):

			// Trigger Plugin Event before displaying options
			echo J2Store::plugin()->eventWithHtml(
				'BeforeDisplaySingleProductOption',
				array($this->product, &$option)
			);

			// Check if the option is a dropdown menu
			if ($option['type'] == 'select'): ?>
				<!-- Dropdown menu with unique id -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php
					// If Option id required
					if ($option['required']): ?>
						<!-- Display required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Render Option name in Bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Select Dropdown -->
					<select
						name="product_option[<?= $option['productoption_id']; ?>]"
						onChange="doAjaxPrice(<?= $product_id ?>,
          						'#option-<?= $option["productoption_id"]; ?>'
          						);">
						<!-- Default Select Option -->
						<option value="">
							<?= Text::_('J2STORE_ADDTOCART_SELECT'); ?>
						</option>

						<?php
						// Go through all the available values
						foreach ($option['optionvalue'] as $option_value):
							// Set check state to empty
							$checked = '';

							// Check if the option is default
							if (
								$option_value['product_optionvalue_default']
							) $checked = 'selected="selected"'; ?>

							<!-- Dropdown menu option -->
							<option
								<?= $checked; ?>
								value="<?= $option_value['product_optionvalue_id']; ?>">

								<?php
								// Render Option Text
								echo stripslashes($this->escape(
									Text::_($option_value['optionvalue_name'])
								));

								// If the price has an additional cost
								// && Price display is enabled
								if (
									$option_value['product_optionvalue_price'] > 0
									&& $this->params->get('product_option_price', 1)
								): ?>
									(
									<?php
									// Add Price prefix if enabled
									if ($this->params->get('product_option_price_prefix', 1)) {
										// Render Prefix
										echo $option_value['product_optionvalue_prefix'];
									}

									// Display Price Modification
									echo $product_helper->displayPrice(
										$option_value['product_optionvalue_price'],
										$this->product,
										$this->params,
										'products.list.option'
									); ?>
									)
								<?php endif; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<br>
			<?php endif;

			// Check if the option is a radio button
			if ($option['type'] == 'radio'): ?>
				<!-- Main Radio Button DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php
					// If required display:
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Display option text in bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<?php

					// Go through all option values
					foreach ($option['optionvalue'] as $option_value):
						// Set checked to empty
						$checked = '';

						// check if its default
						if (
							$option_value['product_optionvalue_default']
						) $checked = 'checked="checked"'; ?>
						<!-- Input radio button -->
						<input
							<?= $checked; ?>
							type="radio"
							autocomplete="off"
							name="product_option[<?= $option['productoption_id']; ?>]"
							value="<?= $option_value['product_optionvalue_id']; ?>"
							id="option-value-<?= $option_value['product_optionvalue_id']; ?>"
							onChange="doAjaxPrice(
          						<?= $product_id; ?>,
          						'#option-<?= $option["productoption_id"]; ?>'
          						);" />

						<?php
						// Display an Option Image if enabled
						if (
							$this->params->get('image_for_product_options', 0) &&
							isset($option_value['optionvalue_image']) &&
							!empty($option_value['optionvalue_image'])
						):
						?>
							<!-- Option Image -->
							<img
								class="optionvalue-image-<?= $option_value['product_optionvalue_id']; ?>"
								src="<?= Uri::root(true) . '/' . $option_value['optionvalue_image']; ?>" />
						<?php endif; ?>

						<!-- Create label for radio button -->
						<label
							for="option-value-<?= $option_value['product_optionvalue_id']; ?>">
							<?php

							// Render Option Text
							echo stripslashes($this->escape(Text::_($option_value['optionvalue_name'])));

							// Display additional price info
							if (
								$option_value['product_optionvalue_price'] > 0
								&& $this->params->get('product_option_price', 1)
							): ?>
								(
								<?php

								// If there is a price prefix display it
								if ($this->params->get('product_option_price_prefix', 1)): ?>
									<!-- Price prefix -->
									<?= $option_value['product_optionvalue_prefix']; ?>
								<?php endif;

								// Render Price Modification
								echo $product_helper->displayPrice(
									$option_value['product_optionvalue_price'],
									$this->product,
									$this->params,
									'products.list.option'
								); ?>
								)

							<?php endif; ?>
						</label> <br>
					<?php endforeach; ?>
				</div>
				<br>
			<?php endif;

			// Check if the option is a checkbox
			if ($option['type'] == 'checkbox'): ?>
				<!-- Min Checkbox DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php
					// If the checkbox is required
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option Name in Bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>
					<?php

					// Go over all the options
					foreach ($option['optionvalue'] as $option_value): ?>
						<!-- Render Checkbox -->
						<input
							type="checkbox"
							name="product_option[<?= $option['productoption_id']; ?>][]"
							value="<?= $option_value['product_optionvalue_id']; ?>"
							id="option-value-<?= $option_value['product_optionvalue_id']; ?>" />
						<?php

						// Display image if enabled
						if (
							$this->params->get('image_for_product_options', 0) &&
							isset($option_value['optionvalue_image']) &&
							!empty($option_value['optionvalue_image'])
						):
						?>
							<!-- Option Image -->
							<img
								class="optionvalue-image-<?= $option_value['product_optionvalue_id']; ?>"
								src="<?= Uri::root(true) . '/' . $option_value['optionvalue_image']; ?>" />
						<?php endif; ?>

						<!-- Option Label -->
						<label
							for="option-value-<?= $option_value['product_optionvalue_id']; ?>">
							<?php

							// Render Label Text
							echo stripslashes($this->escape(Text::_($option_value['optionvalue_name'])));

							// Display additional price info
							if (
								$option_value['product_optionvalue_price'] > 0
								&& $this->params->get('product_option_price', 1)
							): ?>
								(
								<?php
								// Get Prefix
								if ($this->params->get('product_option_price_prefix', 1)) {
									// Render Prefix
									echo $option_value['product_optionvalue_prefix'];
								}

								// Main Price
								echo $product_helper->displayPrice(
									$option_value['product_optionvalue_price'],
									$this->product,
									$this->params,
									'products.list.option'
								); ?>
								)
							<?php endif; ?>
						</label> <br>
					<?php endforeach; ?>
				</div>
				<br>

				<script type="text/javascript">
					// Javascript for dynamic price rendering
					(function($) {
						// Get product option id
						var po_id = '<?= $option['productoption_id']; ?>';

						// Set event listener for the checkbox
						$('#option-' + po_id + ' input:checkbox')
							.bind("click", function() {
								// Get Product id
								var product_id = '<?= $product_id ?>';
								// Update price dynamically though AJAX
								doAjaxPrice(product_id, '#option-' + po_id + ' input:checkbox');
							});
					})(j2store.jQuery);
				</script>

			<?php endif;

			// Check if the option is text/custom input fields
			if ($option['type'] == 'text'):
				// Retrieve text options
				$text_option_params = $platform->getRegistry($option['option_params']);
			?>
				<!-- MAIN TEXT OPTION DIV -->
				<div
					id="option-<? $option['productoption_id']; ?>"
					class="option">
					<?php

					// If Option Is Required
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option text in bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Create text input field -->
					<input
						type="text"
						name="product_option[<?= $option['productoption_id']; ?>]" placeholder="<?= $text_option_params->get('place_holder', ''); ?>"
						value="<?= $option['optionvalue']; ?>" />
				</div>
				<br>
			<?php endif;

			// Check if the option is a textarea
			if ($option['type'] == 'textarea'): ?>
				<!-- Main textarea DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php

					// If option is required
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option text in bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Render Textarea -->
					<textarea
						name="product_option[<?= $option['productoption_id']; ?>]"
						cols="20"
						rows="5">
						<!-- Render Value/Text -->
						<?= $option['optionvalue']; ?>
					</textarea>
				</div>
				<br>
			<?php endif;

			// Check if the option is a file
			if ($option['type'] == 'file'): ?>
				<!-- Main File Option DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php

					// If it is required
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option Text In Bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Button for file upload -->
					<button
						id="product-option-<?= $option['productoption_id']; ?>"
						class="btn btn-default"
						type="button"
						data-loading-text="<?= Text::_('J2STORE_LOADING') ?>">
						<!-- Icon -->
						<i class="fa fa-upload"></i>

						<!-- Button Text -->
						<?= Text::_('J2STORE_PRODUCT_OPTION_CHOOSE_FILE') ?>
					</button>

					<!-- Hidden Field -->
					<!-- Store uploaded file for reference -->
					<input
						type="hidden"
						name="product_option[<?= $option['productoption_id']; ?>]"
						value=""
						id="input-option<?= $option['productoption_id']; ?>" />

				</div>
				<br>

			<?php endif;

			// Check if the option is a date
			if ($option['type'] == 'date'): ?>
				<?php
				// Generate a unique class name
				$element_date = 'j2store_date_' . $option['productoption_id']; ?>
				<!-- Main Date Option DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php
					// If required
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option Text in bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Date Input field -->
					<input
						type="text"
						name="product_option[<?= $option['productoption_id']; ?>]"
						value="<?= $option['optionvalue']; ?>"
						class="<?= $element_date; ?>" />
				</div>
				<br>
				<?php
				// Adding a date picker via JavaScript
				J2StoreStrapper::addDatePicker(
					$element_date,
					$option['option_params']
				); ?>
			<?php endif;

			// Check if the option is a datetime
			if ($option['type'] == 'datetime'):

				// Generate a unique class
				$element_datetime = 'j2store_datetime_' . $option['productoption_id']; ?>

				<!-- Main Datetime Option DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php

					// If required
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option text in bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Datetime input field -->
					<input
						type="text"
						name="product_option[<?= $option['productoption_id']; ?>]"
						value="<?= $option['optionvalue']; ?>"
						class="<?= $element_datetime; ?>" />
				</div>
				<br>
				<?php

				// Adding datetime picker via JavaScript
				J2StoreStrapper::addDateTimePicker(
					$element_datetime,
					$option['option_params']
				); ?>
			<?php endif;

			// Check if the option is a time
			if ($option['type'] == 'time'): ?>
				<!-- Main Time Option DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php

					// If Required
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option text in bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Time input field -->
					<input
						type="text"
						name="product_option[<?= $option['productoption_id']; ?>]"
						value="<?= $option['optionvalue']; ?>"
						class="j2store_time" />
				</div>
				<br>
			<?php endif;

			// Hook event: Render Plugins after displaying option
			echo J2Store::plugin()->eventWithHtml(
				'AfterDisplaySingleProductOption',
				array(
					$this->product,
					$option
				)
			); ?>

		<?php endforeach; ?>
	</div>
	<?php endif;

// Checking if options exist and are not empty
if (isset($options) && !empty($options)):
	// Go through all the options
	foreach ($options as $option) :
		// Checking if the file type is a file
		if ($option['type'] == 'file'):  ?>
			<script type="text/javascript">
				// Function to handle file uploads
				(function($) {
					$('#product-option-<?= $option['productoption_id']; ?>')
						.on('click', function() {
							// Get this object
							var node = this;

							// Remove form
							$('#form-upload').remove();

							// Create hidden input field
							$('body').prepend( /*html*/ `
								<form 
									enctype="multipart/form-data" 
									id="form-upload" 
									style="display: none;">
									<input 
											type="file" 
											name="file" />
								</form>`);

							// Trigger file selection dialogue
							$('#form-upload input[name=\'file\']').trigger('click');

							// Check at an interval if a file has been chosen
							timer = setInterval(function() {
								if (
									$('#form-upload input[name=\'file\']').val() != ''
								) {
									// If selected clear interval
									clearInterval(timer);

									// Request to upload file
									$.ajax({
										url: '<?= $ajax_url; ?>?option=com_j2store&view=carts&task=upload&product_id=' + <?= $this->product->j2store_product_id; ?>,
										type: 'post',
										dataType: 'json',
										data: new FormData($('#form-upload')[0]),
										cache: false,
										contentType: false,
										processData: false,
										beforeSend: function() {
											// Show loading state
											$(node).button('loading');
										},
										complete: function() {
											// Reset button
											$(node).button('reset');
										},
										success: function(json) {
											// Remove previous messages
											$('.j2file-upload-response').remove();

											// If error occurs
											// Display error message
											if (json['error']) {
												$(node)
													.parent()
													.find('input')
													.after('<span class="j2file-upload-response text-danger">' + json['error'] + '</span>');
											}

											// If successful
											// Display success message
											if (json['success']) {
												// Show message
												$(node)
													.parent()
													.find('input')
													.after('<span class="j2file-upload-response text-success">' + json['success'] + ' </span>');

												// Update input field code
												$(node)
													.parent()
													.find('input')
													.attr('value', json['code']);
											}
										},
										// Error handling
										error: function(xhr, ajaxOptions, thrownError) {
											// Alert user
											alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
										}
									});
								}
							}, 500);
						});
				})(j2store.jQuery);
			</script>
<?php endif;
	endforeach;
endif; ?>
<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

// No direct access
defined('_JEXEC') or die;

// Get J2Store platform instance
$platform = J2Store::platform();
// Get product options
$options = $this->product->options;
// Get product id
$product_id = $this->product->j2store_product_id;
// Get product helper
$product_helper = J2Store::product();
// Get ajax url
$ajax_url = Route::_('index.php', false);

// Check if product has options
if ($options): ?>
	<!-- Main Options DIV -->
	<div class="options">
		<?php

		// Loop over all product options
		foreach ($options as $option):
			// Run plugins before displaying product option
			echo J2Store::plugin()->eventWithHtml(
				'BeforeDisplaySingleProductOption',
				array(
					$this->product,
					&$option
				)
			);

			// If the options is a select dropdown menu
			if ($option['type'] == 'select'): ?>
				<!-- Main Select Dropdown DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php

					// If option is required
					if ($option['required']): ?>
						<!-- required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option text in bold -->
					<b><?= Text::_($option['option_name']); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Dropdown menu -->
					<select
						name="product_option[<?= $option['productoption_id']; ?>]"
						onChange="doAjaxPrice(<?= $product_id ?>,
          						'#option-<?= $option["productoption_id"]; ?>'
          						);">

						<!-- Default option -->
						<option value="">
							<!-- Default text -->
							<?= Text::_('J2STORE_ADDTOCART_SELECT'); ?>
						</option>
						<?php

						// Loop over all option values
						foreach ($option['optionvalue'] as $option_value):

							// Set checked state to empty
							$checked = '';

							// If product is checked by default, check it
							if ($option_value['product_optionvalue_default']) $checked = 'selected="selected"'; ?>

							<!-- Render option -->
							<option
								<?= $checked; ?>
								value="<?= $option_value['product_optionvalue_id']; ?>">
								<?php

								// Option text
								echo stripslashes(Text::_($option_value['optionvalue_name']));

								// Check if option has additional charge
								if (
									$option_value['product_optionvalue_price'] > 0
									&& $this->params->get('product_option_price', 1)
								): ?>
									(
									<?php

									// If price has prefix
									if ($this->params->get('product_option_price_prefix', 1)) {
										// Show prefix
										echo $option_value['product_optionvalue_prefix'];
									}

									// render price
									echo $product_helper->displayPrice(
										$option_value['product_optionvalue_price'],
										$this->product,
										$this->params,
										'products.view.option'
									); ?>
									)
								<?php endif; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<br>
			<?php endif;

			// If option is a radio button
			if ($option['type'] == 'radio'): ?>
				<!-- Main radio option DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php

					// If the option i required
					if ($option['required']): ?>
						<!-- Required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option text in bold -->
					<b><?= Text::_($option['option_name']); ?>:</b>

					<!-- Break -->
					<br>

					<?php
					// Loop over option values
					foreach ($option['optionvalue'] as $option_value):

						// Set checked state to empty
						$checked = '';

						// If product is set by default, set checked
						if ($option_value['product_optionvalue_default']) $checked = 'checked="checked"'; ?>

						<!-- Radio Input -->
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
						// If input has image
						if (
							$this->params->get('image_for_product_options', 0) &&
							isset($option_value['optionvalue_image']) &&
							!empty($option_value['optionvalue_image'])
						):
						?>
							<!-- Option image -->
							<img
								class="optionvalue-image-<?= $option_value['product_optionvalue_id']; ?>"
								src="<?= Uri::root(true) . '/' . $option_value['optionvalue_image']; ?>" />
						<?php endif; ?>

						<!-- Radio input label -->
						<label
							for="option-value-<?= $option_value['product_optionvalue_id']; ?>">

							<?php
							// Label text
							echo stripslashes($this->escape(
								Text::_($option_value['optionvalue_name'])
							));

							// If option has extra price
							if (
								$option_value['product_optionvalue_price'] > 0
								&& $this->params->get('product_option_price', 1)
							) : ?>
								(
								<?php

								// If product has prefix
								if ($this->params->get('product_option_price_prefix', 1)) {
									// SHow prefix
									echo $option_value['product_optionvalue_prefix'];
								}

								// Display price
								echo $product_helper->displayPrice(
									$option_value['product_optionvalue_price'],
									$this->product,
									$this->params,
									'products.view.option'
								); ?>
								)
							<?php endif; ?>
						</label> <br>
					<?php endforeach; ?>
				</div>
				<br>
			<?php endif;

			// If option is a checkbox
			if ($option['type'] == 'checkbox'): ?>

				<!-- Main Checkbox Option DIV-->
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
					<b><?= Text::_($option['option_name']); ?>:</b>

					<!-- Break -->
					<br>

					<?php
					// Loop over option values 
					foreach ($option['optionvalue'] as $option_value): ?>
						<!-- Checkbox input -->
						<input type="checkbox"
							name="product_option[<?= $option['productoption_id']; ?>][]"
							value="<?= $option_value['product_optionvalue_id']; ?>"
							id="option-value-<?= $option_value['product_optionvalue_id']; ?>" />

						<?php
						// If checkbox has an image
						if (
							$this->params->get('image_for_product_options', 0) &&
							isset($option_value['optionvalue_image']) &&
							!empty($option_value['optionvalue_image'])
						):
						?>
							<!-- Checkbox Image -->
							<img
								class="optionvalue-image-<?= $option_value['product_optionvalue_id']; ?>"
								src="<?= Uri::root(true) . '/' . $option_value['optionvalue_image']; ?>" />
						<?php endif; ?>

						<!-- Checkbox label -->
						<label
							for="option-value-<?= $option_value['product_optionvalue_id']; ?>">
							<?php

							// Label text
							echo stripslashes($this->escape(Text::_($option_value['optionvalue_name'])));

							// If option has extra price
							if (
								$option_value['product_optionvalue_price'] > 0
								&& $this->params->get('product_option_price', 1)
							): ?>
								(
								<?php

								// If price has prefix
								if ($this->params->get('product_option_price_prefix', 1)) {
									// Show prefix
									echo $option_value['product_optionvalue_prefix'];
								}

								// Show price
								echo $product_helper->displayPrice(
									$option_value['product_optionvalue_price'],
									$this->product,
									$this->params,
									'products.view.option'
								); ?>
								)
							<?php endif; ?>
						</label> <br>
					<?php endforeach; ?>
				</div>
				<br>

				<script type="text/javascript">
					// AJAX Price Update
					(function($) {
						// get product option id
						var po_id = '<?= $option['productoption_id']; ?>';

						// Checkbox on click event
						$('#option-' + po_id + ' input:checkbox')
							.bind("click", function() {
								// Get product id
								var product_id = '<?= $product_id ?>';

								// Update price without refreshing
								doAjaxPrice(product_id,
									'#option-' + po_id + ' input:checkbox');
							});
					})(j2store.jQuery);
				</script>
			<?php endif;

			// If input is of type text
			if ($option['type'] == 'text'): ?>
				<?php

				// Get text option params
				$text_option_params = $platform->getRegistry(
					$option['option_params']
				);
				?>
				<!-- Main text option DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php

					// If required
					if ($option['required']): ?>
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option text in bold -->
					<b><?= Text::_($option['option_name']); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Text Input -->
					<input
						type="text"
						name="product_option[<?= $option['productoption_id']; ?>]"
						value="<?= $option['optionvalue']; ?>"
						placeholder="<?= $text_option_params->get(
														'place_holder',
														''
													); ?>" />
				</div>
				<br>
			<?php endif;

			// If option is a textarea
			if ($option['type'] == 'textarea'): ?>
				<!-- Main textarea option DIV -->
				<div
					id="option-<?= $option['productoption_id']; ?>"
					class="option">
					<?php

					// If required
					if ($option['required']): ?>
						<!-- required span -->
						<span class="required">*</span>
					<?php endif; ?>

					<!-- Option text in bold -->
					<b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Textarea Input -->
					<textarea
						name="product_option[<?= $option['productoption_id']; ?>]"
						cols="20"
						rows="5">
						<!-- Text -->
						<?= $option['optionvalue']; ?>
					</textarea>
				</div>
				<br>
			<?php endif;

			// If option is a file
			if ($option['type'] == 'file'): ?>
				<!-- Main File option DIV -->
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
					<b><?= Text::_($option['option_name']); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Upload button -->
					<button
						type="button"
						id="product-option-<?= $option['productoption_id']; ?>"
						data-loading-text="<?= Text::_('J2STORE_LOADING') ?>"
						class="btn btn-default">
						<!-- Upload Icon -->
						<i class="fa fa-upload"></i>

						<!-- Button text -->
						<?= Text::_('J2STORE_PRODUCT_OPTION_CHOOSE_FILE') ?>
					</button>

					<!-- Hidden input for storing file path -->
					<input
						type="hidden"
						name="product_option[<?= $option['productoption_id']; ?>]"
						id="input-option<?= $option['productoption_id']; ?>"
						value="" />
				</div>
				<br>
			<?php endif;

			// If option is a date
			if ($option['type'] == 'date'):
				// Generate unique dat class
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

					<!-- Option text in bold -->
					<b><?= Text::_($option['option_name']); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Date Input -->
					<input
						type="text"
						name="product_option[<?= $option['productoption_id']; ?>]"
						value="<?= $option['optionvalue']; ?>"
						class="<?= $element_date; ?>" />
				</div>
				<br>
			<?php

				// Render date picker with JavaScript
				J2StoreStrapper::addDatePicker(
					$element_date,
					$option['option_params']
				);
			endif;

			// If the option is a date time
			if ($option['type'] == 'datetime'):
				// Generate unique class name
				$element_datetime = 'j2store_datetime_' . $option['productoption_id']; ?>
				<!-- Main datetime option DIV -->
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
					<b><?= Text::_($option['option_name']); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Datetime input -->
					<input type="text"
						name="product_option[<?= $option['productoption_id']; ?>]"
						value="<?= $option['optionvalue']; ?>"
						class="<?= $element_datetime; ?>" />
				</div>
				<br>
			<?php

				// Render datetime picker with JavaScript
				J2StoreStrapper::addDateTimePicker(
					$element_datetime,
					$option['option_params']
				);
			endif;

			// If option is a time
			if ($option['type'] == 'time'): ?>
				<!-- Main time option DIV -->
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
					<b><?= Text::_($option['option_name']); ?>:</b>

					<!-- Break -->
					<br>

					<!-- Time input -->
					<input
						type="text"
						name="product_option[<?= $option['productoption_id']; ?>]"
						value="<?= $option['optionvalue']; ?>"
						class="j2store_time" />
				</div>
				<br>
		<?php endif;

			// Run after option display plugins
			echo J2Store::plugin()->eventWithHtml(
				'AfterDisplaySingleProductOption',
				array(
					$this->product,
					$option
				)
			);

		endforeach; ?>
	</div>
	<?php endif;

// If options exist
if (isset($options) && !empty($options)):
	// Loop through all options
	foreach ($options as $option) :
		// If the option is a file
		if ($option['type'] == 'file'):  ?>
			<script type="text/javascript">
				// Upload file via AJAX
				(function($) {
					$('#product-option-<?= $option['productoption_id']; ?>')
						.on('click', function() {
							// Get clicked element
							var node = this;

							// Remove upload form
							$('#form-upload').remove();

							// Create hidden form
							$('body').prepend( /*html*/ `
								<form 
								enctype="multipart/form-data" 
								id="form-upload" 
								style="display: none;">
									<input 
									type="file" 
									name="file" />
								</form>`);

							// Insert input field & Trigger file selection
							$('#form-upload input[name=\'file\']').trigger('click');

							// Check if user has selected a file
							timer = setInterval(function() {
								if (
									$('#form-upload input[name=\'file\']').val() != '' &&
									$('#form-upload input[name=\'file\']').val() !=
									undefined) {
									// Clear 500ms interval
									clearInterval(timer);

									// AJAX File Upload
									$.ajax({
										url: '<?= $ajax_url; ?>?option=com_j2store&view=carts&task=upload&product_id=' + <?= $this->product->j2store_product_id; ?>,
										type: 'post',
										dataType: 'json',
										data: new FormData($('#form-upload')[0]),
										cache: false,
										contentType: false,
										processData: false,
										beforeSend: function() {
											// Set loading
											$(node).button('loading');
										},
										complete: function() {
											// Reset button
											$(node).button('reset');
											// Remove form
											$('body').find('#form-upload input[name=\'file\']').remove();
										},
										success: function(json) {
											// Remove upload messages
											$('.j2file-upload-response').remove();

											// If error
											if (json['error']) {
												// Display error messages
												$(node)
													.parent()
													.find('input')
													.after('<span class="j2file-upload-response text-danger">' + json['error'] + '</span>');
											}

											// If success 
											if (json['success']) {
												// Show success message
												$(node)
													.parent()
													.find('input')
													.after('<span class="j2file-upload-response text-success">' + json['success'] + ' </span>');

												// Upload code
												$(node)
													.parent()
													.find('input')
													.attr('value',
														json['code']);
											}
										},

										// Handle errors 
										error: function(xhr, ajaxOptions, thrownError) {
											// SHow error message
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
<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

// No direct access
defined('_JEXEC') or die;

// Get J2STORE Platform 
$platform = J2Store::platform();
// Get List of product options
$options = $this->product->options;
// Get Product ID
$product_id = $this->product->j2store_product_id;

// Check if there are any product options
if ($options): ?>
  <!-- Main Options DIV -->
  <div class="options">
    <?php

    // Loop through all the options
    foreach ($options as $option):

      // Trigger plugin events before displaying options
      echo J2Store::plugin()->eventWithHtml(
        'BeforeDisplaySingleProductOption',
        array(
          $this->product,
          &$option
        )
      );

      // Check if option is a select dropdown
      if (
        $option['type'] == 'select'
        && isset($option['optionvalue'])
        && !empty($option['optionvalue'])
      ): ?>
        <!-- Main Select Dropdown DIV -->
        <div
          id="option-<?= $option['productoption_id']; ?>"
          class="option">
          <?php
          // If Option is required
          if ($option['required']): ?>
            <!-- Required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Option Text in bold -->
          <b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Dropdown menu -->
          <select
            name="product_option[<?= $option['productoption_id']; ?>]"
            onChange="doAjaxFilter(
          						this.options[this.selectedIndex].value,
          						<?= $product_id ?>,
          						<?= $option["productoption_id"]; ?>,
          						'#option-<?= $option["productoption_id"]; ?>'
          						);">
            <!-- Default Option -->
            <option value="">
              <?= Text::_('J2STORE_ADDTOCART_SELECT'); ?>
            </option>

            <?php
            // For all the option values:
            foreach ($option['optionvalue'] as $option_value):
              // Set checked state to empty
              $checked = '';

              // If the option has a default value it is pre selected
              if (
                $option_value['product_optionvalue_default']
              ) $checked = 'selected="selected"'; ?>

              <!-- Render dropdown option -->
              <option
                <?= $checked; ?>
                value="<?= $option_value['product_optionvalue_id']; ?>">

                <?php
                // Render option text
                echo stripslashes($this->escape(
                  Text::_(
                    $option_value['optionvalue_name']
                  )
                ));

                // Check if there are additional price adjustments 
                if (
                  $option_value['product_optionvalue_price'] > 0
                  && $this->params->get('product_option_price', 1)
                ): ?>
                  (
                  <?php

                  // Check if there is a price prefix
                  if ($this->params->get('product_option_price_prefix', 1)) {
                    // Render prefix
                    echo $option_value['product_optionvalue_prefix'];
                  }

                  // Render extra price
                  echo J2Store::product()->displayPrice(
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

      // Check if option is a radio button
      if (
        $option['type'] == 'radio'
        && isset($option['optionvalue'])
        && !empty($option['optionvalue'])
      ): ?>
        <!-- Main Radio Option DIV -->
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

          <?php

          // Loop through each option value
          foreach ($option['optionvalue'] as $option_value):
            // Set is checked state to empty
            $checked = '';

            // Check if it is set by default
            if (
              $option_value['product_optionvalue_default']
            ) $checked = 'checked="checked"'; ?>

            <!-- Radio Button Input -->
            <input
              <?= $checked; ?>
              type="radio"
              name="product_option[<?= $option['productoption_id']; ?>]"
              value="<?= $option_value['product_optionvalue_id']; ?>"
              id="option-value-<?= $option_value['product_optionvalue_id']; ?>"
              onChange="doAjaxFilter(
          						this.value,
          						<?= $product_id ?>,
          						<?= $option["productoption_id"]; ?>,
          						'#option-<?= $option["productoption_id"]; ?>'
          						);" />
            <?php

            // Display image for each option if available
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
              <!-- Label Text -->
              <?= stripslashes($this->escape(Text::_($option_value['optionvalue_name']))); ?>
              <?php

              // Check if option has additional price
              if (
                $option_value['product_optionvalue_price'] > 0
                && $this->params->get('product_option_price', 1)
              ) : ?>
                (
                <?php

                // If there is a prefix
                if ($this->params->get('product_option_price_prefix', 1)) {
                  // Render prefix
                  echo $option_value['product_optionvalue_prefix'];
                }

                // Display extra price
                echo J2Store::product()->displayPrice(
                  $option_value['product_optionvalue_price'],
                  $this->product,
                  $this->params,
                  'products.list.option'
                ); ?>
                )
              <?php endif; ?>
            </label>
            <br>
          <?php endforeach; ?>
        </div>
        <br>
      <?php endif;

      // Check if option is a checkbox
      if (
        $option['type'] == 'checkbox'
        && isset($option['optionvalue'])
        && !empty($option['optionvalue'])
      ) : ?>
        <!-- Main Checkbox DIV -->
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

          <?php

          // Loop over all option values
          foreach ($option['optionvalue'] as $option_value): ?>
            <!-- Checkbox -->
            <input
              type="checkbox"
              name="product_option[<?= $option['productoption_id']; ?>][]"
              value="<?= $option_value['product_optionvalue_id']; ?>"
              id="option-value-<?= $option_value['product_optionvalue_id']; ?>" />
            <?php

            // Display option image if available
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

            <!-- Checkbox label -->
            <label
              for="option-value-<?= $option_value['product_optionvalue_id']; ?>">
              <!-- Label Text -->
              <?= stripslashes($this->escape(Text::_($option_value['optionvalue_name'])));

              // Check if there is an additional price
              if (
                $option_value['product_optionvalue_price'] > 0
                && $this->params->get('product_option_price', 1)
              ): ?>
                (
                <?php

                // If there is a prefix
                if ($this->params->get('product_option_price_prefix', 1)) {
                  // Render prefix
                  echo $option_value['product_optionvalue_prefix'];
                }

                // Display additional price
                echo J2Store::product()->displayPrice(
                  $option_value['product_optionvalue_price'],
                  $this->product,
                  $this->params,
                  'products.list.option'
                ); ?>
                )
              <?php endif; ?>
            </label>
            <br>
          <?php endforeach; ?>
        </div>
        <br>

        <script type="text/javascript">
          // AJAX Filtering
          (function($) {
            // Get Product option ID
            var po_id = '<?= $option['productoption_id']; ?>';

            // Bind click even on checkbox
            $('#option-' + po_id + ' input:checkbox')
              .bind("click", function() {
                // Get the checkbox value
                var checkbox_value = $('#option-' + po_id + ' input:checkbox:checked').val();
                // Get the product ID
                var product_id = '<?= $product_id ?>';

                // Update product display price without refreshing
                doAjaxFilter(
                  checkbox_value,
                  product_id, po_id,
                  '#option-' + po_id + ' input:checkbox');
              });
          })(j2store.jQuery);
        </script>

      <?php endif;

      // Check if the option is a text field
      if ($option['type'] == 'text'):
        // Get option parameters
        $text_option_params = $platform->getRegistry($option['option_params']);
      ?>
        <!-- Main Text Field DIV -->
        <div
          id="option-<?= $option['productoption_id']; ?>"
          class="option">
          <?php

          // If field is required
          if ($option['required']) : ?>
            <!-- Required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Option Text in bold -->
          <b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Textfield input -->
          <input
            type="text"
            name="product_option[<?= $option['productoption_id']; ?>]"
            value="<?= $option['optionvalue']; ?>"
            placeholder="<?= $text_option_params->get('place_holder', ''); ?>" />
        </div>
        <br>
      <?php endif;

      // Check if the option is a textarea
      if ($option['type'] == 'textarea'): ?>
        <!-- Main Textarea DIV -->
        <div
          id="option-<?= $option['productoption_id']; ?>"
          class="option">
          <?php
          // If required
          if ($option['required']) : ?>
            <!-- Required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Option Text in Bold -->
          <b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Textarea -->
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

      // Check if the option is a file 
      if ($option['type'] == 'file') : ?>
        <!-- Main File Option DIV -->
        <div
          id="option-<?= $option['productoption_id']; ?>"
          class="option">
          <?php

          // If option is required
          if ($option['required']) : ?>
            <!-- Required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Option Text in bold -->
          <b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Upload Button -->
          <button
            type="button"
            id="product-option-<?= $option['productoption_id']; ?>"
            data-loading-text="<?= Text::_('J2STORE_LOADING') ?>"
            class="btn btn-default">
            <!-- Button Icon -->
            <i class="fa fa-upload"></i>

            <!-- Button Text -->
            <?= Text::_('J2STORE_PRODUCT_OPTION_CHOOSE_FILE') ?>
          </button>

          <!-- Hidden field to store file data -->
          <input
            type="hidden"
            name="product_option[<?= $option['productoption_id']; ?>]"
            value="" id="input-option<?= $option['productoption_id']; ?>" />
        </div>
        <br>

      <?php endif;

      // Check if the option is a date
      if ($option['type'] == 'date') :
        // Generate unique date picker class
        $element_date = 'j2store_date_' . $option['productoption_id']; ?>

        <!-- Main Date Picker DIV -->
        <div
          id="option-<?= $option['productoption_id']; ?>"
          class="option">
          <?php

          // If required
          if ($option['required']) : ?>
            <!-- Required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Option Text in bold -->
          <b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Date Input Field -->
          <input
            type="text"
            name="product_option[<?= $option['productoption_id']; ?>]"
            value="<?= $option['optionvalue']; ?>"
            class="<?= $element_date; ?>" />
        </div>
        <br>
        <?php

        // Initializing datepicker
        J2StoreStrapper::addDatePicker(
          $element_date,
          $option['option_params']
        ); ?>
      <?php endif;

      // Check if the option is a date time
      if ($option['type'] == 'datetime') :
        // Generate unique class name
        $element_datetime = 'j2store_datetime_' . $option['productoption_id']; ?>
        <!-- Main Datetime Option DIV -->
        <div
          id="option-<?= $option['productoption_id']; ?>"
          class="option">
          <?php

          // If required
          if ($option['required']) : ?>
            <!-- Required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Option Text in bold -->
          <b><?= Text::_($option['option_name']); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Datetime INput field -->
          <input
            type="text"
            name="product_option[<?= $option['productoption_id']; ?>]"
            value="<?= $option['optionvalue']; ?>"
            class="<?= $element_datetime; ?>" />
        </div>
        <br>
        <?php

        // Initializing datetime picker
        J2StoreStrapper::addDateTimePicker(
          $element_datetime,
          $option['option_params']
        ); ?>
      <?php endif;

      // Check if the option is a time
      if ($option['type'] == 'time') : ?>
        <!-- Main Time Option DIV -->
        <div
          id="option-<?= $option['productoption_id']; ?>"
          class="option">
          <?php

          // If required
          if ($option['required']) : ?>
            <!-- Required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Option Text in bold -->
          <b><?= Text::_($option['option_name']); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Time input field -->
          <input
            type="text"
            name="product_option[<?= $option['productoption_id']; ?>]" value="<?= $option['optionvalue']; ?>"
            class="j2store_time" />
        </div>
        <br>
      <?php endif;

      // Event Hook: Render plugins after content is displayed
      echo J2Store::plugin()->eventWithHtml(
        'AfterDisplaySingleProductOption',
        array(
          $this->product,
          $option
        )
      ); ?>

      <!-- Creating additional child option containers -->
      <div id="ChildOptions<?= $option['productoption_id']; ?>"></div>

    <?php endforeach; ?>

    <!-- Creating additional child option containers -->
    <!-- 
    // !POSSIBLE TYPO 
    -->
    <div id="ChildOptio<?= $option['productoption_id']; ?>"></div>
  </div>
  <?php endif;

// Check if there are any product options
if (isset($options) && !empty($options)):
  // Loop through all the options
  foreach ($options as $option) :
    // If the option is a file
    if ($option['type'] == 'file'):  ?>
      <script type="text/javascript">
        // Handle file uploads
        (function($) {
          // Trigger file upload on button click
          $('#product-option-<?= $option['productoption_id']; ?>')
            .on('click', function() {
              // Get this object
              var node = this;

              // Remove existing form
              $('#form-upload').remove();

              // Create hidden input field
              $('body').prepend( /*html*/ `
              <form 
                enctype="multipart/form-data" id="form-upload" 
                style="display: none;">
                <input 
                  type="file" 
                  name="file" />
              </form>`);

              // Trigger file selection dialogue
              $('#form-upload input[name=\'file\']').trigger('click');

              // Check at an interval if the file has been chosen
              timer = setInterval(function() {
                // Check if file has been selected
                if (
                  $('#form-upload input[name=\'file\']').val() != ''
                ) {
                  // If selected clear interval
                  clearInterval(timer);

                  // Request an upload file
                  $.ajax({
                    url: 'index.php?option=com_j2store&view=carts&task=upload&product_id=' + <?= $this->product->j2store_product_id; ?>,
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                      // SHow loading state
                      $(node).button('loading');
                    },
                    complete: function() {
                      // Reset Button
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
                        // SHow message
                        $(node)
                          .parent()
                          .find('input')
                          .after('<span class="j2file-upload-response text-success">' + json['success'] + ' </span>');

                        // Update Input field code
                        $(node)
                          .parent()
                          .find('input')
                          .attr('value', json['code']);
                      }
                    },
                    // Error Handling
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
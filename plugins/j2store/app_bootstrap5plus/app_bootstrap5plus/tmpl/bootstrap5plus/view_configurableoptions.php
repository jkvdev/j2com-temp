<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

// No direct access
defined('_JEXEC') or die;

// Get J2Store platform instance
$platform = J2Store::platform();
// Get product options
$options = $this->product->options;
// Get product id
$product_id = $this->product->j2store_product_id;

// If there are any options
if ($options): ?>
  <!-- Main options DIV -->
  <div class="options">
    <?php foreach ($options as $option):

      // Run plugins before displaying options
      echo J2Store::plugin()->eventWithHtml(
        'BeforeDisplaySingleProductOption',
        array(
          $this->product,
          &$option
        )
      );

      // If option is a select dropdown
      if (
        $option['type'] == 'select'
        && isset($option['optionvalue'])
        && !empty($option['optionvalue'])
      ): ?>
        <!-- Main select dropdown DIV -->
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
          <b><?= Text::_($this->escape($option['option_name'])); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Select Dropdown -->
          <select
            name="product_option[<?= $option['productoption_id']; ?>]"
            onChange="doAjaxFilter(
          						this.options[this.selectedIndex].value,
          						<?= $product_id ?>,
          						<?= $option["productoption_id"]; ?>,
          						'#option-<?= $option["productoption_id"]; ?>'
          						);">
            <!-- Default option -->
            <option value="">
              <!-- Option text -->
              <?= Text::_('J2STORE_ADDTOCART_SELECT'); ?>
            </option>

            <?php
            // Loop over all option values
            foreach ($option['optionvalue'] as $option_value):

              // Set is checked to empty
              $checked = '';

              // If set by default, set checked state
              if ($option_value['product_optionvalue_default']) $checked = 'selected="selected"'; ?>

              <!-- Render option -->
              <option
                <?= $checked; ?>
                value="<?= $option_value['product_optionvalue_id']; ?>">
                <!-- Option Text -->
                <?= stripslashes($this->escape(
                  Text::_($option_value['optionvalue_name'])
                ));

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
                  echo J2Store::product()->displayPrice(
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
      if (
        $option['type'] == 'radio'
        && isset($option['optionvalue'])
        && !empty($option['optionvalue'])
      ): ?>
        <!-- Main radio DIV -->
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

          <?php
          // Loop over all options
          foreach ($option['optionvalue'] as $option_value):

            // Set is checked to empty
            $checked = '';

            // If checked by default, set checked
            if ($option_value['product_optionvalue_default']) $checked = 'checked="checked"'; ?>
            <!-- Radio Input -->
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
            // If input has image
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

            <!-- Radio Label -->
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
              ): ?>
                (
                <?php

                // If price has prefix
                if ($this->params->get('product_option_price_prefix', 1)) {
                  // Show prefix
                  echo $option_value['product_optionvalue_prefix'];
                }

                // Show price
                echo J2Store::product()->displayPrice(
                  $option_value['product_optionvalue_price'],
                  $this->product,
                  $this->params,
                  'products.view.option'
                ); ?>
                )
              <?php endif; ?>
            </label>
            <br>
          <?php endforeach; ?>
        </div>
        <br>
      <?php endif;

      // If option is a checkbox
      if (
        $option['type'] == 'checkbox'
        && isset($option['optionvalue'])
        && !empty($option['optionvalue'])
      ): ?>
        <!-- Main checkbox DIV-->
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

          <?php

          foreach ($option['optionvalue'] as $option_value): ?>
            <!-- Render checkbox input -->
            <input
              type="checkbox"
              name="product_option[<?= $option['productoption_id']; ?>][]" value="<?= $option_value['product_optionvalue_id']; ?>" id="option-value-<?= $option_value['product_optionvalue_id']; ?>" />

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

            <!-- Checkbox Label -->
            <label
              for="option-value-<?= $option_value['product_optionvalue_id']; ?>">
              <!-- Label Text -->
              <?= stripslashes($this->escape(Text::_($option_value['optionvalue_name'])));

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
                echo J2Store::product()->displayPrice(
                  $option_value['product_optionvalue_price'],
                  $this->product,
                  $this->params,
                  'products.view.option'
                ); ?>
                )
              <?php endif; ?>
            </label>
            <br>
          <?php endforeach; ?>
        </div>
        <br>

        <script type="text/javascript">
          // AJAX Price Update
          (function($) {
            // Get product option id
            var po_id = '<?= $option['productoption_id']; ?>';

            // Set checkbox on click event
            $('#option-' + po_id + ' input:checkbox')
              .bind("click", function() {
                // Get Checkbox value
                var checkbox_value = $('#option-' + po_id + ' input:checkbox:checked').val();
                // Get product id
                var product_id = '<?= $product_id ?>';

                // Update price
                doAjaxFilter(checkbox_value,
                  product_id,
                  po_id, '#option-' + po_id + ' input:checkbox');
              });
          })(j2store.jQuery);
        </script>
      <?php endif;

      // If option is a text input
      if ($option['type'] == 'text'):
        // Get text params
        $text_option_params = $platform->getRegistry(
          $option['option_params']
        );
      ?>
        <!-- Main text DIV -->
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
        <!-- Main textarea DIV -->
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
        <!-- Main File DIV -->
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

          <!-- Upload Button -->
          <button
            type="button"
            id="product-option-<?= $option['productoption_id']; ?>"
            data-loading-text="<?= Text::_('J2STORE_LOADING') ?>"
            class="btn btn-default">
            <!-- Upload Icon -->
            <i class="fa fa-upload"></i>

            <!-- Button Text -->
            <?= Text::_('J2STORE_PRODUCT_OPTION_CHOOSE_FILE') ?>
          </button>

          <!-- Hidden Input to save file path -->
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
        // Generate unique class
        $element_date = 'j2store_date_' . $option['productoption_id']; ?>
        <!-- Main date DIV -->
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

          <!-- Date input -->
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

      // If option is a datetime
      if ($option['type'] == 'datetime'):
        // Generate unique class
        $element_datetime = 'j2store_datetime_' . $option['productoption_id']; ?>
        <!-- Main datetime DIV -->
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

          <!-- Datetime Input -->
          <input
            type="text"
            name="product_option[<?= $option['productoption_id']; ?>]" value="<?= $option['optionvalue']; ?>"
            class="<?= $element_datetime; ?>" />
        </div>
        <br>
      <?php

        // Render datetime picker with JavaSCript
        J2StoreStrapper::addDateTimePicker(
          $element_datetime,
          $option['option_params']
        );
      endif;

      // If option is a time
      if ($option['type'] == 'time'): ?>
        <!-- Main time DIV -->
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

          <!-- Time input -->
          <input
            type="text"
            name="product_option[<?= $option['productoption_id']; ?>]" value="<?= $option['optionvalue']; ?>"
            class="j2store_time" />
        </div>
        <br>
      <?php endif;

      // Run plugins after displaying an option
      echo J2Store::plugin()->eventWithHtml(
        'AfterDisplaySingleProductOption',
        array(
          $this->product,
          $option
        )
      ); ?>

      <!-- Render additional child options -->
      <div
        id="ChildOptions<?= $option['productoption_id']; ?>">
      </div>

    <?php endforeach; ?>
  </div>
  <?php endif;

// If options exist
if (isset($options) && !empty($options)):
  // Loop over all options
  foreach ($options as $option) :
    // If option is a file
    if ($option['type'] == 'file'):  ?>
      <script type="text/javascript">
        // Upload file through AJAX
        (function($) {
          // Event listener on product option
          $('#product-option-<?= $option['productoption_id']; ?>')
            .on('click', function() {
              // Get this element
              var node = this;

              // Remove form
              $('#form-upload').remove();

              // create hidden form
              $('body').prepend( /*html*/ `
                <form 
                  enctype="multipart/form-data" 
                  id="form-upload" 
                  style="display: none;">
                  <input 
                    type="file" 
                    name="file" />
                </form>`);

              // Insert an input file type and trigger it
              $('#form-upload input[name=\'file\']').trigger('click');

              // Monitor file selection
              timer = setInterval(function() {
                if (
                  $('#form-upload input[name=\'file\']').val() != '' &&
                  $('#form-upload input[name=\'file\']').val() !=
                  undefined) {
                  // Clear 500ms interval
                  clearInterval(timer);

                  // AJAX File Upload
                  $.ajax({
                    url: 'index.php?option=com_j2store&view=carts&task=upload&product_id=' + <?= $this->product->j2store_product_id; ?>,
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
                    },
                    success: function(json) {
                      // Remove upload messages
                      $('.j2file-upload-response').remove();

                      // If error
                      if (json['error']) {
                        // Display error message
                        $(node)
                          .parent()
                          .find('input')
                          .after('<span class="j2file-upload-response text-danger">' + json['error'] + '</span>');
                      }

                      // If success
                      if (json['success']) {
                        // Display success message
                        $(node)
                          .parent()
                          .find('input')
                          .after('<span class="j2file-upload-response text-success">' + json['success'] + ' </span>');

                        // Save file
                        $(node)
                          .parent()
                          .find('input')
                          .attr('value',
                            json['code']);
                      }
                    },
                    // Handle errors
                    error: function(xhr, ajaxOptions, thrownError) {
                      // Show error
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
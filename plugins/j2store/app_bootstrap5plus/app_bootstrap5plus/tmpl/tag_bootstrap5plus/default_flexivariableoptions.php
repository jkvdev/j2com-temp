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

// Get Options
$options = isset($this->product->options)
  && !empty($this->product->options)
  ? $this->product->options : array();

// Get variant Name
$variant_name = array();

// If variant names exist
if (
  isset($this->product->variant->variant_name)
  && $this->product->variant->variant_name
) {
  // Split the variant names into an array
  $variant_name = explode(',', $this->product->variant->variant_name);
}

// If options exist
if ($options): ?>
  <!-- Main Options DIV -->
  <div
    class="options"
    id="variable-options-<?= $this->product->j2store_product_id ?>">
    <?php

    // Loop over all options
    foreach ($options as $option_key => $option):

      // set default option id to empty
      $default_option_value_id = '';

      // Loop over all option values
      foreach ($option['optionvalue'] as $o_value) {
        // Check if it is set as default
        if (
          isset($variant_name[$option_key])
          && isset($o_value['product_optionvalue_id'])
          && $variant_name[$option_key] == $o_value['product_optionvalue_id']
        ) {
          // Set default value
          $default_option_value_id = $o_value['optionvalue_id'];

          // Break Loop
          break;
        }
      }

      // Set default option name to empty
      $default_option_value_name = '';
      // Set option count to 0
      $option_count = 0;

      // Run plugins before displaying an option
      echo J2Store::plugin()->eventWithHtml(
        'BeforeDisplaySingleProductOption',
        array(
          $this->product,
          &$option
        )
      );

      // If the option is a select dropdown
      if ($option['type'] == 'select'): ?>
        <!-- Main Select Dropdown Option DIV -->
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

          <!-- Dropdown menu -->
          <select
            name="product_option[<?= $option['productoption_id']; ?>]"
            onChange="doFlexiAjaxPrice(
                            <?= $this->product->j2store_product_id ?>,
                                    '#option-<?= $option["productoption_id"]; ?>'
                                    )">
            <!-- Render default option -->
            <option value="*">
              <!-- Option text -->
              <?= stripslashes($this->escape(Text::_('J2STORE_CHOOSE'))); ?>
            </option>
            <?php

            // Loop over each option
            foreach ($option['option_value'] as $option_value):
              // Set is checked to empty
              $checked = '';

              // Check if it is a default value and set it
              if (
                $default_option_value_id == $option_value->j2store_optionvalue_id
              ) $checked = 'selected="selected"'; ?>

              <!-- Render option -->
              <option
                <?= $checked; ?>
                value="<?= $option_value->j2store_optionvalue_id; ?>">
                <!-- Option Text -->
                <?= stripslashes($this->escape(
                  Text::_($option_value->optionvalue_name)
                )); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <br>
      <?php endif;

      // Check if the option is a radio button
      if ($option['type'] == 'radio'): ?>
        <!-- Main Radio Option DIV -->
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

          // Loop over all option values
          foreach ($option['option_value'] as $option_value) :
            // Set checked to empty
            $checked = '';

            // Check if it is set by default
            if (
              $default_option_value_id == $option_value->j2store_optionvalue_id
            ) $checked = 'checked="checked"'; ?>

            <!-- Radio button -->
            <input
              <?= $checked; ?>
              type="radio"
              name="product_option[<?= $option['productoption_id']; ?>]"
              autocomplete="off"
              onClick="doFlexiAjaxPrice(
                  <?= $this->product->j2store_product_id ?>,
                  '#option-<?= $option["productoption_id"]; ?>'
                );"
              value="<?= $option_value->j2store_optionvalue_id; ?>"
              id="option-value-<?= $option_value->j2store_optionvalue_id; ?>"
              data-product_id="<?= $this->product->j2store_product_id ?>" />
            <?php

            // If Option has an image render it
            if (
              $this->params->get('image_for_product_options', 0) &&
              isset($option_value->optionvalue_image) &&
              !empty($option_value->optionvalue_image)
            ):
            ?>
              <!-- Option Image -->
              <img
                class="optionvalue-image-<?= $option['productoption_id']; ?>-<?= $option_value->j2store_optionvalue_id; ?>"
                src="<?= Uri::root(true) . '/' . $option_value->optionvalue_image; ?>" />
            <?php endif; ?>

            <!-- Radio label -->
            <label
              for="option-value-<?= $option_value->j2store_optionvalue_id; ?>">
              <!-- Text -->
              <?= stripslashes($this->escape(Text::_($option_value->optionvalue_name))); ?>
            </label>
            <br>
          <?php endforeach; ?>
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
    <?php endforeach; ?>
  </div>
<?php endif; ?>
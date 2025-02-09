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

// Get product options
$options = isset($this->product->options)
  && !empty($this->product->options)
  ? $this->product->options : array();

// If there are product options
if ($options): ?>
  <!-- Main Options DIV -->
  <div
    class="options"
    id="variable-options-<?= $this->product->j2store_product_id ?>">
    <?php
    // Loop through options
    foreach ($options as $option):

      // Run Plugins before displaying an Option
      echo J2Store::plugin()->eventWithHtml(
        'BeforeDisplaySingleProductOption',
        array(
          $this->product,
          &$option
        )
      );

      // If the option is a dropdown
      if ($option['type'] == 'select'): ?>
        <!-- Main Dropdown Option DIV -->
        <div
          id="option-<?= $option['productoption_id']; ?>"
          class="option">
          <?php
          // If required
          if ($option['required']): ?>
            <!-- Required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Bold Option Text -->
          <b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

          <!-- Break -->
          <br>

          <!-- Main Dropdown -->
          <select
            name="product_option[<?= $option['productoption_id']; ?>]"
            onChange="doAjaxPrice(
         	 			<?= $this->product->j2store_product_id ?>,
         	 			'#option-<?= $option["productoption_id"]; ?>'
         	 			);">
            <?php

            // Loop over all option values
            foreach ($option['optionvalue'] as $option_value):
              // Set is checked to false
              $checked = '';

              // Check if option is set by default
              if ($option_value['product_optionvalue_default']) $checked = 'selected="selected"'; ?>

              <!-- Option -->
              <option
                <?= $checked; ?>
                value="<?= $option_value['product_optionvalue_id']; ?>"
                <?= $option_value['product_optionvalue_attribs']; ?>>
                <!-- Option Text -->
                <?= stripslashes($this->escape(
                  Text::_($option_value['optionvalue_name'])
                )); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <br>
      <?php endif;

      // If the option is a radio button
      if ($option['type'] == 'radio') : ?>
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

          // Loop over all option values
          foreach ($option['optionvalue'] as $option_value):
            // Set checked status to empty
            $checked = '';

            // Check if it is set by default
            if ($option_value['product_optionvalue_default']) $checked = 'checked="checked"'; ?>

            <!-- Radio Button -->
            <input
              <?= $checked; ?>
              type="radio"
              name="product_option[<?= $option['productoption_id']; ?>]"
              autocomplete="off"
              onClick="doAjaxPrice(
          	 		<?= $this->product->j2store_product_id ?>,
          	 		'#option-<?= $option["productoption_id"]; ?>'
          	 );"
              value="<?= $option_value['product_optionvalue_id']; ?>" id="option-value-<?= $option_value['product_optionvalue_id']; ?>"
              <?= $option_value['product_optionvalue_attribs']; ?> />

            <?php

            // If radio button has an image, render it
            if (
              $this->params->get('image_for_product_options', 0) &&
              isset($option_value['optionvalue_image']) &&
              !empty($option_value['optionvalue_image'])
            ):
            ?>
              <!-- Option Image -->
              <img
                class="optionvalue-image-<?= $option_value['product_optionvalue_id']; ?>"
                src="<?= Uri::root(true) . '/' . $option_value['optionvalue_image']; ?>"
                <?= $option_value['product_optionvalue_attribs']; ?> />
            <?php endif; ?>

            <!-- Radio Label -->
            <label
              for="option-value-<?= $option_value['product_optionvalue_id']; ?>"
              <?= $option_value['product_optionvalue_attribs']; ?>>
              <!-- Label Text -->
              <?= stripslashes($this->escape(Text::_($option_value['optionvalue_name']))); ?>
            </label>
            <br>
          <?php endforeach; ?>
        </div>
      <?php endif;

      // Render plugins after displaying an option
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
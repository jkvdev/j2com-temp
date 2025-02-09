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
// Get Options
$options = isset($this->product->options)
  && !empty($this->product->options)
  ? $this->product->options : array();

// If options exist
if ($options): ?>
  <!-- Main Options DIV -->
  <div
    class="options"
    id="variable-options-<?= $this->product->j2store_product_id ?>">

    <?php
    // Loop over all options
    foreach ($options as $option) :

      // Run before display option plugins
      echo J2Store::plugin()->eventWithHtml(
        'BeforeDisplaySingleProductOption',
        array(
          $this->product,
          &$option
        )
      );

      // If option is a select dropdown
      if ($option['type'] == 'select'): ?>
        <!-- Main select dropdown DIV -->
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

          <!-- Select dropdown menu -->
          <select
            name="product_option[<?= $option['productoption_id']; ?>]"
            onChange="doAjaxPrice(
         	 			<?= $this->product->j2store_product_id ?>,
         	 			'#option-<?= $option["productoption_id"]; ?>'
         	 			)">
            <?php

            // Loop over all option values
            foreach ($option['optionvalue'] as $option_value) :

              // Set checked state to empty
              $checked = '';

              // If option is checked by default, set to checked
              if ($option_value['product_optionvalue_default']) $checked = 'selected="selected"'; ?>
              <!-- Default option -->
              <option
                <?= $checked; ?>
                value="<?= $option_value['product_optionvalue_id']; ?>"
                <?= $option_value['product_optionvalue_attribs']; ?>>
                <!-- Option Text -->
                <?= stripslashes($this->escape(Text::_(
                  $option_value['optionvalue_name']
                ))); ?>
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
          id="option-<?= $option['productoption_id']; ?>" class="option">
          <?php

          // If required
          if ($option['required']) : ?>
            <!-- required span -->
            <span class="required">*</span>
          <?php endif; ?>

          <!-- Option text in bold -->
          <b><?= $this->escape(Text::_($option['option_name'])); ?>:</b>

          <!-- Break -->
          <br>

          <?php
          // Loop over all option values
          foreach ($option['optionvalue'] as $option_value) :

            // Set is checked state to empty
            $checked = '';

            // If set by default, set checked
            if ($option_value['product_optionvalue_default']) $checked = 'checked="checked"'; ?>

            <!-- Radio button input -->
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
              <?= $option_value['product_optionvalue_attribs']; ?>
              data-product_id="<?= $this->product->j2store_product_id ?>" />

            <?php
            // If option has an image
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

            <!-- Radio Option Label -->
            <label
              for="option-value-<?= $option_value['product_optionvalue_id']; ?>"
              <?= $option_value['product_optionvalue_attribs']; ?>>
              <!-- Label Text -->
              <?= stripslashes($this->escape(Text::_(
                $option_value['optionvalue_name']
              ))); ?>
            </label>
            <br>
          <?php endforeach; ?>
        </div>
        <br>
    <?php endif;

      // Run after display option plugins
      echo J2Store::plugin()->eventWithHtml(
        'AfterDisplaySingleProductOption',
        array(
          $this->product,
          $option
        )
      );
    endforeach; ?>
  </div>
<?php endif; ?>
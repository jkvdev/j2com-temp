<?php

/**
 * @package   J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license   GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Language\Text;

// No direct access
defined('_JEXEC') or die;

// Check if there are any product specific filters
if (isset($this->filters) && count($this->filters)): ?>
  <!-- Main Product specifications DIV -->
  <div class="j2store-product-specifications">
    <?php

    // Loop through each filter group
    foreach ($this->filters as $group_id => $rows): ?>
      <!-- Filter name -->
      <h4 class="filter-group-name">
        <!-- Text -->
        <?= $this->escape(Text::_($rows['group_name'])); ?>
      </h4>

      <!-- Render table -->
      <table class="table table-striped">
        <?php
        // Loop through filters inside each group
        foreach ($rows['filters'] as $filter): ?>
          <tr>
            <td>
              <!-- Display filter -->
              <?= $this->escape(Text::_($filter->filter_name)); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endforeach; ?>

  </div>
<?php endif; ?>

<!-- Product dimensions table -->
<table class="table table-striped">
  <!-- Group 1 -->
  <tr>
    <!-- Product dimensions -->
    <td>
      <!-- Text -->
      <?= Text::_('J2STORE_PRODUCT_DIMENSIONS'); ?>
    </td>

    <td>
      <!-- Main Dimensions span -->
      <span class="product-dimensions">
        <?php

        // Check if the product has variants
        if (
          isset($this->product->variant)
          && !empty($this->product->variant)
        ):
          // Check if width, height & length exist
          if (
            $this->product->variant->length
            && $this->product->variant->height
            && $this->product->variant->width
          ): ?>
            <!-- Show dimensions -->
            <?= round($this->product->variant->length, 2); ?>
            x <?= round($this->product->variant->width, 2); ?>
            x <?= round($this->product->variant->height, 2); ?>

            <!-- Show title -->
            <?= $this->product->variant->length_title; ?>
          <?php endif; ?>
        <?php else: ?>
          <!-- No dimensions available -->
          <?= Text::_('J2STORE_EMPTY_DASHES'); ?>
        <?php endif; ?>
      </span>
    </td>
  </tr>

  <!-- Group 2 -->
  <tr>
    <!-- Product weight -->
    <td>
      <!-- Text -->
      <?= Text::_('J2STORE_PRODUCT_WEIGHT'); ?>
    </td>

    <td>
      <!-- Main Weight span -->
      <span class="product-weight">
        <?php

        // Check if product has variants
        if (
          isset($this->product->variant)
          && !empty($this->product->variant)
        ) {
          // Check if product has weight
          if ($this->product->variant->weight) {
            // Show weight
            echo round($this->product->variant->weight, 2);

            // SHow title
            echo $this->product->variant->weight_title;
          }
        } else {
          // No weight
          echo Text::_('J2STORE_EMPTY_DASHES');
        } ?>
      </span>
    </td>
  </tr>
</table>
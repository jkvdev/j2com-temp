<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\Uri\Uri;

// No direct access
defined('_JEXEC') or die;

// Get root url of the site
$image_path = Uri::root();
// Initialize main product image path
$main_image = "";
// Get width for main product
$main_image_width = $this->params->get(
  'item_product_main_image_width',
  '200'
);
// Get width for additional products
$additional_image_width = $this->params->get(
  'item_product_additional_image_width',
  '100'
);

// Call J2Store platform helper
$platform = J2Store::platform();

// If main image display is enabled
if (
  $this->params->get('item_show_product_main_image', 1)
  && !empty($this->product->main_image)
): ?>
  <!-- Main Image DIV -->
  <div class="j2store-mainimage">
    <?php

    // Get Image path
    $main_image = $platform->getImagePath(
      $this->product->main_image
    );

    // If image path exists
    if (!empty($main_image)):
      // Set class to zoom or no
      $class = $this->params->get('item_enable_image_zoom', 1)
        ? 'zoom'
        : 'nozoom'; ?>

      <!-- Span for the image -->
      <span
        id="j2store-item-main-image-<?= $this->product->j2store_product_id; ?>"
        class="<?= $class; ?> bg-light p-0 m-0 d-flex align-items center justify-content-center w-100 overflow-hidden"
        style="height: 460px;">

        <!-- Main Image -->
        <!-- width="<?= intval($main_image_width); ?>" -->
        <img
          alt="<?= (!empty($this->product->main_image_alt))
                  ? $this->escape($this->product->main_image_alt)
                  : $this->escape($this->product->product_name); ?>"
          title="<?= $this->escape($this->product->product_name); ?>"
          src="<?= $main_image; ?>"
          class="j2store-product-main-image j2store-img-responsive m-0 p-0 img-fluid mx-auto d-block object-contain"
          style="max-height: 100%;" />

      </span>

      <script type="text/javascript">
        // Get main image path
        var main_image = "<?= $image_path . $main_image; ?>";

        // When html is fully loaded
        j2store.jQuery(document).ready(function() {
          // Get image zoom
          var enable_zoom = <?= $this->params->get('item_enable_image_zoom', 1); ?>;

          // If image is zoomed
          if (enable_zoom) {
            // Zoom into the image
            j2store.jQuery(
                '#j2store-item-main-image-<?= $this->product->j2store_product_id; ?>'
              )
              .zoom({
                magnify: 0.3 // Adjust this value (Default is 2)
              });
          }
        });
      </script>

    <?php
    // Fallback: If main image is not empty
    elseif (!empty($this->product->main_image)):
      // Display image using fallback
      echo J2Store::product()->displayImage(
        $this->product,
        array(
          'type' => 'ViewMain',
          'params' => $this->params,
          'alt' => $this->escape(
            $this->product->main_image_alt
          )
        )
      );
    endif; ?>
  </div>

  <?php endif;

// If product has additional images
if (
  $this->params->get('item_show_product_additional_image', 1)
  && isset($this->product->additional_images)
  && !empty($this->product->additional_images)
):
  // Get additional images from JSON data
  $additional_images = json_decode($this->product->additional_images);
  // Keep only non empty images
  $additional_images = array_filter((array)$additional_images);

  // If there are additional images
  if (count($additional_images)) :
    // Get the alt text
    $additional_images_alt = json_decode(
      $this->product->additional_images_alt,
      true
    );
  ?>
    <!-- Additional images DIV -->
    <div class="j2store-product-additional-images my-3">
      <!-- Image list -->
      <ul class="additional-image-list p-0 d-flex gap-3">
        <?php

        // Loop through all images
        foreach ($additional_images as $key => $image):

          // Get image path
          $image = $platform->getImagePath($image);

          // If image is not empty
          if (!empty($image)):
            // set the path of the image
            $image_src = $image;
        ?>
            <!-- Display Image -->
            <!-- 
            onmouseover="setMainPreview('addimage-<?= $this->product->j2store_product_id; ?>-<?= $key; ?>', <?= $this->product->j2store_product_id; ?>, <?= $this->params->get('item_enable_image_zoom', 1); ?>, 'inner')"
             -->
            <li
              class='bg-light overflow-hidden d-flex align-items-center justify-content-center p-0 m-0'
              style="width: 120px; height: 120px; cursor: pointer;"
              onclick="setMainPreview('addimage-<?= $this->product->j2store_product_id; ?>-<?= $key; ?>', <?= $this->product->j2store_product_id; ?>, <?= $this->params->get('item_enable_image_zoom', 1); ?>, 'inner')">
              <img
                id="addimage-<?= $this->product->j2store_product_id; ?>-<?= $key; ?>"
                class="j2store-item-additionalimage-preview j2store-img-responsive object-fit-contain img-fluid d-block m-0 p-0"
                style="max-width: 100%; max-height: 100%;"
                alt="<?= (isset($additional_images_alt[$key])
                        && !empty($additional_images_alt[$key]))
                        ? $this->escape($additional_images_alt[$key])
                        : $this->escape($this->product->product_name); ?>"
                title="<?= $this->escape($this->product->product_name); ?>"
                src="<?= $image_src; ?>"
                width="<?= intval($additional_image_width); ?>" />
            </li>
          <?php

          // Fallbacks for rendering
          elseif (!empty($image)):
            // Display fallback image
            echo J2Store::product()->displayImage(
              $this->product,
              array(
                'type' => 'ViewAdditional',
                'params' => $this->params,
                'key' => $key,
                'image' => $image,
                'alt' => (isset($additional_images_alt[$key])
                  && !empty($additional_images_alt[$key]))
                  ? $this->escape($additional_images_alt[$key])
                  : $this->escape($this->product->product_name)
              )
            );
          endif;
        endforeach;

        // If main image is not empty
        if (!empty($main_image)): ?>
          <!-- Render main product as the last image -->
          <li
            class='bg-light overflow-hidden d-flex align-items-center justify-content-center p-0 m-0'
            style="width: 120px; height: 120px; cursor: pointer;"
            onclick="setMainPreview('additial-main-image-<?= $this->product->j2store_product_id; ?>', <?= $this->product->j2store_product_id; ?>, <?= $this->params->get('item_enable_image_zoom', 1); ?>, 'inner')">
            <!-- Render Image -->
            <!-- 
            onmouseover="setMainPreview('additial-main-image-<?= $this->product->j2store_product_id; ?>', <?= $this->product->j2store_product_id; ?>, <?= $this->params->get('item_enable_image_zoom', 1); ?>, 'inner')"
             -->
            <img
              id="additial-main-image-<?= $this->product->j2store_product_id; ?>"
              class="j2store-item-additionalimage-preview j2store-img-responsive additional-mainimage object-fit-contain img-fluid d-block m-0 p-0"
              style="max-width: 100%; max-height: 100%;"
              alt="<?= (!empty($this->product->main_image_alt))
                      ? $this->escape($this->product->main_image_alt)
                      : $this->escape($this->product->product_name); ?>"
              title="<?= $this->escape($this->product->product_name); ?>"
              src="<?= $main_image; ?>"
              width="<?= intval($additional_image_width); ?>" />
          </li>
        <?php

        // If there is a fallback error
        elseif (!empty($this->product->main_image)):
          // Display fallback image
          echo J2Store::product()->displayImage(
            $this->product,
            array(
              'type' => 'AdditionalMain',
              'params' => $this->params,
              'alt' => $this->escape(
                $this->product->main_image_alt
              )
            )
          );
        endif; ?>
      </ul>
    </div>
  <?php endif;
endif;

// If zoom is enabled
if ($this->params->get('item_enable_image_zoom', 1)) : ?>
  <script>
    // When html is ready
    j2store
      .jQuery(document)
      .ready(function() {
        j2store
          .jQuery('body')
          .on('after_doAjaxFilter', function(e, product, response) {
            // Remove zoomed-in images
            j2store.jQuery('img.zoomImg').remove();

            // Apply zoom effect to the main image
            j2store.jQuery(
                '#j2store-item-main-image-<?= $this->product->j2store_product_id; ?>')
              .zoom();
          });
      });
  </script>
<?php endif; ?>
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

// Setting up variables
$image_path = Uri::root();
$image_type = $this->params->get('list_image_type', 'thumbnail');
$main_image = "";
$platform = J2Store::platform();

// Check whether to show images or not
if ($this->params->get('list_show_image', 1)):
?>
  <!-- Main Image Div For Rendering -->
  <div
    class="j2store-product-images bg-light overflow-hidden rounded-top-3 mb-2">
    <!-- Check if the image is of type thumbnail -->
    <?php if ($image_type == 'thumbimage'): ?>
      <!-- Main Thumbnail Image Div -->
      <div
        class="j2store-thumbnail-image w-100 h-100 d-flex justify-content-center align-center"
        style="max-width: 600px; max-height: 200px;">
        <?php
        // Retrieving Thumbnail image path
        $thumb_image = $platform->getImagePath($this->product->thumb_image);

        // Check if the thumbnail image path exists
        if (!empty($thumb_image)):
          // Check if the image points to a link
          if ($this->params->get('list_image_link_to_product', 1)): ?>
            <!-- Rendering thumbnail image as a link -->
            <a href="<?= $this->product->product_link; ?>">
            <?php endif; ?>

            <!-- 
            //TODO: USE CUSTOM CSS 
            -->
            <!-- Rendering thumbnail image -->
            <!-- Gathering alt and title tag information, if not set to product name -->
            <!-- width="<?= (int)$this->params->get('list_image_thumbnail_width', '200'); ?>" -->
            <img
              alt="<?= (!empty($this->product->thumb_image_alt)) ? $this->escape($this->product->thumb_image_alt) : $this->escape($this->product->product_name); ?>"
              title="<?= $this->escape($this->product->product_name); ?>"
              class="j2store-img-responsive j2store-product-thumb-image-<?= $this->product->j2store_product_id; ?> w-100 h-100 object-fit-cover"
              src="<?= $thumb_image ?>"

              style="object-fit: cover; transition: transform 0.3s ease-in-out;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'" />
            <?php
            // Check if the thumbnail has a link
            if ($this->params->get('list_image_link_to_product', 1)): ?>
              <!-- End Link -->
            </a>
        <?php endif;

          // Check if the Thumbnail Image is set
          elseif (!empty($this->product->thumb_image)):

            // Display The thumbnail Image
            echo J2Store::product()->displayImage(
              $this->product,
              array(
                'type' => 'Thumb',
                'params' => $this->params,
                'alt' => $this->escape($this->product->thumb_image_alt)
              )
            );

          endif; ?>
      </div>
    <?php endif;

    // Check whether the image is of main type
    if ($image_type == 'mainimage'): ?>
      <!-- Main Image Div Render Tag -->
      <div class="j2store-mainimage">
        <?php
        // Retrieve Main Image Path
        $main_image = $platform->getImagePath($this->product->main_image);

        // Check if the main image path is not empty
        if (!empty($main_image)):
          // Check if the main image has a link
          if ($this->params->get('list_image_link_to_product', 1)): ?>
            <!-- Render Main Image Link -->
            <a href="<?= $this->product->product_link; ?>">
            <?php endif; ?>

            <!-- Render Main Image -->
            <!-- Retrieve main Image alt and title tags if not default to product name -->
            <img
              alt="<?= (!empty($this->product->main_image_alt)) ? $this->escape($this->product->main_image_alt) : $this->escape($this->product->product_name); ?>"
              title="<?= $this->escape($this->product->product_name); ?>"
              class="j2store-img-responsive j2store-product-main-image-<?= $this->product->j2store_product_id; ?>"
              src="<?= $main_image; ?>"
              width="<?= (int)$this->params->get('list_image_thumbnail_width', '200'); ?>" />
            <?php
            // Check if main image has a link
            if ($this->params->get('list_image_link_to_product', 1)): ?>
              <!-- End Link -->
            </a>
        <?php endif;

          // Check if main image is set
          elseif (!empty($this->product->main_image)):

            // Display main image
            echo J2Store::product()->displayImage(
              $this->product,
              array(
                'type' => 'Main',
                'params' => $this->params,
                'alt' => $this->escape($this->product->main_image_alt)
              )
            );

          endif; ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
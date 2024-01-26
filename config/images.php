<?php

return [
  /*
  The path to upload the original image with variants
  In addition each of store has its own path that contains item original watermarked images.
  For example
    $['product_images_path'] + '/{$store->domain}'
  */
  'product_image_path' => 'uploads/baseitem_img',
  'product_original_path_append' => '/original',
  'product_thumbnail_path_append' => '/thumbnails',

  'store_watermark_path' => 'uploads/store_watermark_img',

  'normal_scale' => 600,
  'thumbnail_scale' => 300,
];
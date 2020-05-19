<div class="library-services library-listing-available-services">
  <?php
  require_once(__DIR__."/../mainlib-magellan-faicons.php");

  $postTerms = wp_get_post_terms($postId, "wpsl_store_category");

  //Get terms for wpsl_store_category
  $terms = mainlib_magellan_get_all_library_service_terms();
  $defaultIcon = get_option("mainlib_magellan_default_icon");
  foreach($terms as $term) {
    $meta = get_fields("term_{$term->term_id}");
    $meta = ($meta) ? $meta : array();
    if (!array_key_exists("service_icon", $meta)) {
      $meta['service_icon'] = $defaultIcon;
    }

    $default = (array_key_exists("default_service", $meta)) ? (bool)$meta['default_service'] : false;
    $available = in_array($term, $postTerms);

    if ($available || $default) {
      ?>
      <figure class='library-listing-single-service <?php echo ($available) ? "service-available" : ""; ?>'>
        <div class='library-service-icon'>
          <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 56 56" class="library-service-icon-svg">
            <text x="28" y="28"
                  class="library-service-icon-svg-text main-<?php echo substr($meta['service_icon'], 0, 3); ?>">
              &#x<?php echo $iconClassList[$meta['service_icon']]; ?>;
            </text>
            <circle class="library-service-icon-svg-available-background" cx="45" cy="44" r="7"/>
            <text x="45" y="45" class="library-service-icon-svg-available">&#xf058;</text>
          </svg>
        </div>
        <figcaption class='library-service-icon-title'>
          <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 100 24" class="library-service-icon-title-svg">
              <?php
              $displayName = (strlen($term->name) > 18) ? substr($term->name, 0, 15)."..." : $term->name;
              ?>
              <text x="50" y="12" <?php if (strlen($displayName) > 14) { ?>textLength="100%" <?php } ?>
                  class="library-service-icon-title-svg-text"><?php echo $displayName; ?></text>
          </svg>
        </figcaption>
      </figure>
      <?php
    }
  }
  ?>
</div>
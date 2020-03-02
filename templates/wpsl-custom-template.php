<?php
global $wpsl_settings, $wpsl, $atts;

//Include Modal Select widget
wp_enqueue_style("modalSelect", plugin_dir_url(__FILE__)."../css/jquery.modal-select.css");
wp_enqueue_script("modalSelect", plugin_dir_url(__FILE__)."../js/jquery.modal-select.js", array('jquery'));

//Include Magellan resources
wp_enqueue_style("magellan", plugin_dir_url(__FILE__)."../css/magellan.css");
wp_enqueue_script("magellan", plugin_dir_url(__FILE__)."../js/magellan.js", array('jquery'));

$autoload_class = ( !$wpsl_settings['autoload'] ) ? 'class="wpsl-not-loaded"' : '';
$wrapperClass = (array_key_exists("view", $_REQUEST) && in_array($_REQUEST['view'], ["list", "map"])) ? "view-{$_REQUEST['view']}" : "view-map";

ob_start();
$thumb_size = $this->get_store_thumb_size();
?>

  <style>
    #wpsl-wrap.view-map #wpsl-gmap {
      max-height: <?php echo $wpsl_settings['height']; ?>px;
    }

    <?php if ( isset( $thumb_size[0] ) && is_numeric( $thumb_size[0] ) && isset( $thumb_size[1] ) && is_numeric( $thumb_size[1] ) ) { ?>
    #wpsl-stores .wpsl-store-thumb {
      height:<?php echo esc_attr( $thumb_size[0] ); ?>px !important;
      width:<?php echo esc_attr( $thumb_size[1] ); ?>px !important;
    }
    <?php } ?>

    <?php if ( $wpsl_settings['template_id'] == 'below_map' && $wpsl_settings['listing_below_no_scroll'] ) { ?>
    #wpsl-gmap {
      height: <?php echo esc_attr( $wpsl_settings['height'] );?>px !important;
    }
    #wpsl-stores, #wpsl-direction-details {
      height:auto !important;
    }
    <?php } else { ?>
    #wpsl-stores, #wpsl-direction-details, #wpsl-gmap {
      height: <?php echo esc_attr( $wpsl_settings['height'] ); ?>px !important;
    }
    <?php } ?>
    #wpsl-gmap .wpsl-info-window {
      max-width: <?php echo esc_attr( $wpsl_settings['infowindow_width'] ); ?>px !important;
    }
  </style>

  <div id='wpsl-wrap' class='<?php echo $wrapperClass; ?>'>
    <div class="wpsl-search wpsl-clearfix <?php echo $this->get_css_classes(); ?>">
      <form autocomplete="off">
        <div id="wpsl-search-wrap-magellan">

          <div class="magellan-input-group c2">
            <label for="wpsl-search-input"><?php echo esc_html( $wpsl->i18n->get_translation( 'search_label', __( 'Your location', 'wpsl' ) ) ); ?></label>
            <input id="wpsl-search-input" class="magellan-input" type="text" value="<?php echo apply_filters( 'wpsl_search_input', '' ); ?>" name="wpsl-search-input" placeholder="" aria-required="true" />
          </div>

          <?php
            if ( $wpsl_settings['radius_dropdown'] ) {
              ?>
              <div id="wpsl-radiu" class="magellan-input-group">
                <label for="wpsl-radius-dropdown"><?php echo esc_html( $wpsl->i18n->get_translation( 'radius_label', __( 'Search radius', 'wpsl' ) ) ); ?></label>
                <select id="wpsl-radius-dropdown" class="wpsl-dropdow magellan-input" name="wpsl-radius">
                  <?php echo $this->get_dropdown_list( 'search_radius' );?>
                </select>
              </div>
              <?php
            }
            if ( $wpsl_settings['results_dropdown'] ) {
              ?>
              <div id="wpsl-result" class="magellan-input-group">
                <label for="wpsl-results-dropdown"><?php echo esc_html( $wpsl->i18n->get_translation( 'results_label', __( 'Results', 'wpsl' ) ) ); ?></label>
                <select id="wpsl-results-dropdown" class="wpsl-dropdow magellan-input" name="wpsl-results">
                  <?php echo $this->get_dropdown_list( 'max_results' ); ?>
                </select>
              </div>
              <?php
            }

          if ( $wpsl_settings['category_filter'] ) {

            if ( isset( $this->sl_shortcode_atts['category_filter_type'] ) ) {
              $filter_type = $this->sl_shortcode_atts['category_filter_type'];
            } else {
              $filter_type = $wpsl_settings['category_filter_type'];
            }

            if("magellan" == $filter_type) {
              echo mainlib_magellan_create_category_filter($this->sl_shortcode_atts);
            } else {
              echo $this->create_category_filter();
            }
          }
          ?>
          <div class="magellan-input-group c2">
            <label>&nbsp;</label>
            <input id="wpsl-search-btn" type="submit" value="<?php echo esc_attr( $wpsl->i18n->get_translation( 'search_btn_label', __( 'Search', 'wpsl' ) ) ); ?>">
            <div class="wpsl-view-select">
              <button type="button" class="view-active view-select-button view-map">&#xf279;</button>
              <button type="button" class="view-select-button view-list">&#xf00b;</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div id="wpsl-gmap" class="wpsl-gmap-canvas"></div>

    <div id="wpsl-result-list">
      <div id="wpsl-stores" <?php echo $autoload_class;?>>
        <ul></ul>
      </div>
      <div id="wpsl-direction-details">
        <ul></ul>
      </div>
    </div>
  </div>

<?php
return ob_get_clean();
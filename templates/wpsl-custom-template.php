<?php
global $wpsl_settings, $wpsl;

//Include Magellan resources
wp_enqueue_style("magellan", plugin_dir_url(__FILE__)."../css/magellan.css");
wp_enqueue_script("magellan", plugin_dir_url(__FILE__)."../js/magellan.js");

$output         = $this->get_custom_css();
$output .= "<style>#wpsl-wrap.view-map #wpsl-gmap {max-height: {$wpsl_settings['height']}px}</style>";
$autoload_class = ( !$wpsl_settings['autoload'] ) ? 'class="wpsl-not-loaded"' : '';
$wrapperClass = (array_key_exists("view", $_REQUEST) && in_array($_REQUEST['view'], ["list", "map"])) ? "view-{$_REQUEST['view']}" : "view-map";
$output .= "<div id='wpsl-wrap' class='{$wrapperClass}'>" . "\r\n";
$output .= "\t" . '<div class="wpsl-search wpsl-clearfix ' . $this->get_css_classes() . '">' . "\r\n";
$output .= "\t\t" . '<div id="wpsl-search-wrap">' . "\r\n";
$output .= "\t\t\t" . '<form autocomplete="off">' . "\r\n";
$output .= "\t\t\t" . '<div class="wpsl-input">' . "\r\n";
$output .= "\t\t\t\t" . '<div><label for="wpsl-search-input">' . esc_html( $wpsl->i18n->get_translation( 'search_label', __( 'Your location', 'wpsl' ) ) ) . '</label></div>' . "\r\n";
$output .= "\t\t\t\t" . '<input id="wpsl-search-input" type="text" value="' . apply_filters( 'wpsl_search_input', '' ) . '" name="wpsl-search-input" placeholder="" aria-required="true" />' . "\r\n";
$output .= "\t\t\t" . '</div>' . "\r\n";

if ( $wpsl_settings['radius_dropdown'] || $wpsl_settings['results_dropdown']  ) {
  $output .= "\t\t\t" . '<div class="wpsl-select-wrap">' . "\r\n";

  if ( $wpsl_settings['radius_dropdown'] ) {
    $output .= "\t\t\t\t" . '<div id="wpsl-radius">' . "\r\n";
    $output .= "\t\t\t\t\t" . '<label for="wpsl-radius-dropdown">' . esc_html( $wpsl->i18n->get_translation( 'radius_label', __( 'Search radius', 'wpsl' ) ) ) . '</label>' . "\r\n";
    $output .= "\t\t\t\t\t" . '<select id="wpsl-radius-dropdown" class="wpsl-dropdown" name="wpsl-radius">' . "\r\n";
    $output .= "\t\t\t\t\t\t" . $this->get_dropdown_list( 'search_radius' ) . "\r\n";
    $output .= "\t\t\t\t\t" . '</select>' . "\r\n";
    $output .= "\t\t\t\t" . '</div>' . "\r\n";
  }

  if ( $wpsl_settings['results_dropdown'] ) {
    $output .= "\t\t\t\t" . '<div id="wpsl-results">' . "\r\n";
    $output .= "\t\t\t\t\t" . '<label for="wpsl-results-dropdown">' . esc_html( $wpsl->i18n->get_translation( 'results_label', __( 'Results', 'wpsl' ) ) ) . '</label>' . "\r\n";
    $output .= "\t\t\t\t\t" . '<select id="wpsl-results-dropdown" class="wpsl-dropdown" name="wpsl-results">' . "\r\n";
    $output .= "\t\t\t\t\t\t" . $this->get_dropdown_list( 'max_results' ) . "\r\n";
    $output .= "\t\t\t\t\t" . '</select>' . "\r\n";
    $output .= "\t\t\t\t" . '</div>' . "\r\n";
  }

  $output .= "\t\t\t" . '</div>' . "\r\n";
}

if ( $wpsl_settings['category_filter'] ) {
  $output .= $this->create_category_filter();
}

$output .= "\t\t\t\t" . '<div class="wpsl-search-btn-wrap"><input id="wpsl-search-btn" type="submit" value="' . esc_attr( $wpsl->i18n->get_translation( 'search_btn_label', __( 'Search', 'wpsl' ) ) ) . '">' . "\r\n";

/**
 * Magellan Custom View control
 */
$output .= '<div class="wpsl-view-select"><button type="button" class="view-active view-select-button view-map">&#xf279;</button><button type="button" class="view-select-button view-list">&#xf00b;</button></div>';
$output .= "\t\t\t\t" . '</div>' . "\r\n";



$output .= "\t\t" . '</form>' . "\r\n";
$output .= "\t\t" . '</div>' . "\r\n";
$output .= "\t" . '</div>' . "\r\n";

$output .= "\t" . '<div id="wpsl-gmap" class="wpsl-gmap-canvas"></div>' . "\r\n";

$output .= "\t" . '<div id="wpsl-result-list">' . "\r\n";
$output .= "\t\t" . '<div id="wpsl-stores" '. $autoload_class .'>' . "\r\n";
$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
$output .= "\t\t" . '</div>' . "\r\n";
$output .= "\t\t" . '<div id="wpsl-direction-details">' . "\r\n";
$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
$output .= "\t\t" . '</div>' . "\r\n";
$output .= "\t" . '</div>' . "\r\n";

if ( $wpsl_settings['show_credits'] ) {
  $output .= "\t" . '<div class="wpsl-provided-by">'. sprintf( __( "Search provided by %sWP Store Locator%s", "wpsl" ), "<a target='_blank' href='https://wpstorelocator.co'>", "</a>" ) .'</div>' . "\r\n";
}

$output .= '</div>' . "\r\n";

return $output;
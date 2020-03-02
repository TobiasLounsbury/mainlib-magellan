<?php


/**
 * Returns all of the Library Services in their sorted order
 *
 * @param boolean $hideEmpty Used to either include or exclude categories that are empty
 * @return array|int|WP_Error
 */
function mainlib_magellan_get_all_library_service_terms($hideEmpty = false) {
  return get_terms( array(
      'taxonomy' => 'wpsl_store_category',
      'orderby' => "term_order",
      'hide_empty' => $hideEmpty,
  ) );
}


/**
 * Create the category filter.
 *
 * @todo create another func that accepts a meta key param to generate
 * a dropdown with unique values. So for example create_filter( 'restaurant' ) will output a
 * filter with all restaurant types. This can be used in a custom theme template.
 *
 * @since 2.0.0
 * @return string|void $category The HTML for the category dropdown, or nothing if no terms exist.
 */
function mainlib_magellan_create_category_filter($shortcodeOptions) {

  global $wpsl, $wpsl_settings;

  $categories = (isset($shortcodeOptions['category'])) ? explode(",", $shortcodeOptions['category']) : array();

  $args = apply_filters( 'wpsl_dropdown_category_args', array(
          'show_option_none'  => $wpsl->i18n->get_translation( 'category_default_label', __( 'Any', 'wpsl' ) ),
          'option_none_value' => '0',
          'orderby'           => 'NAME',
          'order'             => 'ASC',
          'echo'              => 0,
          'selected'          => $categories,
          'hierarchical'      => 1,
          'name'              => 'wpsl-category',
          'id'                => 'wpsl-category-list',
          'class'             => 'wpsl-dropdown wpsl-dropdown-magellan',
          'taxonomy'          => 'wpsl_store_category',
          'hide_if_empty'     => true
      )
  );


  $terms = get_terms( array(
      'taxonomy' => $args['taxonomy'],
      'orderby' => $args['orderby'],
      'order' => $args['order'],
      'hide_empty' => $args['hide_if_empty'],
  ));


  if ( count( $terms ) > 0 ) {

    $category = '<div id="wpsl-category" class="magellan-input-group c2">' . "\r\n";
    $category .= "<label for='${args['id']}'>" . esc_html( $wpsl->i18n->get_translation( 'category_label', __( 'Category', 'wpsl' ) ) ) . '</label>' . "\r\n";
    $category .= "<select name='${args['name']}' id='${args['id']}' class='${args['class']} magellan-input' multiple='multiple'>\n";

    foreach($terms as $term) {
      $selected = (in_array($term->term_id, $categories) || in_array($term->slug, $categories)) ? "selected" : "";
      $category .= "<option value='{$term->term_id}' $selected>{$term->name}</option>";
    }

    $category .= "</select>";
    $category .= '</div>' . "\r\n";
    return $category;
  }
}
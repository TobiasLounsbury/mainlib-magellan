<?php
global $wpsl_settings, $wpsl;

require_once(__DIR__."/../mainlib-magellan-faicons.php");

//fetch the list of library services
//Get terms for wpsl_store_category
$terms = mainlib_magellan_get_all_library_service_terms();

$defaultIcon = get_option('mainlib_magellan_default_icon');

?>
<li data-store-id="<%= id %>">
    <div class="wpsl-store-location library-listing">
        <div class="library-listing-title">
            <?php if ($wpsl_settings['store_url']) { ?>
            <a href="<%= permalink %>" target="_blank"><strong><%= store %></strong></a>
            <?php } else { ?>
                <strong><%= store %></strong>
            <?php } ?>
            <span class="library-listing-more-detail library-listing-toggle-more-detail" title="<?php echo get_option("mainlib_magellan_more_info_hint", "Show Hours and More Details"); ?>"><i class="fas fa-chevron-right"></i></span></div>
        <% if ( thumb ) { %>
        <div class="library-listing-thumb"><%= thumb %></div>
        <% } %>
        <div class="library-listing-available-services">
          <?php
          foreach($terms as $term) {
            $meta = get_fields("term_{$term->term_id}");
            $meta = ($meta) ? $meta : array();
            if(!array_key_exists("service_icon", $meta)) {
                $meta['service_icon'] = $defaultIcon;
            }
            if(!array_key_exists("default_service", $meta) || !$meta['default_service']) {
                echo "<% if ( _.contains(services, '{$term->slug}') ) { %>";
            }
            ?>
              <figure class='library-listing-single-service <% if ( _.contains(services, '<?php echo $term->slug; ?>') ) { %>service-available<% } %>'>
              <div class='library-service-icon'><svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 56 56" class="library-service-icon-svg">
                      <text x="28" y="28" class="library-service-icon-svg-text main-<?php echo substr($meta['service_icon'], 0, 3); ?>" >&#x<?php echo $iconClassList[$meta['service_icon']]; ?>;</text>
                      <circle class="library-service-icon-svg-available-background" cx="45" cy="44" r="7"/>
                      <text x="45" y="45" class="library-service-icon-svg-available" >&#xf058;</text>
                  </svg></div>
              <figcaption class='library-service-icon-title'><svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 76 18" class="library-service-icon-title-svg">
                      <?php
                      $displayName = (strlen($term->name) > 18) ? substr($term->name, 0, 15)."..." : $term->name;
                      ?>
                      <text x="38" y="9" <?php if (strlen($displayName) > 14 ) { ?>textLength="100%" <?php } ?>  class="library-service-icon-title-svg-text" ><?php echo $displayName; ?></text>
                  </svg></figcaption></figure><?php
            if(!array_key_exists("default_service", $meta) || !$meta['default_service']) {
                echo "<% } %>";
            }
          }
          ?></div>

        <div class="library-details">
            <div >
                <span class="library-listing-distance"><%= distance %> <?php echo esc_html( wpsl_get_distance_unit() ); ?></span>
                <span class="wpsl-street"><%= address %></span>
                <% if ( address2 ) { %>
                <span class="wpsl-street"><%= address2 %></span>
                <% } %>
                <span><?php echo wpsl_address_format_placeholders(); ?></span>
              <?php if (!$wpsl_settings['hide_country']) {?><span class="wpsl-country"><%= country %></span><?php } ?>
                </p>

              <?php if ( $wpsl_settings['show_contact_details'] ) { ?>
                  <p class="wpsl-contact-details">
                      <% if ( phone ) { %>
                      <span><%= formatPhoneNumber( phone ) %></span>
                      <% } %>
                      <% if ( fax ) { %>
                      <span><strong><?php echo esc_html( $wpsl->i18n->get_translation( 'fax_label', __( 'Fax', 'wpsl' ) ) ); ?></strong>: <%= fax %></span>
                      <% } %>

                      <% if ( url ) { %>
                      <span ><a href="<%= url %>" target="_blank"><% if ( url.length < 35 ) { %><%= url %><% } else { %>Website <i class="fas fa-external-link-alt"></i><% } %></a></span>
                      <% } %>

                      <% if ( email ) { %>
                      <span><strong><?php echo esc_html( $wpsl->i18n->get_translation( 'email_label', __( 'Email', 'wpsl' ) ) ); ?></strong>: <%= formatEmail( email ) %></span>
                      <% } %>
                  </p>
              <?php } ?>

                <% if ( special_hours_today ) { %>
                    <?php echo get_option('mainlib_magellan_special_hours_warning_text'); ?>
                <% } %>

                <span class="library-listing-toggle-more-detail library-listing-more-detail-link" title="<?php echo get_option("mainlib_magellan_more_info_hint", "Show Hours and More Details"); ?>">
                    <span class="library-listing-more-detail-link-text more">-More Info-</span>
                    <span class="library-listing-more-detail-link-text less">-Less Info-</span>
                </span>

              <?php if ( !$wpsl_settings['hide_hours'] ) { ?>
                  <% if ( hours ) { %>
                  <div class="wpsl-store-hours expandable-js"><strong><?php echo esc_html( $wpsl->i18n->get_translation( 'hours_label', __( 'Hours', 'wpsl' ) ) ) ?></strong><%= hours %></div>
                  <% } %>
              <?php } ?>

            </div>

            <div class="library-actions">
                <%= createDirectionUrl().replace(wpslLabels.directions, "<span class='library-action-button' title='<?php echo get_option("mainlib_magellan_navigation_hint", "Open in Google Maps"); ?>'><i class='fas fa-location-arrow'></i></span>") %>
                <% if ( phone ) { %>
                <a href="tel:<%= phone %>" title="<?php echo get_option("mainlib_magellan_call_hint", "Call this Library"); ?>"><span class='library-action-button'><i class='fas fa-phone-alt'></i></span></a>
                <% } %>
                <span class='library-listing-more-detail-i library-action-button library-listing-toggle-more-detail' title="<?php echo get_option("mainlib_magellan_more_info_hint", "Show Hours and More Details"); ?>"><i class='fas fa-clock'></i></span>
            </div>
        </div>

        <% if ( description ) { %>
        <div class="library-description expandable-js"><%= description %></div>
        <% } %>

    </div>
</li>


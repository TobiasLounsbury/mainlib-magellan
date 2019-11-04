<?php
global $wpsl_settings, $wpsl;

//fetch the list of library services
//Get terms for wpsl_store_category
$terms = get_terms( array(
    'taxonomy' => 'wpsl_store_category',
    'hide_empty' => false,
) );

?>
<li data-store-id="<%= id %>">
    <div class="wpsl-store-location library-listing">
        <div class="library-listing-title"><strong><%= store %></a></strong><span class="library-listing-more-detail"><i class="fas fa-chevron-right"></i></span></div>
        <% if ( thumb ) { %>
        <div class="library-listing-thumb"><%= thumb %></div>
        <% } %>
        <div class="library-listing-available-services">
          <?php
          foreach($terms as $term) {
            $meta = get_fields("term_{$term->term_id}");
            if($meta['default_service']) {
              echo "<figure class='library-listing-single-service <% if ( _.contains(services, '{$term->slug}') ) { %>service-available<% } %>'>";
              echo "<div class='library-service-icon'><i class='{$meta['service_icon']}'></i></div>";
              echo "<figcaption class='library-service-icon-title expandable'>{$term->name}</figcaption>";
              echo "</figure>";
            }
          }
          ?>
        </div>


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

              <?php if ( !$wpsl_settings['hide_hours'] ) { ?>
                  <% if ( hours ) { %>
                  <div class="wpsl-store-hours expandable"><strong><?php echo esc_html( $wpsl->i18n->get_translation( 'hours_label', __( 'Hours', 'wpsl' ) ) ) ?></strong><%= hours %></div>
                  <% } %>
              <?php } ?>

            </div>

            <div class="library-actions">
                <%= createDirectionUrl().replace(wpslLabels.directions, "<span class='library-action-button'><i class='fas fa-location-arrow'></i></span>") %>
                <% if ( phone ) { %>
                <a href="tel:<%= phone %>"><span class='library-action-button'><i class='fas fa-phone-alt'></i></span></a>
                <% } %>
                <span class='library-listing-more-detail-i library-action-button'><i class='fas fa-info'></i></span>
            </div>
        </div>

        <div class="library-description expandable"><%= description %></div>

    </div>
</li>


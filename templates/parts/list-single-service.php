<?php
$customFields = get_fields("term_{$term->term_id}");
$icon = ($customFields && $customFields['service_icon']) ? $customFields['service_icon'] : "<i class='fas fa-concierge-bell' aria-hidden='true'></i>";
?>
<div class="library-service-listing">
  <div class="library-service-title"><?php echo $term->name; ?></div>
  <div class="library-service-details">
      <div class="library-service-icon"><i class="<?php echo $icon; ?>"></i></div>
    <div class="library-service-description"><?php echo $term->description; ?></div>
    <div class="library-service-permalink"><a href="/<?php echo $locationsPath; ?>/?wpsl-widget-categories=<?php echo $term->term_id; ?>#wpsl-result-list">Sites with this Service</a></div>
  </div>
  <div class="library-service-usage"><?php echo round(($term->count / $totalLocations) * 100); ?>% of MAIN Libraries offer <?php echo $term->name; ?>.</div>
</div>

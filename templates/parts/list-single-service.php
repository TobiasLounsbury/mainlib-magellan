<?php
$customFields = get_fields("term_{$term->term_id}");
$iconClass = ($customFields && array_key_exists("service_icon", $customFields)) ? $customFields['service_icon'] : $defaultIcon;
?>
<div class="library-service-listing">
    <div class="library-service-title"><strong><?php echo $term->name; ?></strong></div>
    <div class="library-service-details">
        <div class="library-service-icon"><i class="<?php echo $iconClass; ?>"></i></div>
        <div class="mainlib-no-after">
            <div class="library-service-description"><?php echo $term->description; ?></div>
            <div class="library-service-permalink"><a href="/<?php echo $locationsPath; ?>/?wpsl-widget-categories=<?php echo $term->term_id; ?>&wpsl-widget-search-radius=<?php echo get_option('mainlib_magellan_services_radius', '30'); ?>#wpsl-result-list">Sites with this Service</a></div>
            <div class="library-service-usage"><?php echo round(($term->count / $totalLocations) * 100); ?>% of MAIN Libraries offer <?php echo $term->name; ?>.</div>
        </div>
    </div>
</div>

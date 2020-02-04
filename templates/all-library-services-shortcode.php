<div class="all-library-services">
<?php
//Get terms for wpsl_store_category
$terms = mainlib_magellan_get_all_library_service_terms();
$totalLocations = (int) wp_count_posts("wpsl_stores")->publish;
$locationsPath = get_option("mainlib_magellan_locations_path");
$defaultIcon = get_option("mainlib_magellan_default_icon");
foreach($terms as $term) {
include(__DIR__."/parts/list-single-service.php");
}
?>
</div>
<div class="all-library-services">
<?php
//Get terms for wpsl_store_category
$terms = get_terms( array(
    'taxonomy' => 'wpsl_store_category',
    'hide_empty' => false,
) );
$totalLocations = (int) wp_count_posts("wpsl_stores")->publish;
$locationsPath = get_option("mainlib_magellan_locations_path");
foreach($terms as $term) {
include(__DIR__."/parts/list-single-service.php");
}
?>
</div>
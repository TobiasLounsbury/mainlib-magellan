<div class="all-library-services">
<?php
//Get terms for wpsl_store_category
$terms = mainlib_magellan_get_all_library_service_terms(false, "name");

if(!empty($terms)) {

  $sortby = (array_key_exists("sort-services-by", $_REQUEST)) ? $_REQUEST['sort-services-by'] : "name";

  if (!property_exists($terms[0], $sortby)) {
    $sortby = "name";
  }

  usort($terms, function ($a, $b) use ($sortby) {
    if ($sortby == "count") {
      return ($a->$sortby == $b->$sortby) ? 0 : ($a->$sortby > $b->$sortby) ? -1 : 1;
    } else {
      return ($a->$sortby == $b->$sortby) ? 0 : ($a->$sortby < $b->$sortby) ? -1 : 1;
    }
  });


  $totalLocations = (int)wp_count_posts("wpsl_stores")->publish;
  $locationsPath = get_option("mainlib_magellan_locations_path");
  $defaultIcon = get_option("mainlib_magellan_default_icon");
  ?>
    <form method="get">
      <label for="sort-services-by"><strong>Sort: </strong></label>
      <select name="sort-services-by" id="sort-services-by"
              style="display: inline-block; width: 200px;" onchange="submit()">
        <option value="name" <?php echo ($sortby == "name") ? "selected" : ""; ?>>Alphabetically</option>
        <option value="count" <?php echo ($sortby == "count") ? "selected" : ""; ?>>Popularity</option>
      </select>
      <p>&nbsp;</p>
    </form>

  <?php
  foreach ($terms as $term) {
    include(__DIR__ . "/parts/list-single-service.php");
  }
} else {
  echo "<h3>No Library Services Found</h3>";
}
?>
</div>
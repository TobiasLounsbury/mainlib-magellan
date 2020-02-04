<?php


/**
 * Export
 */
function mainlib_magellan_export_closed_data() {

  $file_name = 'mainlib-closings-export-' . date('Ymd' ) . '.csv';

  // Set the download headers for the CSV file.
  header( 'Content-Type: text/csv; charset=utf-8' );
  header( 'Content-Disposition: attachment; filename=' . $file_name . '' );

  //Open the stream for writing
  $output = fopen( 'php://output', 'w' );

  //Output some csv headers
  fputcsv( $output, ["id", "name", "email", "closed_dates"]);

  //Fetch all the location posts.
  $locations = get_posts(['post_type' => 'wpsl_stores', 'numberposts' => -1]);

  //This is to switch between the two formats
  $wrap = (array_key_exists("magellan_export_format", $_REQUEST) && $_REQUEST['magellan_export_format'] == "csv-single");
  //If we are in December, we wont be looking for THIS year, so show next
  $year = (date("m") > 11) ? date("Y") + 1 : date("Y");
  //Get the last day of the year as a timestamp
  $end = strtotime("12/31/{$year}");
  foreach($locations as $location) {
    //Fetch the custom hours data
    $hourString = get_post_meta($location->ID, "wpsl_expanded_hours", true);
    $hours = json_decode($hourString, true);

    $closedDates = [];
    $date = strtotime("01/01/{$year}");

    //Loop through the days of the year
    while($date <= $end) {
      //Get the day of the week
      $dow = date("w", $date);
      //Check to see if this location is usually open on this day of the week.
      $closed = empty($hours[$dow]['periods']);

      //Check if we have a custom entry for this date
      if(array_key_exists(date("Y-m-d", $date), $hours)) {
        $closed = empty($hours[date("Y-m-d", $date)]['periods']);
      }

      //If the location is closed, log the date for inclusion in the csv
      if($closed) {
        if($wrap) {
          $closedDates[] = "'". date("m/d/Y", $date) ."'";
        } else {
          $closedDates[] = date("m/d/Y", $date);
        }
      }

      //Add a day (the number of seconds in a day)
      $date += 86400;
    }

    if($wrap) {
      $closedDates = [ implode(",", $closedDates) ];
    }

    //Fetch the coauthors (this is used to include an email address
    $coauthors = get_coauthors($location->ID);
    //Merge the closed dates with the location meta data
    $data = array_merge([
        $location->ID,
        $location->post_title,
        $coauthors[ sizeof($coauthors) - 1 ]->data->user_email
    ], $closedDates);

    //Output line to the stream
    fputcsv($output, $data);
  }

  //Close the stream
  fclose( $output );

  exit();

}
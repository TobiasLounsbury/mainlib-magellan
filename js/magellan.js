(function($) {

  //Handle changing to map view
  $(".wpsl-view-select .view-map").click(function(e) {
    //$("#wpsl-gmap").fadeIn();
    //Refresh the search results if we are re-showing the map, because the
    //zoom gets screwy when the map is hidden and search location changes
    //if($(".wpsl-view-select .view-list").hasClass("view-active")) {
    //  $("#wpsl-search-btn").click();
    //}
    //$(".wpsl-view-select .view-map").addClass("view-active");
    //$(".wpsl-view-select .view-list").removeClass("view-active");
    $("#wpsl-wrap").removeClass("view-list").addClass("view-map");
  });

  //Handle changing to list view
  $(".wpsl-view-select .view-list").click(function(e) {
   // $("#wpsl-gmap").fadeOut();
    //$(".wpsl-view-select .view-list").addClass("view-active");
    //$(".wpsl-view-select .view-map").removeClass("view-active");
    $("#wpsl-wrap").removeClass("view-map").addClass("view-list");
  });

  $("#page").click(function(event) {
    if ($(event.target).hasClass("library-listing-more-detail") || $(event.target).hasClass("library-listing-more-detail-i")) {
      let parent = $(event.target).closest(".library-listing");
      parent.toggleClass("expanded");
      if(parent.hasClass("expanded")) {
        parent.find(".expandable-js, .ibrary-listing-single-service:nth-child(n+6)").slideDown();
      } else {
        parent.find(".expandable-js, .ibrary-listing-single-service:nth-child(n+6)").slideUp();
      }
    }
  });

  //This moves the List of services down below the map and above the title
  $(".wpsl-locations-details").before( $(".post-item-single .library-services") );

})(jQuery);
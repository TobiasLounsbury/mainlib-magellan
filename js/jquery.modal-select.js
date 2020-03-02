// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;( function( $, window, document, undefined ) {

  "use strict";

  // undefined is used here as the undefined global variable in ECMAScript 3 is
  // mutable (ie. it can be changed by someone else). undefined isn't really being
  // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
  // can no longer be modified.

  // window and document are passed through as local variables rather than global
  // as this (slightly) quickens the resolution process and can be more efficiently
  // minified (especially when both are regularly referenced in your plugin).

  // Create the defaults once
  const pluginName = "modalSelect";
  let defaults = {
    selectClass: "default",
    modalClass: "default",
    overlayClass: "",
    transitionDelay: 200,
    noSelectionText: "None Selected",
    selectionText: "#count#: #names#",
    okText: "Ok",
    filterPlaceholder: "Filter",
  };

  // The actual plugin constructor
  function ModalSelect ( element, options ) {
    this.element = element;
    this._defaults = defaults;
    this._name = pluginName;
    this.settings = $.extend( {}, defaults, options );
    this._init();
  }

  // Avoid ModalSelect.prototype conflicts
  $.extend( ModalSelect.prototype, {

    /**
     * Initialization the widget
     *
     * @param action
     * @private
     */
    _init: function(action) {
      //todo: remove this
      console.log("init");
      //Make sure that the modal frame exists.
      if($("#jquery-modal-select-frame").length < 1) {
        this.$frame = $("<div class='jquery-modal-select-frame' id='jquery-modal-select-frame'></div>");
        let $overlay = $("<div class='jquery-modal-select-overlay " + this.settings.overlayClass + "'></div>");
        $overlay.on("click", () => {this.hideModalSelect();});
        this.$frame.append($overlay);

        let modal = $("<div class='jquery-modal-select-modal " + this.settings.modalClass + "'></div>");
        //todo: needs filter block
        let obj = $("<div class='jquery-modal-select-filter'></div>");
        this.$filterInput = $("<input id='jquery-modal-select-filter-input'  class='jquery-modal-select-filter-input' placeholder='" + this.settings.filterPlaceholder + "' />");
        obj.append(this.$filterInput);
        modal.append(obj);
        //Debounce the filter input
        this.$filterInput.on("keyup", () => {
          clearTimeout(this.filterTimer);
          this.filterTimer = setTimeout(() => {this._applyFilter()}, 200);
        });

        //Add an ok button
        modal.append("<div class='jquery-modal-select-okcancel'><span>" + this.settings.okText + "</span></div>");
        //add click handlers
        modal.find(".jquery-modal-select-okcancel").on("click", () => {this.hideModalSelect();});
        modal.appendTo(this.$frame);
        this.$frame.appendTo("body");
      } else {
        this.$filterInput = $("#jquery-modal-select-filter-input");
        this.$frame = $("#jquery-modal-select-frame");
      }

      //Mark if we are multiple or not
      this.isMultiple = $(this.element).prop('multiple');

      //Create an option list
      this.$optionsList = $("<div class='jquery-modal-select-options-group'></div>").hide();

      this.$optionsList.on("click", (e) => {
        if($(e.target).hasClass("jquery-modal-select-option")) {
          this.selectOption(e.target);
        }
      });

      //Add the option list to the modal.
      this.$frame.find(".jquery-modal-select-modal").append(this.$optionsList);

      //Hide the original element
      $(this.element).addClass("jquery-modal-hidden-accessible");

      //Replace the original element
      this.$selectionIndicator = $("<span class='jquery-modal-select-indicator'></span>");
      this.$selectionIndicator.insertAfter(this.element);

      //populate the option list
      this._buildOptions();

      //bind a click handler to the placeholder object
      this.$selectionIndicator.on("click", () => {this.showModalSelect()});

      //bind a change handler so we can adjust if the select get modified programatically
      $(this.element).on("change", () => {this.updateSelected()});
    },

    /**
     * Build the list of options from the select
     *
     * @private
     */
    _buildOptions: function() {
      //Create a list
      let ul = $("<ul></ul>");
      $(this.element).find("option").each(function() {
        let li = $("<li class='jquery-modal-select-option' data-value='" + $(this).val() + "'><span>" + $(this).text() + "</span></li>");
        li.toggleClass("selected", $(this).is(":selected"));
        $.data(li, "target-option", this);
        ul.append(li);
      });
      //Remove anything that is in the list
      this.$optionsList.children().remove();
      //Add the options we just created
      this.$optionsList.append(ul);

      this._updateIndicator();
    },


    /**
     * Filter out options that don't match the filter input
     *
     * @private
     */
    _applyFilter: function() {
      let filterText = this.$filterInput.val();
      if (this.lastFilter != filterText) {
        this.lastFilter = filterText;
        if(filterText == "") {
          this.$optionsList.find("li").removeClass("jqms-option-hidden");
        } else {
          this.$optionsList.find("li").filter(function () {
            $(this).toggleClass("jqms-option-hidden", !($(this).text().toLowerCase().indexOf(filterText) > -1));
          });
        }
        console.log("Filter: ", filterText);
      }
    },

    _updateIndicator: function() {
      //Set the placeholder text
      let $opts = $(this.element).find("option:selected");
      let count = $opts.length;
      let names = $opts.map(function() {
        return $(this).text();
      }).get().join(', ');
      if(count) {
        this.$selectionIndicator.text(this.settings.selectionText.replace("#count#", count).replace("#names#", names));
      } else {
        this.$selectionIndicator.text("" + this.settings.noSelectionText);
      }
    },


    /**
     * Resets the filter options by showing all elements and setting
     * the input's value to an empty string
     *
     * @private
     */
    resetFilter: function(){
      this.$filterInput.val("");
      this.$frame.find("li").removeClass("jqms-option-hidden");
    },


    /**
     * Unmake the widget
     */
    destroy: function() {
      console.error("Destroy: Not yet implemented");
      //todo: Remove the option group
    },


    /**
     * Rebuild all of the widget's pieces by destroying and
     * then re-initializing it.
     */
    rebuild: function() {
      let e = $(this.element);
      let settings = this.settings;
      this.destroy();
      e[pluginName](settings);
    },


    /**
     * Refresh the widget
     */
    refresh: function() {
      console.log("Todo: Refresh may need to be fleshed out so that it does more than just rebuild the options list");
      this._buildOptions();
      //Make sure we are still multiple.
      this.isMultiple = $(this.element).prop('multiple');
      this.resetFilter()
    },


    /**
     * Attempt to take an action, but with an opject passed in
     * rather than an action string
     */
    attemptAction: function(options) {console.error("Attempt Action: Not yet implemented");},


    /**
     * Updates the selected status (and classes) of the options
     * based on the selected status of the options within the original
     * select element
     */
    updateSelected: function() {
      console.log("Update Selected");
      let $target = $(this.element);
      //Mark various options with the selected class
      this.$optionsList.find(".jquery-modal-select-option").each(function() {
        $(this).toggleClass("selected", $target.find('option[value="' + $(this).data("value") + '"]').is(":selected"));
      });

      //update the text of the placeholder object
      this._updateIndicator();
    },


    /**
     * Marks a single option as selected
     */
    selectOption: function(e) {
      let opt = $(this.element).find('option[value="' + $(e).data("value") + '"]');
      opt.prop('selected', !opt.prop('selected'));
      this.updateSelected();
    },


    /**
     * Show the modal select window
     */
    showModalSelect: function() {
      //Make sure that all other option lists are hidden
      this.$frame.find(".jquery-modal-select-options-group").hide();
      this.$frame.find(".jquery-modal-select-modal").toggleClass("multiple", this.isMultiple);

      //make sure the correct set of options is displayed
      this.$optionsList.show();

      //Make sure the filter is clear
      this.resetFilter();


      //Show the frame
      $("#jquery-modal-select-frame").show();
      setTimeout(() => {$("#jquery-modal-select-frame").addClass("open");}, 10);
    },


    /**
     * Hide the Modal Select window
     */
    hideModalSelect() {
      $("#jquery-modal-select-frame").removeClass("open");
      setTimeout(() => {$("#jquery-modal-select-frame,#jquery-modal-select-frame .jquery-modal-select-options-group").hide();}, this.settings.transitionDelay);
    },
  });

  // A really lightweight plugin wrapper around the constructor,
  // preventing against multiple instantiations
  $.fn[ pluginName ] = function( options ) {
    return this.each( function() {
      if ( !$.data( this, "plugin_" + pluginName ) ) {
        //Make sure that this.element is a select input, because the rest of the code assumes that.
        if(!$(this).is("select")) {
          console.error("ModalSelect only works on select inputs");
          return false;
        }
        //Create an instance of the widget
        $.data( this, "plugin_" + pluginName, new ModalSelect( this, options ) );
      } else {
        //Widget already exists, so attempt to do whatever the user is asking for.
        let widget = $.data( this, "plugin_" + pluginName );
        if ( typeof options === 'string' && $.isFunction( widget[ options ] ) ) {
          widget[options]();
        } else {
          widget.attemptAction(options);
        }
      }
    } );
  };

} )( jQuery, window, document );
var app = (function ($) {

    var lat = undefined;
    var lng = undefined;

    var sort = 'alphabetical';

    var init = function() {
        setupMultiselects();
        setupFilters();
        setupToggleFilters();
        setupSortButtons();
        updateRestaurants();

    };

    var addSpinner = function(className) {
        var className = typeof className !== 'undefined' ?  className : 'restaurant-group';
        $( '.' + className ).html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
    }

    var updateRestaurants = function() {
      addSpinner();



      var url = '/api/restaurants?';
      if (sort=='distance') {
        url = url + '&lat=' + lat;
        url = url + '&lng=' + lng;
      }

      if (sort=='date') {
        url = url + '&sort=date';
      }

      if ( $("#cuisine-filter-select option:not(:selected)").length > 0 ) {
        var selected = $('#cuisine-filter-select').val();
        url = url + '&cuisine_filter=' + JSON.stringify(selected);
      }

      $.get(url, function(data, status){
          $( '.restaurant-group' ).html(data);
      });
    }

    var setupMultiselects = function()  {
        $('#cuisine-filter-select').select2({
            placeholder: 'Select cuisines'
        });
    }

    var setupFilters = function() {
        $('#cuisine-filter-select').change(function() {
            updateRestaurants();
        });
    }

    var setupToggleFilters = function() {
        $('.toggle-cuisines').click(function() {
            var selected = false;
            if ($( this ).data('toggle')=='on' ) {
                selected = true;
            }
            $('#cuisine-filter-select option').prop('selected', selected).trigger('change.select2');
            updateRestaurants();
        });
    }

    var setupSortButtons = function() {
        $('.btn-current-location').click(function() {
            addSpinner();

            $('#distance_modal').modal('hide');
            locationTools.setLocation(receiveLocationCallback);

        });

        $('.btn-sort-alphabetic').click(function() {
            addSpinner();
            sort = "alphabetic";
            updateRestaurants();
            $('.btn-sort').removeClass('active');
            $('.btn-sort-alphabetic').addClass('active');

        });

        $('.btn-sort-date').click(function() {
            addSpinner();
            sort = 'date';
            updateRestaurants();
            $('.btn-sort').removeClass('active');
            $('.btn-sort-alphabetic').addClass('active');

        });
    }

    var receiveLocationCallback = function (position) {
        lat = position.coords.latitude;
        lng = position.coords.longitude;
        sort = "distance";
        $('.btn-sort').removeClass('active');
        $('.btn-sort-distance').addClass('active');
        updateRestaurants();
    }

    return {
        init: function() {
            return init();
        },
        receiveLocationCallback: function(lat,lng) {
            return receiveLocationCallback(lat,lng);
        }
    }

})(jQuery);


jQuery(document).ready(function() {

    app.init();

});

var locationTools = (function ($) {

    var lat = undefined;
    var lng = undefined;

    var init = function() {

    };

    var setLocation = function(cb) {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(cb);
        }

    }

    var locationCallback = function(cb) {
        cb()
    }

    var getLocation = function() {
        return { lat:lat, lng:lng};
    }

    return {
        init: function() {
            return init();
        },
        getLocation: function() {
            return getLocation();
        },
        setLocation: function() {
            return setLocation();
        }
    }

})(jQuery);

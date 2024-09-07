jQuery(document).ready(function($) {
    $('#city-search').on('keyup', function() {
        var city = $(this).val();

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_cities_weather',
                city: city
            },
            success: function(response) {
                $('#cities-weather-table tbody').html(response);
            }
        });
    });
});

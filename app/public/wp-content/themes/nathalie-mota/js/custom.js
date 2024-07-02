jQuery(document).ready(function($) {
    function loadPhotos() {
        var category = $('#category-filter').val();
        var format = $('#format-filter').val();
        var order = $('#order-filter').val();
        var data = {
            action: 'filter_photos',
            category: category,
            format: format,
            order: order,
        };

        $.post(nathalie_mota_ajax.url, data, function(response) {
            $('#photo-list').html(response);
        });
    }

    $('#category-filter, #format-filter, #order-filter').change(function() {
        loadPhotos();
    });

    $('#load-more').click(function() {
        var offset = $('#photo-list .photo-item').length;
        var category = $('#category-filter').val();
        var format = $('#format-filter').val();
        var order = $('#order-filter').val();
        var data = {
            action: 'load_more_photos',
            offset: offset,
            category: category,
            format: format,
            order: order,
        };

        $.post(nathalie_mota_ajax.url, data, function(response) {
            $('#photo-list').append(response);
        });
    });

    loadPhotos();
});

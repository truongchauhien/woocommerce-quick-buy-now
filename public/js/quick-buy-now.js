jQuery(document).on('found_variation', function(event, b) {
    jQuery('.wqbn-buy-now')
        .first()
        .prop('disabled', false);
    
    return false;
});

jQuery(document).on('reset_data', function(a, b) {
    jQuery('.wqbn-buy-now')
        .first()
        .prop('disabled', true);

    return false;
});

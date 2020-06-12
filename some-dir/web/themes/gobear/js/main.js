
jQuery('.btn-more-info').on('click', function(e) {
    e.preventDefault();
    jQuery(this).parent().find('.btn-less-info').removeClass('hidden');
    jQuery(this).addClass('hidden');
    jQuery(this).parent().find('.description').removeClass('hidden');
});

jQuery('.btn-less-info').on('click', function(e) {
    e.preventDefault();
    jQuery(this).parent().find('.btn-more-info').removeClass('hidden');
    jQuery(this).addClass('hidden');
    jQuery(this).parent().find('.description').addClass('hidden');
});
$(function () {
    $('.howitworks-tab').on('click', function () {
        var tab = $(this).data('tab');
        // Switch tab button styles
        $('.howitworks-tab').removeClass('bg-primary text-white').addClass('bg-base-100 text-base-content');
        $(this).addClass('bg-primary text-white').removeClass('bg-base-100 text-base-content');
        // Show the correct panel
        $('.howitworks-panel').addClass('hidden');
        $('#tab-' + tab).removeClass('hidden');
    });
});
// Default: show first tab
$(function () {
    $('.howitworks-tab').first().trigger('click');
});

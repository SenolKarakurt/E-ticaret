$(document).ready(function () {
    var contentTitle = null;
    $('.showhide').click(function () {
        var collapse_content_selector = $(this).attr('title');
        contentTitle = $(this).html();
        var toggle_switch = $(this);
        $(collapse_content_selector).toggle(function () {
            if ($(this).css('display') == 'none') {
                contentTitle = contentTitle.replace("fa-caret-up", "fa-caret-down");
                contentTitle = contentTitle.replace("fa-minus", "fa-plus");
                toggle_switch.html(contentTitle);
            } else {
                contentTitle = contentTitle.replace("fa-caret-down", "fa-caret-up");
                contentTitle = contentTitle.replace("fa-plus", "fa-minus");
                contentTitle = contentTitle.replace("Read more...", "");
                toggle_switch.html(contentTitle);
            }
        });
    });
});

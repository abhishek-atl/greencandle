const body = $('body');
const html = $('html');

$(document).ready(function() {
    body.attr("data-theme-version", "light");
    body.attr("data-layout", "vertical");
    body.attr("data-nav-headerbg", "color_1");
    body.attr("data-headerbg", "color_1");
    body.attr("data-sidebar-style", "full");
    body.attr("data-sibebarbg", "color_1");
    body.attr("data-sidebar-position", "static");
    body.attr("data-header-position", "static");
    body.attr("data-container", "wide");
    html.attr("dir", "ltr");
    body.attr("direction", "ltr");

    const innerWidth = $(window).innerWidth();
    if (innerWidth < 1200) {
        body.attr("data-layout", "vertical");
        body.attr("data-container", "wide");
    }

    if (innerWidth > 767 && innerWidth < 1200) {
        body.attr("data-sidebar-style", "mini");
    }

    if (innerWidth < 768) {
        body.attr("data-sidebar-style", "overlay");
    }
});
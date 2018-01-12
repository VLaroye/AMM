$(document).ready(function () {
    var a = $('a.toggleEvent');

    a.click(function (e) {
        e.preventDefault();
        var target = e.currentTarget.dataset.target.toString();
        $('div[data-event = "' + target + '"]').animate({
            left: '-100%'
        });
    });
});


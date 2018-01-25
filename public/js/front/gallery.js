var $loadingAnimation = $("#loadingAnimation");
$loadingAnimation.hide();
$(document)
    .ajaxStart(function() {
        $loadingAnimation.show();
    })
    .ajaxStop(function() {
        $loadingAnimation.hide();
    });

var albumLink = $('.gallery-album a');

albumLink.click(function (e) {
    e.preventDefault();

    $('#gallery-images').empty();
    $('#gallery-images').append('<div class="galleria" style="max-width: 100%; height: 35em"></div>');

    var request = $.ajax({
        type: 'GET',
        url: e.currentTarget.href,
        dataType: 'json',
    })

        .done(function (data) {


            var images = $.parseJSON(data);

            images.forEach(function (image) {
                var imageSrc = image.images[0].source;
                $('.galleria').append(
                    '<img src="' + imageSrc + '">'
                );
            });
            Galleria.loadTheme('https://cdnjs.cloudflare.com/ajax/libs/galleria/1.5.7/themes/classic/galleria.classic.min.js');
            Galleria.run('.galleria');

        })

        .fail(function (jqXHR) {
        })

})
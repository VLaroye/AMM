var form = $('.contactForm');
var contactForm = document.getElementById('form');
var responseDiv = $('#responseData');

form.submit(function (e) {
    if (contactForm.checkValidity() === false) {
        e.preventDefault();
        e.stopPropagation();
        contactForm.classList.add('was-validated');
    } else {
        e.preventDefault();

        var request = $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize()
        })

            .done(function (data) {
                if (typeof data.message !== 'undefined') {
                    responseDiv.text(data.message)
                }
            })

            .fail(function (jqXHR) {
                console.log(jqXHR);
                if (typeof jqXHR.responseJSON !== 'undefined') {
                    var errors = jqXHR.responseJSON.errors;

                    for (var error in errors) {
                        if (errors.hasOwnProperty(error) && typeof errors[error] !== 'undefined') {
                            var fieldName;

                            switch (error) {
                                case 'data.firstName':
                                    fieldName = 'Nom';
                                    break;
                                case 'data.secondName':
                                    fieldName = 'Prenom';
                                    break;
                                case 'data.mail':
                                    fieldName = 'Email';
                                    break;
                                case 'data.subject':
                                    fieldName = 'Sujet';
                                    break;
                                case 'data.message':
                                    fieldName = 'Message';
                                    break;
                                default:
                                    fieldName = "Autre erreur";
                                    break;
                            }

                            responseDiv.append("<p><span class='font-weight-bold'>" + fieldName + "</span> : " + errors[error] + "</p>")

                        }
                    }
                }

                contactForm.classList.add('was-validated');
                responseDiv.addClass('py-2');
            })
    }
});

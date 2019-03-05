// alert( $('#inputEmail').val()+'rrr');
// alert('le jquery marche bien  assets/js/onlyloginpage.js');


var msg = $('#message').attr('class');

if( msg == 'success' ) {
    $.notify({
        icon: 'ti-face-smile ',
        message: "Vous avez bien rentré votre musique."

    }, {
        type: msg,
        timer: 40000
    })
};

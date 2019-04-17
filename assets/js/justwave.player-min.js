window.onload = function() {

    var file = document.getElementById("thefile");
    audio = document.getElementById("audio");
    var lance = document.getElementById("lance");
    var temps = document.getElementById("temps");

    var aud = document.getElementById('musicFile');

    var xhr = new XMLHttpRequest();
    xhr.open('GET', aud.value);
    xhr.responseType = 'blob';
    xhr.onload = e => toto(xhr.response);

    xhr.send();


function toto(resp){
    fileJoachim = new File([resp], 'sample.mp3', {
        lastModified: new Date(0), // optional - default = now
        type: "audio/mp3" // optional - default = ''
    });
    document.getElementById("loadedMusic").innerText = "music loaded";
}



    lance.onclick = function() {
        if (document.getElementById("loadedMusic").innerText != '') {
            document.getElementById("button").classList.add('hidden');
            document.getElementById("audioDisplay").classList.remove('hidden');
            var files = this.files;
            audio.src = URL.createObjectURL(fileJoachim);
            audio.load();
            audio.play();
            var context = new AudioContext();
            var src = context.createMediaElementSource(audio);
            var analyser = context.createAnalyser();

            var canvas = document.getElementById("canvas");
            canvas.width = document.getElementById("music-content").offsetWidth - 30;
            canvas.height = window.innerHeight / 2;
            var ctx = canvas.getContext("2d");

            src.connect(analyser);
            analyser.connect(context.destination);

            analyser.fftSize = 256;

            var bufferLength = analyser.frequencyBinCount;

            var dataArray = new Uint8Array(bufferLength);

            var WIDTH = canvas.width;
            var HEIGHT = canvas.height;

            var barWidth = (WIDTH / bufferLength) * 2.5;
            var barHeight;
            var x = 0;

            function renderFrame() {
                requestAnimationFrame(renderFrame);

                x = 0;

                analyser.getByteFrequencyData(dataArray);

                ctx.fillStyle = "#000";
                ctx.fillRect(0, 0, WIDTH, HEIGHT);

                for (var i = 0; i < bufferLength; i++) {
                    barHeight = dataArray[i];

                    var r = barHeight + (25 * (i / bufferLength));
                    var g = 250 * (i / bufferLength);
                    var b = 50;

                    ctx.fillStyle = "rgb(" + r + "," + g + "," + b + ")";
                    ctx.fillRect(x, HEIGHT - barHeight, barWidth, barHeight);

                    x += barWidth + 1;
                }
            }

            audio.play();
            renderFrame();
        }
        ;
    }

};

$( "#commentForm" ).submit(function( event ) {
    document.getElementById('music_timeMusic').value = audio.currentTime;
});


// CLIQUE POUR VOIR LES COMMENTAIRES
$( '[id^="note-"]' ).click(function() {
    $( '[id^="comm-"]' ).hide();
    var idCom = $(this).attr('id');
    idCom = idCom.replace('note-', '');
    $('#comm-'+idCom).show();
    audio.currentTime = $('#comm-'+idCom).attr('title');
});



$("#temps").click(function(e) {

    $.ajax({
        url: "/ajaxComment",
        method: 'post',
        global: true,
        data: {
            idMusic: $('#music_music').val(),
            idUser: $('#music_users').val(),
            commentaire: $('#music_comment').val(),
            time : audio.currentTime
        }
    }).done (function (response) {
        /*
        $('#returnAjax').html('');
        if( response != '' ) {
            var files = JSON.parse(response);

            var returnHtml = '';
            for (var i = 0; i < files.length; i++) {
                var libelle = files[i].libelle;
                var idJ = files[i].id;
                var toDownload = files[i].toDownload;

                if (toDownload == 1)
                    returnHtml += '<p>' + libelle + ' <input upload_text="' + libelle + '"  class="form-control-file" id="' + idJ + '-upl" type="file" name="' + idJ + '-uploadStatus" /></p>';
                else
                    returnHtml += '<p>' + libelle + ' déjà téléchargé par vos soins</p>';

            }
            $('#returnAjax').html(returnHtml);
        }

         */
    });

    return false;

});

var msg = $('#message').attr('class');

if( msg == 'success' ) {
    $.notify({
        icon: 'ti-face-smile ',
        message: "Vous avez bien rentré votre commentaire."

    }, {
        type: msg,
        timer: 40000
    })
};
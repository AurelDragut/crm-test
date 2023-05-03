/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import $ from 'jquery';
global.$ = $;
// start the Stimulus application
import 'bootstrap';
import 'bootstrap-slider';

function check(){

    var RNummer = $('#form_re_nr').val();
    var PLZ = $('#form_Postleitzahl').val();

    $.post("/anfrage/ausfuellen",
        {
            Rechnungsnummer: RNummer,
            Postleitzahl: PLZ,
        },
        function(data, status){
            if (status === 'success') {
                if (data === 'null') {
                    alert('Keine Bestellung gefunden!');
                }
            }
        });
    $.get("/anfrage/neu",
        {
            Rechnungsnummer: RNummer,
            Postleitzahl: PLZ,
        },
        function( data ) {
            $( "#kunden" ).html( '<div class="extraArticle">'+data+'</div>' );
        });
}

document.addEventListener("DOMContentLoaded", function(){
    var errors = $('#error-messages');
    if (errors.children().length > 0) {
        check();
    }
});

$("#form_Pruefen").click(function () {
    check();
});

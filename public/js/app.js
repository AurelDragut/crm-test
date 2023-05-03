val =  $('.price-slider').slider('getValue');

$('.price-slider').on('slide', function (ev) {
    $('#minPrice').val(ev.value[0]);
    $('#maxPrice').val(ev.value[1]);
});

function toggleChevron(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".fa")
        .toggleClass('fa-minus fa-plus');
}

$('#accordion').on('shown.bs.collapse', toggleChevron);
$('#accordion').on('hidden.bs.collapse', toggleChevron);

var videoWidth = $('iframe').width();
videoHeight = videoWidth * .61;
$('iframe').css('height', videoHeight);

var carBoxHeight = $('.car-pic-m').width();
$('.car-pic-m img').css('width', carBoxHeight);
$('.car-pic-m img').css('height', carBoxHeight);
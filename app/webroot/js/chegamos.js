var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-19798490-1']);
_gaq.push(['_setDomainName', 'none']);
_gaq.push(['_setAllowLinker', true]);
_gaq.push(['_trackPageview']);

(function() {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
})();

var rootUrl = $("#rootUrl").val();

var updateOnTimeout = function() {
    var timeout = 1000 * 60 * 10;
    var lastUpdate = $.cookie('lastLocationUpdate');
    var currentTime = new Date();
    var now = currentTime.getTime();

    if(lastUpdate === null || now > lastUpdate*1 + timeout*1) {
        $.cookie('lastLocationUpdate', now, {
            'path' : '/'
        });
        getUserLocation();
    }
}

var intervalo = window.setInterval(function() {
    if($.cookie('disableAutoDetect') === null) {
        updateOnTimeout();
    }
}, 10000);

var updateLocation = function(lat, lng) {
    $.get(rootUrl + 'profile/location', {
        'lat': lat, 
        'lng': lng, 
        'type' : 'json'
    },
    function(data) {
        if (data.success === true) {
            var addressData = [
            data.checkinData.street,
            data.checkinData.district,
            data.checkinData.city,
            data.checkinData.state 
            ];

            $('#ondeEstou').fadeOut();
            $('#ondeEstou span').html(addressData.filter(String).join(', '));
            $('#ondeEstou').fadeIn();
        }
    }
    );
}

getUserLocation = function() {
    navigator.geolocation.getCurrentPosition(
        function(position){
            lat=position.coords.latitude;
            lng=position.coords.longitude;
            updateLocation(lat,lng);
        }
        );
}

$('#autoDetectContainer').show();
$('.autoDetect').change(function() {
    if ($("input[name='autoDetect']:checked").val() == 'off') {
        $.cookie('disableAutoDetect', true, {
            'path' : '/'
        });
    } else {
        $.cookie('disableAutoDetect', null, {
            'path' : '/'
        });
        getUserLocation();
    }
});

$.mobile.page.prototype.options.backBtnText = "Voltar";

$.extend($.mobile, {
    loadingMessage: "Carregando"
});


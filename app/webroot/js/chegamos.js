var _gaq = _gaq || [];
_gaq.push([ '_setAccount', 'UA-19798490-1' ]);
_gaq.push([ '_setDomainName', 'none' ]);
_gaq.push([ '_setAllowLinker', true ]);
_gaq.push([ '_trackPageview' ]);
_gaq.push([ '_trackPageLoadTime' ]);

var rootUrl = $("#rootUrl").val();

var updateOnTimeout = function() {
	var timeout = 1000 * 60 * 10;
	var lastUpdate = $.cookie('lastLocationUpdate');
	var currentTime = new Date();
	var now = currentTime.getTime();

	if (lastUpdate === null || now > lastUpdate * 1 + timeout * 1) {
		$.cookie('lastLocationUpdate', now, {
			'path' : '/'
		});
		getUserLocation();
	}
};

var intervalo = window.setInterval(function() {
	if ($.cookie('disableAutoDetect') === null) {
		updateOnTimeout();
	}
}, 10000);

var updateLocation = function(lat, lng) {
	$.get(rootUrl + 'location/update', {
		'lat' : lat,
		'lng' : lng
	}, function(data) {
		if (data.success === true) {
			var addressData = [ 
                    data.location.address.street,
					data.location.address.district,
					data.location.address.city.name,
					data.location.address.city.state 
			];

			$('#ondeEstou').fadeOut();
			$('#ondeEstou span').html(addressData.filter(String).join(', '));
			$('#ondeEstou').fadeIn();
		}
	});
};

getUserLocation = function() {
	navigator.geolocation.getCurrentPosition(function(position) {
		lat = position.coords.latitude;
		lng = position.coords.longitude;
		updateLocation(lat, lng);
	});
};

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

$(document).bind("mobileinit", function(){
	$.extend($.mobile, {
		loadingMessage : "Carregando",
		pageLoadErrorMessage : "Erro ao carregar a página"
	});
});
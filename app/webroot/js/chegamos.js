var _gaq = _gaq || [];
_gaq.push([ '_setAccount', 'UA-19798490-1' ]);
_gaq.push([ '_setDomainName', 'none' ]);
_gaq.push([ '_setAllowLinker', true ]);
_gaq.push([ '_trackPageview' ]);
_gaq.push([ '_trackPageLoadTime' ]);

var rootUrl = $("#rootUrl").val();

var intervalo = window.setInterval(function() {
	if ($.cookie('disableAutoDetect') === null) {
		updateOnTimeout();
	}
}, 10000);

var updateOnTimeout = function() {
	var timeout = 1000 * 60 * 10;
	var lastUpdate = $.cookie('lastLocationUpdate');
	var currentTime = new Date();
	var now = currentTime.getTime();

	if (lastUpdate === null || now > lastUpdate * 1 + timeout * 1) {
		$.cookie('lastLocationUpdate', now, {
			'path' : '/'
		});
		locationService.detect();
		locationService.save();
		locationService.updateLabel();
	}
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
		locationService.detect();
		locationService.save();
		locationService.updateLabel();
	}
});


$(document).ready(function() {
	locationService = new LocationService();
	locationService.setSaveUrl(rootUrl + 'location/update');
	locationService.setLoadUrl(rootUrl + 'location/current');
	locationService.setLabel($("#ondeEstou"));

	locationService.load();
	locationService.updateLabel();	
	
	$.extend($.mobile, {
		loadingMessage : "Carregando",
		pageLoadErrorMessage : "Erro ao carregar a página"
	});
});
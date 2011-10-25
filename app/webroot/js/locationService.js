LocationService = function() {

	var saveUrl = 'location/update';
	var loadUrl = 'location/current';
	var label = null;
	var location = new Location();
	
	this.setSaveUrl = function(saveUrl) {
		this.saveUrl = saveUrl;
	};

	this.setLoadUrl = function(loadUrl) {
		this.loadUrl = loadUrl;
	};
	
	this.setLabel = function(label) {
		this.label = label;
	};
	
	this.getPoint = function() {
		return location.point;
	};

	this.getAddress = function() {
		return location.address;
	};
	
	this.getLocation = function() {
		return location;
	};

	this.detect = function() {
		navigator.geolocation.getCurrentPosition(this.setPointFromDetect);
	};
	
	this.setPointFromDetect = function(position) {
		location.point.lat = position.coords.latitude;
		location.point.lng = position.coords.longitude;
	};
	
	this.save = function() {
		$.get(saveUrl, location.point, this.setAddressFromJson);
	};
	
	this.load = function() {
		$.get(loadUrl, {}, this.setAddressFromJson);
	};

	this.setAddressFromJson = function(json) {
		if (json.success === true) {
			location.address = [ json.location.address.street,
					json.location.address.district,
					json.location.address.city.name,
					json.location.address.city.state ];
		}
	};
	
	this.updateLabel = function() {
		if (this.label !== undefined && location.address !== undefined) {
			var labelHtml = '<span class="ui-btn-inner ui-btn-corner-all">' + location.address.filter(String).join(', ') + '</span>';

			$(label).fadeOut();
			$(label).html(labelHtml);
			$(label).fadeIn();
		}
	};
};
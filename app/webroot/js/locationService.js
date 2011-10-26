function LocationService() {
	this.saveUrl = 'location/update';
	this.loadUrl = 'location/current';
	this.label = null;
	this.location = new Location();
}	

LocationService.prototype.detect = function() {
	navigator.geolocation.getCurrentPosition($.proxy(this.setPointFromDetect, this));
	this.save();
};

LocationService.prototype.setPointFromDetect = function(position) {
	this.location.point = new Point(
		position.coords.latitude,
		position.coords.longitude
	);
};

LocationService.prototype.save = function() {
	$.get(this.saveUrl, this.location.point, $.proxy(this.setAddressFromJson, this));
};

LocationService.prototype.load = function() {
	$.get(this.loadUrl, {}, $.proxy(this.setAddressFromJson, this));
};

LocationService.prototype.setAddressFromJson = function(json) {
	if (json.success === true) {
		this.location.address = [ json.location.address.street,
				json.location.address.district,
				json.location.address.city.name,
				json.location.address.city.state ];
	}
	this.updateLabel();
};

LocationService.prototype.updateLabel = function() {
	if (this.label !== undefined && this.location.address !== undefined) {
		var labelHtml = '<span class="ui-btn-inner ui-btn-corner-all">' + this.location.address.filter(String).join(', ') + '</span>';

		$(this.label).fadeOut();
		$(this.label).html(labelHtml);
		$(this.label).fadeIn();
	}
};
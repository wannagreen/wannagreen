// Génération de la map sur la page groupe détails
var iconBlue = new GIcon();
iconBlue.image = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
iconBlue.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
iconBlue.iconSize = new GSize(12, 20);
iconBlue.shadowSize = new GSize(22, 20);
iconBlue.iconAnchor = new GPoint(6, 20);
iconBlue.infoWindowAnchor = new GPoint(5, 1);

var iconRed = new GIcon();
iconRed.image = 'http://labs.google.com/ridefinder/images/mm_20_red.png';
iconRed.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
iconRed.iconSize = new GSize(12, 20);
iconRed.shadowSize = new GSize(22, 20);
iconRed.iconAnchor = new GPoint(6, 20);
iconRed.infoWindowAnchor = new GPoint(5, 1);

var customIcons = [];
customIcons["groupe1"] = iconBlue;
customIcons["groupe2"] = iconRed;

function load_map() {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
		map.setCenter(new GLatLng(46.9, 2.3), 6);
		
		var urltab = location.href.split('/'),
			id_groupe = urltab[urltab.length-1];
		GDownloadUrl('http://localhost/wannagreen/groupe/get_xml_map/'+id_groupe, function(data) {
			var xml = GXml.parse(data);
			var utilisateur = xml.documentElement.getElementsByTagName("utilisateur");
			for (var i = 0; i < utilisateur.length; i++) {
				var email = utilisateur[i].getAttribute("email");
				var avatar = utilisateur[i].getAttribute("avatar");
				var nom = utilisateur[i].getAttribute("nom");
				var prenom = utilisateur[i].getAttribute("prenom");
				var adresse = utilisateur[i].getAttribute("adresse");
				//var type = utilisateur[i].getAttribute("type");
				var point = new GLatLng(parseFloat(utilisateur[i].getAttribute("latitude")),
				parseFloat(utilisateur[i].getAttribute("longitude")));
				var marker = createMarker(point, nom, prenom, adresse);
				map.addOverlay(marker);
			}
		});
	}
}

function createMarker(point, nom, prenom, adresse) {
	//var marker = new GMarker(point, customIcons[type]);
	var marker = new GMarker(point, iconBlue);
	var html = "<b>" + nom + " " + prenom + "</b> <br/>" + adresse;
	GEvent.addListener(marker, 'click', function() {
		marker.openInfoWindowHtml(html);
	});
	return marker;
}

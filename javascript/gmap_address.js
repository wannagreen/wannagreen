google.load("maps", "2.x",{"other_params":"sensor=false"});
var adresse;

function showLocation(address){
	adresse = address;
	var monGeocodeur = new google.maps.ClientGeocoder();
	monGeocodeur.getLocations(adresse, rechercheAdresse);
}

function rechercheAdresse(reponse){
	document.getElementById("autoCompletionResult").style.border = "thin solid #DDD";
	// Div qui recevra les résultat
	var resultDiv = document.getElementById("autoCompletionResult");	
	resultDiv.innerHTML = "";

	var unorderedList = document.createElement("ul");
	resultDiv.appendChild(unorderedList);

	if (!reponse || reponse.Status.code != 200) {
		// alert("Désolé, Il nous est impossible de géocoder votre adresse : \r\n\nCode Erreur : " + reponse.Status.code + '\r\n\nRetrouvez la signification de ce code ?ette adresse :\r\n\nhttp://www.google.com/apis/maps/documentation/reference.html#GGeoStatusCode');				
		// Création d'un élément de la liste
		var listItem = document.createElement("li");
		// Récupération de la valeur de l'élément
		var value  = document.createTextNode("Aucune adresse trouvée");

		// Ajout de l'élément à la liste
		listItem.appendChild(value);
		listItem.setAttribute("onclick","fillField(this);");
		unorderedList.appendChild(listItem);

	}else{
		var nombreReponse = reponse.Placemark.length;
		for(a=0; a<nombreReponse ;a++){
			place = reponse.Placemark[a];
			var Gstatusrequete = reponse.Status.code;
			var Gprecision = place.AddressDetails.Accuracy;
			var adresserequete = adresse;
			var GadresseFormatee = place.address;
			var Galtitude = place.Point.coordinates[2];
			var Glatitude = place.Point.coordinates[1];
			var Glongitude = place.Point.coordinates[0];
			
			// Création d'un élément de la liste
			var listItem = document.createElement("li");
			// Récupération de la valeur de l'élément
			var value  = document.createTextNode(GadresseFormatee);

			// Ajout de l'élément à la liste
			listItem.appendChild(value);
			listItem.setAttribute("onclick","fillField(this);");
			unorderedList.appendChild(listItem);

		}
	}

}

function fillField(listItem){
	document.getElementById("adresse").value = listItem.innerHTML;
	document.getElementById("autoCompletionResult").innerHTML = "";
	document.getElementById("autoCompletionResult").style.border = "none";	
}

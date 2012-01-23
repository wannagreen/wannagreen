$(function() {
	
	// Onglets pour le bloc de gauche
	//$('div#tabs').tabs({ cookie: {} });
	
	// Mise en forme des bouttons avec jquery-ui
	$('input.button, a.button').button();
	
	$('div#conteneur').delegate('a._blank', 'click', function() {
		window.open($(this).attr('href'), '_blank');
		return false;
	});
	
	// Gestion de la pop-in (twitter)
	$('a.twitter').click(function() {
		$.modal('<div id="tweetbox"></div>', {
			closeClass: 'close',
			escClose: true,
			overlayClose: true
		});
		var defcontent = '';
		if($(this).attr('id') == 'twitter_decouvrir') {
			defcontent = 'Je viens de découvrir une plateforme dédiée aux projets verts et solidaires !! http://wannagreen.com/';
			twttr.anywhere(function(twitter) {  
				twitter("#tweetbox").tweetBox({
					label: 'Parlez de Wannagreen sur Twitter',
					defaultContent: defcontent
				});
			});
		}
		else {
			defcontent = 'J\'ai trouvé un lien intéressant sur wannagreen.com : ';
			var url = escape($(this).parents('div.publication').find('a:first-child').attr('href')),
				short_url = '';
			$.getJSON('http://api.bitly.com/v3/shorten?login=julienp&apiKey=R_ab32dfb4adfcae3b7bcffc0962791467&format=json&longUrl='+url, function(result) {
				short_url = result.data['url'];
				defcontent += short_url;
				
				twttr.anywhere(function(twitter) {  
					twitter("#tweetbox").tweetBox({
						label: 'Tweeter un lien Wannagreen',
						defaultContent: defcontent
					});
				});
			});
		}
		return false;
	});
	
	// Fonctions twitter linkify et hovercards uniquement sur la page de profil utilisateur
	if(location.href.indexOf('utilisateur/profil/') != -1) {
		// Twitter linkify & hovercards
		if($('p.twitter').length > 0) {
			twttr.anywhere(function(twitter) {
				twitter('p.twitter').linkifyUsers();
				twitter.hovercards();
			});
		}
	}
	
	// Gestion des pop-in (modal)
	$('a.openmodal').click(function() {
		$.get($(this).attr('href'), function(content) {
			$.modal(content, {
				closeClass: 'close',
				escClose: true,
				overlayClose: true
			});
		});
		return false;
	});
	
	if(location.href.indexOf('groupe/details') != -1) {
		$('#map').load(function() {
			load_map();
		}).load();
	}
	
	// Permet de masquer et d'afficher la div de connexion delicious, masquée par défaut
	$('#delicious_connexion,#ajout_tag,div.ajout_tag').hide();
	$('#toggle_delicious_connexion,#toggle_ajout_tag,a.toggle_ajout_tag').click(function() {
		if($(this).hasClass('toggle_ajout_tag'))
			$(this).parents('p').siblings('div.ajout_tag').slideToggle('fast');
		else
			$('#delicious_connexion,#ajout_tag').slideToggle('fast');
		return false;
	});
	
	// Ajoute les tags cliqués dans la textbox
	var input = $('input#tags');
	$('div#tags_suggeres').delegate('span.tags-add', 'click', function(event) {
		event.preventDefault();
		if(input.val().indexOf($(this).html()) == -1) {
			if(input.val() == '')
				input.val($(this).html());
			else
				input.val(input.val() + ' ' + $(this).html());
		}
	});
	
	$('#bouton_suggerer_tags').click(function() {
		var url = $('input#url');
		if(url.val() != '') {
			$.ajax({
				url: BASE_URL + 'lien/suggerer_tags/',
				type: 'POST',
				data: 'url=' + url.val(),

				success: function(result) {
					$('div#tags_suggeres').html(result);
				},
				error: function() {
					$('div#tags_suggeres').html('<div class="error">Erreur lors de la suggestion</div>');
				}
			});
		}
	});
	
	// Code dédié à la page groupe_details
	if(location.href.indexOf('groupe/details/') != -1) {
		
		$('input#valider_tags').click(function() {
			if($('input#tags').val() != '') {
				$.ajax({
					url: $(this).parents('form').attr('action'),
					type: 'POST',
					data: 'tags=' + $('input#tags').val(),
					
					success: function(result) {
						$('div#tags_result').html(result);
						$('input#tags').val('');
					},
					error: function() {
						$('div#tags_result').html('<div class="error">Erreur lors de la requête</div>');
					}
				});
			}
			return false;
		});
		
		$('input.valider_tags_pub').click(function() {
			var form = $(this).parents('form'),
				input = form.find('input.tags_publication');
			if(input.val() != '') {
				$.ajax({
					url: form.attr('action'),
					type: 'POST',
					data: 'tags=' + input.val(),
					
					success: function(result) {
						$('div.tags_result', form).html(result);
						input.val('');
					},
					error: function() {
						$('div.tags_result', form).html('<p class="error">Erreur lors de la requête</p>');
					}
				});
			}
			return false;
		});
		
		$('span.tag_link img').click(function() {
			var img = $(this),
				rel = img.attr('rel');
			if($(this).attr('rel') != undefined && $(this).attr('rel') != '') {
				var id_groupe = location.href.split('details/')[1];
				$.ajax({
					url: BASE_URL + 'groupe/supprimer_tag/' + id_groupe.split('/')[0],
					type: 'POST',
					data: 'id_tag=' + $(this).attr('rel'),
					
					success: function(result) {
						// $('div#tags_result').html(result);
						img.parents('span').fadeOut('slow', function() {
							$(this).remove();
						});
					},
					error: function() {
						$('div#tags_result').html('<div class="error">Erreur lors de la requête</div>');
					}
				});
			}
			return false;
		});
		
		$('input.valider_comm').click(function() {
			var form = $(this).parents('form'),
				input = form.find('textarea.commentaire_text');
			if(input.val() != '') {
				$.ajax({
					url: form.attr('action'),
					type: 'POST',
					dataType: 'JSON',
					data: 'commentaire=' + input.val(),
					
					success: function(result) {
						var comm = '<div class="commentaire"><p>' + result.contenu + '</p><p class="date">Le ' + result.date_creation + ' par <a href="' + BASE_URL + 'utilisateur/profil/' + result.id_utilisateur + '">' + result.prenom + ' ' + result.nom + '</a></p></div>';
						form.parents('div.publication').find('div.liste_comm').append(comm);
						input.val('');
					},
					error: function() {
						form.find('div#comm_result').html('<div class="error">Erreur lors de la requête</div>');
					}
				});
			}
			return false;
		});
		
		// Edition de la description d'un groupe
		$('a#clic_modif_description').click(function() {
			if($('textarea#txt_description').length == 0) {
				var actuelle = $('p#groupe_description').html(),
					id_groupe = $('input#id_groupe_modif').val();
				$('p#groupe_description').html('<textarea rows="4" cols="105" id="txt_description">' + actuelle + '</textarea><br /><br /><a class="button" id="valid_modif_description" href="' + BASE_URL + 'groupe/modifier_description_groupe/' + id_groupe + '">Valider</a>');
				$('a.button').button();
			}
			return false;
		});
		
		$('p#groupe_description').delegate('a#valid_modif_description', 'click', function() {
			var valid_button = $(this),
				new_description = $('textarea#txt_description').val();
			if(new_description != '') {
				$.ajax({
					url: valid_button.attr('href'),
					type: 'POST',
					data: 'new_description=' + new_description,
					
					success: function(result) {
						$('p#groupe_description').html(new_description);
					},
					error: function() {
						$('p#groupe_description').html('<div class="error">Erreur lors de la requête</div>');
					}
				});
			}
			return false;
		});
		
	}
	
});

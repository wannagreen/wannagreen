<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<h1>Accueil</h1>
	</div>
	<div id="accueil" class="block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
		<h1>Bienvenue sur la plateforme la plus verte et solidaire du monde !</h1>
		<p>Essayez de taper quelque chose ci dessous pour voir les tweets correspondants</p>
		<form method="post" action="<?= base_url() ?>accueil/twitter_search">
			<input type="text" name="twttr_search" />
			<input type="submit" name="submit" value="Afficher les tweets" class="button" />
		</form>
		<h2>Tweets correspondants au mot clé &laquo; <strong><?= $search_word ?></strong> &raquo;</h2>
		<div id="twitter_search">
			<?php
			if($twttr_feed === FALSE):
				echo '<div class="info">Wannagreen semble rencontrer des problèmes pour récupérer les tweets...</div>';
			elseif(empty($twttr_feed->entry)):
				echo '<div class="info">Il n\'y a aucun tweet correspondant à ce mot clé</div>';
			else:
				foreach($twttr_feed->entry as $entry):
					$link_attr = $entry->link[1]->attributes();
					str_replace($search_word, '<strong>'.$search_word.'</strong>', $entry->content);
					date_default_timezone_set('Europe/Paris'); ?>
					<div class="tweet">
						<div class="tweet_image"><img src="<?= $link_attr['href'] ?>" alt="Twitter Pic" /></div>
						<div class="tweet_userdate">
							<div class="tweet_username"><a href="<?= $entry->author->uri ?>"><?= $entry->author->name ?></a></div>
							<div class="tweet_date"><?= date('d/m/Y à H:i:s', strtotime($entry->updated)) ?></div>
						</div>
						<div class="clear"></div>
						<div class="tweet_content"><?= $entry->content ?></div>
					</div><?php
				endforeach;
			endif;
			?>
		</div>
	</div>
</div>

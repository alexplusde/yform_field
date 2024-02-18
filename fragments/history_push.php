<?php

/** @var rex_fragment $this */

$nonce = $this->getVar('nonce') ?? '';
$target_url = $this->getVar('target_url') ?? '';
$target_title = $this->getVar('target_title') ?? '(ﾉ◕ヮ◕)ﾉ*:･ﾟ✧';
?>

<script type="text/javascript" nonce="<?= $nonce ?>">
	if (window.history && window.history.pushState) {
		// Fügen Sie einen neuen Eintrag zum Browserverlauf hinzu
		window.history.pushState({
				page: '<?= $target_title ?>'
			}, '<?= $target_title ?>',
			'<?= $target_url ?>');

		// Hören Sie auf das 'popstate' Ereignis, das ausgelöst wird, wenn der Benutzer auf "Zurück" klickt
		window.onpopstate = function(event) {
			if (event.state && event.state.page === '<?= $target_title ?>') {
				// Leiten Sie den Benutzer zur neuen Seite um
				window.location.href = '<?= $target_url ?>';
			}
		};
	} else {
		// Die history.pushState Methode ist nicht verfügbar
		// Führen Sie eine alternative Aktion durch, z.B. den Benutzer auf eine andere Seite umleiten
		window.location.href = '<?= $target_url ?>';
	}
</script>

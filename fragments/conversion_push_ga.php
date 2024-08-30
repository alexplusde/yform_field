<?php

/** @var rex_fragment_var $this */
$event = $this->getVar('event');
$send_to = $this->getVar('send_to');
$value = $this->getVar('value');
$currency = $this->getVar('currency');

?>
<script>
	window.addEventListener('gtagLoaded', function(event) {

		gtag('event', '<?= $event ?>', {
			'send_to': '<?= $send_to ?>',
			'value': <?= $value ?>,
			'currency': '<?= $currency ?>'
		});

	});
</script>

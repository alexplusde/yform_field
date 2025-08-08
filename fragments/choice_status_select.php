<?php
$data_id = $this->getVar('data_id');
$table = $this->getVar('table');
$field = $this->getVar('field');
$options = $this->getVar('options');
$selected = $this->getVar('selected');
$token = $this->getVar('token');
?>
<select data-table="<?= $table ?>"
	data-token="<?= $token ?>"
	data-field="<?= $field ?>"
	data-id="<?= $data_id ?>" class="form-control dropdown-toggle"
	data-status="choice_status_select" style="width: auto;">
	<?php foreach ($options as $value => $option) {
        ?>
	<option value="<?= $value ?>" <?php if ($value == $selected) {
        echo 'selected';
    } ?>><?= $option ?></option>
	<?php
    } // foreach $options
?>
</select>

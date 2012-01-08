<table border="1" cellspacing="0" cellpadding="5">
	<tr>
		<th>file</th>
		<th>crawling result</th>
		<th>hash master</th>
	</tr>

	<!-- ############################################## -->
	<!-- #### diferences between result and master #### -->
	<!-- ############################################## -->
	<?php foreach($this->hashes as $path => $hash): ?>
		<?php
			$bgc = '#99FF99';
			if(	(isset($master[$path]) && $hash != $master[$path])
			    || (!isset($master[$path])) ) {

				$bgc = '#FF0000';
				$valid = FALSE;
			}
		?>
		<tr>
			<td style="background-color: <?= $bgc ?>;"><?= $path ?></td>
			<td style="background-color: <?= $bgc ?>;"><?= $hash ?></td>
			<td style="background-color: <?= $bgc ?>;"><?= @$master[$path] ?></td>
		</tr>

		<?php unset($master[$path]); ?>
	<?php endforeach; ?>
	

	<!-- ############################################## -->
	<!-- #### diferences between master and result #### -->
	<!-- ############################################## -->
	<?php foreach($master as $path => $hash): ?>
		<?php $bgc = '#FF0000'; ?>
		<?php $valid = FALSE;   ?>
		<tr>
			<td style="background-color: <?= $bgc ?>;"><?= $path ?></td>
			<td style="background-color: <?= $bgc ?>;"></td>
			<td style="background-color: <?= $bgc ?>;"><?= $hash ?></td>
		</tr>
	<?php endforeach; ?>

</table>

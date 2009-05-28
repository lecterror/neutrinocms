<div class="userPermissions index">
	<h2>
		<?php __('Users'); ?>
	</h2>
	<?php foreach ($users as $user): ?>
		<div>
			<?php
			$linkText = sprintf('%s (%s %s)', $user['User']['username'], $user['User']['first_name'], $user['User']['last_name']);
			echo $html->link($linkText, array('action' => 'view', $user['User']['id']));
			?>
		</div>
	<?php endforeach; ?>
</div>
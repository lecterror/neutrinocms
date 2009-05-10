<div>
	<?php if (isset($success) && $success === true): ?>
		<h2><?php __('Congratulations!'); ?></h2>
		<p><?php __('Your account has been activated successfully!'); ?></p>
		<p>
			<?php
			echo $html->link
				(
					__('Go to login form', true),
					array
					(
						'controller' => 'users',
						'action' => 'login'
					)
				);
			?>
		</p>
	<?php else: ?>
		<h1><?php __('Oops!'); ?></h1>
		<p><?php __('There was an error while activating your account!'); ?></p>
		<p><?php echo $html->link(__('Back to home page', true), '/'); ?></p>
	<?php endif; ?>
</div>
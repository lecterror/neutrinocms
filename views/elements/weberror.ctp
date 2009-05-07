<?php echo $html->div('post'); ?>
	<h2><?php __('Whooops'); ?></h2>
	<small>&nbsp;</small>
	<?php echo $html->div('entry'); ?>
		<div class="error-image-container">
			<?php echo $html->image('laugh.png'); ?>
		</div>
		<p>
		<?php __('Something just broke down, and it\'s not scheduled to that right now.'); ?>
		</p>
		<p>
			<?php
			echo sprintf
				(
				__('If you think this might be a temporary problem, go back where you came from and try again. Otherwise, just go to %s	and don\'t try this funny business again.', true),
				$html->link(__('home page', true), '/')
				);
			?>
		</p>
	</div>
</div>
<div>&nbsp;</div>
<?php echo $html->div('post'); ?>
	<h2>Whooops</h2>
	<small>&nbsp;</small>
	<?php echo $html->div('entry'); ?>
		<div class="error-image-container">
			<?php echo $html->image('laugh.png'); ?>
		</div>
		<p>
		Something just broke down, and it's not scheduled to that right now.
		</p>
		<p>
			If you think this might be a temporary problem, go back where you came from
			and try again. Otherwise, just go to <?php echo $html->link('home page', '/'); ?>
			and don't try this funny business again.
		</p>
	</div>
</div>
<div>&nbsp;</div>
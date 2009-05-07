<?php $this->pageTitle = __('Search', true); ?>
<?php echo $html->div('post'); ?>
	<?php echo $html->div('entry', null, array('style' => 'text-align:center; background-color:#efef;')); ?>
		<div style="width:177px; margin:auto; margin-top:100px; text-align:left;">
		<?php echo $this->element('searchbox'); ?>
		</div>
	</div>
</div>
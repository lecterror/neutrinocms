<h1>Search</h1>
<?php
echo $form->create(null, array('url' => array('controller' => 'search', 'action' => 'results'), 'class' => 'searchform', 'id' => 'SearchForm'));
	echo '<div class="search-wrap">';
	echo $form->input('Search.phrase', array('label' => false, 'class' => 'textbox', 'div' => false));
	?>&nbsp;<?php
	echo $form->submit('Search', array('class' => 'button', 'div' => false));
	echo '</div>';
echo $form->end();
?>

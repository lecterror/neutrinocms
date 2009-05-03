<?php

class AuthHelper extends AppHelper
{
	var $helpers = array('Session');

	function valid()
	{
		if ($this->Session->read('Auth.User'))
			return true;

		return false;
	}
}

?>
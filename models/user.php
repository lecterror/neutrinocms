<?php
/*
	This file is part of NeutrinoCMS.

	NeutrinoCMS is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	NeutrinoCMS is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with NeutrinoCMS.  If not, see <http://www.gnu.org/licenses/>.
*/

class User extends AppModel
{
	var $name = 'User';

	var $validate = array();

	function __construct()
	{
		parent::__construct();

		$this->validate = array
			(
				'username'	=>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('Enter a valid username', true)
					),
				'password'	=>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('Enter a valid password', true)
					),
				'email'	=>
					array
					(
						'rule'		=> VALID_EMAIL,
						'message'	=> __('Please enter a valid email address', true)
					),
				'first_name'	=>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('Enter your first name', true)
					),
				'last_name'	=>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('Enter your last name', true)
					)
			);
	}
}

?>
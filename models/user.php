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

	var $validate =
		array
		(
			'username'	=>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> 'Enter a valid username'
				),
			'password'	=>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> 'Enter a valid password'
				),
			'email'	=>
				array
				(
					'rule'		=> VALID_EMAIL,
					'message'	=> 'Please enter a valid email address'
				),
			'first_name'	=>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> 'Enter your first name'
				),
			'last_name'	=>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> 'Enter your last name'
				)
		);
}

?>
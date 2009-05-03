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

class Comment extends AppModel
{
	var $name = "Comment";

	var $belongsTo =
		array
		(
			'Article' =>
				array
				(
					'className'  => 'Article',
					'conditions' => '',
					'order'      => '',
					'foreignKey' => 'article_id'
				)
		);

	var $validate = array
		(
			'article_id'	=> VALID_NOT_EMPTY,
			'name' =>
				array
				(
					'required' =>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> 'Please enter a username'
					)
				),
			'website' =>
				array
				(
					'allowEmpty'	=> true,
					'rule'			=> 'url',
					'message'		=> 'Please enter a valid URL (or leave empty)'
				),
			'email' =>
				array
				(
					'mail' =>
					array
					(
						'rule'		=> VALID_EMAIL,
						'message'	=> 'Please enter a valid email'
					),
					'length' =>
					array
					(
						'rule'		=> array('maxLength', 100),
						'message'	=> 'Email cannot be longer than 100 characters'
					)
				),
			'comment' =>
				array
				(
					'required' =>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> 'Please enter a comment'
					),
					'length' =>
					array
					(
						'rule'		=> array('maxLength', 1000),
						'message'	=> 'Comment shouldn\'t be longer than 1000 characters'
					)
				)
		);

	function findForRss()
	{
		return $this->find
			(
				'all',
				array
				(
					'order'			=> 'Comment.created DESC',
					'limit'			=> 10
				)
			);
	}
}

?>
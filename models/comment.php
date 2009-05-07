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

	var $validate = array();

	function __construct()
	{
		parent::__construct();

		$this->validate = array
			(
				'article_id'	=> VALID_NOT_EMPTY,
				'name' =>
					array
					(
						'required' =>
						array
						(
							'rule'		=> VALID_NOT_EMPTY,
							'message'	=> __('Please enter a username', true)
						)
					),
				'website' =>
					array
					(
						'allowEmpty'	=> true,
						'rule'			=> 'url',
						'message'		=> __('Please enter a valid URL (or leave empty)', true)
					),
				'email' =>
					array
					(
						'mail' =>
						array
						(
							'rule'		=> VALID_EMAIL,
							'message'	=> __('Please enter a valid email', true)
						),
						'length' =>
						array
						(
							'rule'		=> array('maxLength', 100),
							'message'	=> __('Email cannot be longer than 100 characters', true)
						)
					),
				'comment' =>
					array
					(
						'required' =>
						array
						(
							'rule'		=> VALID_NOT_EMPTY,
							'message'	=> __('Please enter a comment', true)
						),
						'length' =>
						array
						(
							'rule'		=> array('maxLength', 1000),
							'message'	=> __('Comment shouldn\'t be longer than 1000 characters', true)
						)
					)
			);
	}

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
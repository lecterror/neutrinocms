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

class Download extends AppModel
{
	var $name = 'Download';
	var $noDrafts = array('Download.published' => 1);

	var $belongsTo =
		array
		(
			'DownloadCategory' =>
				array
				(
					'className'  => 'DownloadCategory',
					'conditions' => '',
					'order'      => '',
					'foreignKey' => 'download_category_id'
				)
		);

	var $actsAs =
		array
		(
			'Hitcount' => array('hitField' => 'downloaded')
		);

	var $hasMany =
		array
		(
			'Rating' =>
				array
				(
					'className'		=> 'Rating',
					'conditions'	=> array('class_name' => 'Download'),
					'order'			=> 'Rating.created',
					'foreignKey'	=> 'foreign_id',
					'dependent'     => true
				)
		);

	var $validate = array();

	function __construct()
	{
		parent::__construct();

		$this->validate = array
			(
				'name' =>
					array
					(
						'required' =>
							array
							(
								'rule' => VALID_NOT_EMPTY,
								'message' => __('Enter a download name', true)
							),
						'length' =>
							array
							(
								'rule' => array('maxLength', 200),
								'message' => __('Download name is too long (200 characters max)', true)
							)
					),
				'display_file_name' =>
					array
					(
						'required' =>
							array
							(
								'rule' => VALID_NOT_EMPTY,
								'message' => __('Enter a file name', true)
							),
						'length' =>
							array
							(
								'rule' => array('maxLength', 255),
								'message' => __('File name is too long (255 characters max)', true)
							)
					),
				'real_file_name' =>
					array
					(
						'required' =>
							array
							(
								'rule' => VALID_NOT_EMPTY,
								'message' => __('Enter a file name', true)
							),
						'length' =>
							array
							(
								'rule' => array('maxLength', 255),
								'message' => __('File name is too long (255 characters max)', true)
							),
						'fileMustExist' =>
							array
							(
								'rule' => 'fileMustExist',
								'message' => __('The selected file does not exist', true)
							)
					),
				'slug' =>
					array
					(
						'required' =>
							array
							(
								'rule' => '/^[a-z0-9_\-\.]+$/',
								'message' => __('Enter a slug (lowercase letters, numbers, underscore and hyphen are allowed)', true)
							),
						'length' =>
							array
							(
								'rule' => array('maxLength', 50),
								'message' => __('Slug is too long (50 characters max)', true)
							),
						'unique' =>
							array
							(
								'rule' => 'isUnique',
								'message' => __('A download with the same slug already exists', true)
							)
					),
				'downloaded' =>
					array
					(
						'number' =>
							array
							(
								'rule' => '/^[0-9]+$/',
								'message' => __('Enter a valid number', true)
							),
						'required' =>
							array
							(
								'rule' => VALID_NOT_EMPTY,
								'message' => __('Enter an initial hitcount (normally zero)', true)
							)
					),
				'content_description' =>
					array
					(
						'required' =>
						array
						(
							'rule'		=> VALID_NOT_EMPTY,
							'message'	=> __('Content description cannot be empty', true)
						),
						'length' =>
						array
						(
							'rule'		=> array('maxLength', 500),
							'message'	=> __('Content description is too long', true)
						)
					),
				'content_keywords' =>
					array
					(
						'required' =>
						array
						(
							'rule'		=> VALID_NOT_EMPTY,
							'message'	=> __('Content keywords cannot be empty', true)
						),
						'length' =>
						array
						(
							'rule'		=> array('maxLength', 1000),
							'message'	=> __('Content keywords are too long', true)
						),
						'limit' =>
						array
						(
							'rule'		=> array('keywordLimit', 20),
							'message'	=> __('You need to have less than 20 keywords', true)
						)
					),
				'description' =>
					array
					(
						'required' =>
						array
						(
							'rule'		=> VALID_NOT_EMPTY,
							'message'	=> __('Description cannot be empty', true)
						)
					)
			);
	}

	function beforeSave()
	{
		if (!isset($this->data['Download']['real_file_name'])
			|| empty($this->data['Download']['real_file_name']))
		{
			$this->invalidate('real_file_name', 'fileMustExist');
			return false;
		}

		$filename = $this->data['Download']['real_file_name'];

		if (!$this->fileMustExist(array('real_file_name' => $filename)))
		{
			return false;
		}

		$file = new File(FILES.$filename);

		if (function_exists('mime_content_type'))
		{
			$this->data['Download']['mime_type'] = mime_content_type($filename);
		}
		else
		{
			$this->data['Download']['mime_type'] = 'application/octet-stream';
		}

		$this->data['Download']['size'] = $file->size();

		return true;
	}

	function fileMustExist($data)
	{
		$filename = $data['real_file_name'];
		$file = new File(FILES.$filename);

		return $file->exists();
	}

	function getSlug($id)
	{
		$newData = $this->find
			(
				'first',
				array
				(
					'conditions' => array
					(
						'Download.id' => $id
					),
					'fields' => array('slug'),
					'recursive' => -1
				)
			);

		return $newData['Download']['slug'];
	}

	function getSingle($slug)
	{
		$_slug = Sanitize::escape($slug);

		return $this->find
			(
				'first',
				array
				(
					'conditions' => array('Download.slug' => $_slug),
					'contain' => array('DownloadCategory', 'Rating')
				)
			);
	}

	function getDownloadStats()
	{
		return $this->find
			(
				'all',
				array
				(
					'conditions' => array('downloaded >' => '0'),
					'fields' => array('id', 'name', 'slug', 'downloaded'),
					'order' => 'downloaded DESC',
					'recursive' => -1
				)
			);
	}

	function getSitemapInformation()
	{
		return $this->find
			(
				'all',
				array
				(
					'conditions' => array('published' => 1),
					'fields' => array('name', 'slug', 'created'),
					'order' => 'created DESC',
					'recursive' => -1
				)
			);
	}
}

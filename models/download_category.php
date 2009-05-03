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

class DownloadCategory extends AppModel
{
	var $name = 'DownloadCategory';
	var $displayField = 'name';

	var $actsAs =
		array(
			'Sluggable' =>
				array
				(
					'length'		=> 50,
					'overwrite'		=> true,
					'label'			=> 'name',
					'slug'			=> 'slug',
					'separator'		=> '-'
				)
		);

	var $hasMany =
		array
		(
			'Download' =>
			array
			(
				'className'     => 'Download',
				'conditions'    => '',
				'order'         => '',
				'limit'         => '',
				'foreignKey'    => 'download_category_id',
				'dependent'     => true,
				'exclusive'     => false,
				'finderQuery'   => ''
			)
		);

	var $validate = array(
		'name' =>
			array
			(
				'required' =>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> 'Category name cannot be empty'
				),
				'unique' =>
				array
				(
					'rule'		=> 'isUnique',
					'message'	=> 'A category with the same name already exists'
				),
				'length' =>
				array
				(
					'rule'		=> array('maxLength', 100),
					'message'	=> 'Category name cannot be longer than 100 characters'
				)
			),
		'description' =>
			array
			(
				'required' =>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> 'Category description cannot be empty'
				),
				'length' =>
				array
				(
					'rule'		=> array('maxLength', 500),
					'message'	=> 'Category name cannot be longer than 500 characters'
				)
			)
		);

	function getSlug($id)
	{
		$newData = $this->find
			(
				'first',
				array
				(
					'conditions' => array
					(
						'DownloadCategory.id' => $id
					),
					'fields' => array('slug'),
					'recursive' => -1
				)
			);

		return $newData['DownloadCategory']['slug'];
	}

	function getSingle($slug, $recursive = -1)
	{
		$_slug = Sanitize::escape($slug);

		return $this->find
			(
				'first',
				array
				(
					'conditions' => array('DownloadCategory.slug' => $_slug),
					'recursive' => $recursive
				)
			);
	}

	function findSimilar($slug, $recursive = -1)
	{
		$_slug = Sanitize::escape(trim(low($slug)));

		return $this->find
			(
				'all',
				array
				(
					'conditions' => array
					(
						'or' => array
						(
							'LOWER(DownloadCategory.name) like' => '%'.$_slug.'%',
							'LOWER(DownloadCategory.description) like' => '%'.$_slug.'%',
							'LOWER(DownloadCategory.slug) like' => '%'.$_slug.'%'
						)
					),
					'recursive' => $recursive
				)
			);
	}

	function findRelatedDownloads($categoryId, $user)
	{
		$conditions = array('Download.download_category_id' => $categoryId);

		if (!$user)
		{
			$conditions = array_merge($conditions, $this->Download->noDrafts);
		}

		return $this->Download->find
			(
				'all',
				array
				(
					'conditions' => $conditions,
					'order' => 'created DESC',
					'contain' => array('DownloadCategory')
				)
			);
	}

	function afterSave($created)
	{
		clearCache('element_cache_downloadmenu', 'views', '');

		parent::afterSave($created);
	}

	function afterDelete()
	{
		clearCache('element_cache_downloadmenu', 'views', '');

		parent::afterDelete();
	}
}
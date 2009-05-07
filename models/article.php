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

uses('Sanitize');

class Article extends AppModel
{
	var $name = 'Article';
	var $noDrafts = array('Article.isdraft' => 0);

	var $actsAs =
		array(
			'Hitcount',
			'Sluggable' =>
				array
				(
					'length'		=> 50,
					'overwrite'		=> true,
					'label'			=> 'title',
					'slug'			=> 'slug',
					'separator'		=> '-'
				)
		);

	var $belongsTo =
		array
		(
			'ArticleCategory' =>
				array
				(
					'className'  => 'ArticleCategory',
					'conditions' => '',
					'order'      => '',
					'foreignKey' => 'article_category_id'
				)
		);

	var $hasMany =
		array
		(
			'Comment' =>
				array
				(
					'className'		=> 'Comment',
					'conditions'	=> '',
					'order'			=> 'Comment.created ASC',
					'foreignKey'	=> 'article_id',
					'dependent'     => true
				),
			'Rating' =>
				array
				(
					'className'		=> 'Rating',
					'conditions'	=> array('class_name' => 'Article'),
					'order'			=> 'Rating.created',
					'foreignKey'	=> 'foreign_id',
					'dependent'     => true
				)
		);

	var $validate = array();

	function __construct()
	{
		parent::__construct();

		$this->validate = array(
			'article_category_id' =>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> __('Article category cannot be empty', true)
				),
			'title' =>
				array
				(
					'required' =>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('Article title cannot be empty', true)
					),
					'length' =>
					array
					(
						'rule'		=> array('maxLength', 100),
						'message'	=> __('Title cannot be longer than 100 characters', true)
					),
					'unique' =>
					array
					(
						'rule'		=> 'isUnique',
						'message'	=> __('An article with the same title already exists', true)
					)
				),
			'intro' =>
				array
				(
					'required' =>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('Article introduction cannot be empty', true)
					),
					'length' =>
					array
					(
						'rule'		=> array('maxLength', 255),
						'message'	=> __('Introduction cannot be longer than 255 characters', true)
					)
				),
			'content' =>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> __('Article content cannot be empty', true)
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
				)
			);
	}

	function search($phrase, $user)
	{
		$_phrase = Sanitize::escape(trim(low($phrase)));

		$conditions = array
			(
				'or' => array
				(
					'LOWER(Article.title) like' => '%'.$_phrase.'%',
					'LOWER(Article.intro) like' => '%'.$_phrase.'%',
					'LOWER(Article.content) like' => '%'.$_phrase.'%',
					'LOWER(Article.slug) like' => '%'.$_phrase.'%'
				)
			);

		if (!$user)
		{
			$conditions = array_merge($conditions, $this->noDrafts);
		}

		return $this->find
			(
				'all',
				array
				(
					'conditions' => $conditions,
					'contain' => array('ArticleCategory', 'Rating', 'Comment')
				)
			);
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
						'Article.id' => $id
					),
					'fields' => array('slug'),
					'recursive' => -1
				)
			);

		return $newData['Article']['slug'];
	}

	function findForHomepage($user)
	{
		$conditions = array();

		if (!$user)
		{
			$conditions = array_merge($conditions, $this->noDrafts);
		}

		return $this->find
			(
				'all',
				array
				(
					'conditions' => $conditions,
					'order' => 'created DESC',
					'limit' => 5,
					'contain' => array('ArticleCategory', 'Comment')
				)
			);
	}

	function findSimilar($slug, $user, $recursive = -1)
	{
		$_slug = Sanitize::escape(trim(low($slug)));
		$conditions = array
			(
				'or' => array
				(
					'LOWER(Article.title) like' => '%'.$_slug.'%',
					'LOWER(Article.intro) like' => '%'.$_slug.'%',
					'LOWER(Article.content) like' => '%'.$_slug.'%',
					'LOWER(Article.slug) like' => '%'.$_slug.'%'
				)
			);

		if (!$user)
		{
			$conditions = array_merge($conditions, $this->noDrafts);
		}

		return $this->find
			(
				'all',
				array
				(
					'conditions' => $conditions,
					'contain' => array('ArticleCategory', 'Comment')
				)
			);
	}

	function getSingle($slug)
	{
		$_slug = Sanitize::escape($slug);

		return $this->find
			(
				'first',
				array
				(
					'conditions' => array('Article.slug' => $_slug),
					'contain' => array('ArticleCategory', 'Rating')
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
					'conditions' => array('isdraft' => 0),
					'fields' => array('title', 'slug', 'updated'),
					'order' => 'updated DESC',
					'recursive' => -1
				)
			);
	}

	function findForRss($conditions = array())
	{
		$conditions = array_merge($conditions, $this->noDrafts);

		return $this->find
			(
				'all',
				array
				(
					'conditions'	=> $conditions,
					'order'			=> 'created DESC',
					'limit'			=> 10
				)
			);
	}

	function getHitStats()
	{
		return $this->find
			(
				'all',
				array
				(
					'conditions' => array('isdraft' => 0, 'hitcount >' => '0'),
					'fields' => array('id', 'title', 'slug', 'hitcount', 'hitcount_rss'),
					'order' => 'hitcount DESC',
					'recursive' => -1
				)
			);
	}

	function getMostPopular($limit)
	{
		return $this->find
			(
				'all',
				array
				(
					'conditions' => array('isdraft' => 0),
					'fields' => array('title', 'slug'),
					'order' => 'hitcount DESC',
					'limit' => $limit,
					'recursive' => -1
				)
			);
	}

	function getMostCommented($limit)
	{
		$commentsCount = $this->Comment->find
			(
				'all',
				array
				(
					'fields' => array('article_id', 'count(*) as count'),
					'conditions' => '1 = 1 GROUP BY article_id',
					'order' => 'count DESC',
					'recursive' => -1,
					'limit' => $limit
				)
			);

		$articles = array();

		foreach ($commentsCount as $comment)
		{
			$articles = array_merge
				(
					$articles,
					$this->find
					(
						'all',
						array
						(
							'conditions' => array('id' => $comment['Comment']['article_id']),
							'fields' => array
							(
								'id',
								'title',
								'slug'
							),
							'recursive' => -1
						)
					)
				);
		}

		return $articles;
	}

	function getHighestRated($limit)
	{
		$articlesTemp = $this->find(
			'all',
			array(
				'contain' => 'Rating',
				'fields' => array
				(
					'Article.slug',
					'Article.title'
				)
			)
		);

		$articlesTemp = Set::sort($articlesTemp, '{n}.Rating.Summary.totalRating', 'desc');
		$top = (count($articlesTemp) < $limit ? count($articlesTemp) : $limit);
		$articles = array();

		for ($i = 0; $i < $top; $i++)
		{
			if (!Set::check($articlesTemp[$i], 'Rating.Summary'))
				break;

			$articles[] = $articlesTemp[$i];
		}

		return $articles;
	}
}

?>
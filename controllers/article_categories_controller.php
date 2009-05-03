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

class ArticleCategoriesController extends AppController
{
	var $name = 'ArticleCategories';
	var $components = array('RequestHandler');

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->allow('not_found', 'index', 'view');
		$this->Auth->deny('edit', 'add', 'delete');
	}

	function not_found($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$similar = $this->ArticleCategory->findSimilar($slug);
		$this->Session->setFlash('The requested category was not found!');
		$this->set(compact('similar', 'slug'));

		// clear the cache in case something is broken
		@clearCache('element_cache_sitemenu','views', '');
	}

	function index()
	{
		$categories = $this->ArticleCategory->find
			(
				'all',
				array
				(
					'files' => array('name', 'description', 'slug'),
					'recursive' => -1
				)
			);

		$this->set(compact('categories'));
	}

	function view($slug = null)
	{
		$category = $this->ArticleCategory->getSingle($slug);

		if (!$category)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$articles = $this->ArticleCategory->findRelatedArticles
			(
				$category['ArticleCategory']['id'],
				$this->Auth->user()
			);

		$this->set(compact('category', 'articles'));
	}

	function edit($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$category = $this->ArticleCategory->getSingle($slug);

		if (!$category)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$this->ArticleCategory->id = $category['ArticleCategory']['id'];

		if (empty($this->data))
		{
			$this->data = $category;
			unset($this->data['ArticleCategory']['id']); // "fix" for automagic form

			return;
		}

		$this->ArticleCategory->data = $this->data;

		if (!$this->ArticleCategory->validates())
		{
			$this->data['ArticleCategory']['slug'] = $slug;
			$this->Session->setFlash('Please correct the errors below');
			return;
		}

		if (!$this->ArticleCategory->save($this->data))
		{
			$this->Session->setFlash('Category was not saved!');
			return;
		}

		$this->Session->setFlash('Category saved');
		$continueEditing = (strpos(low($this->data['Submit']['type']), 'continue editing') !== false);

		$newSlug = $this->ArticleCategory->getSlug($this->ArticleCategory->id);

		if ($continueEditing)
		{
			$this->_redirectTo('edit', $newSlug);
		}

		$this->_redirectTo('view', $newSlug);
	}

	function add($slug = null)
	{
		if (empty($this->data))
		{
			if (!empty($slug))
			{
				$this->data['ArticleCategory']['name'] = $slug;
			}

			return;
		}

		$this->ArticleCategory->data = $this->data;

		if (!$this->ArticleCategory->validates())
		{
			$this->Session->setFlash('Please correct the errors below');
			return;
		}

		if (!$this->ArticleCategory->save($this->data))
		{
			$this->Session->setFlash('Category was not saved!');
			return;
		}

		$this->Session->setFlash('Category saved');
		$newSlug = $this->ArticleCategory->getSlug($this->ArticleCategory->id);

		if ($this->Session->check('ArticleCategory.Redirect'))
		{
			if ($this->Session->read('ArticleCategory.Redirect'))
			{
				$this->Session->del('ArticleCategory.Redirect');
				$this->redirect(array('controller' => 'articles', 'action' => 'add', 'category' => $newSlug));
			}
		}

		$this->_redirectTo('view', $newSlug);
	}

	function delete($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$category = $this->ArticleCategory->getSingle($slug);

		if (!$category)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$this->ArticleCategory->id = $category['ArticleCategory']['id'];

		if (empty($this->data))
		{
			$this->set(compact('category'));
			return;
		}

		$delete_button = (strpos(low($this->data['Submit']['type']), 'delete') !== false);

		if (!$delete_button)
		{
			$this->_redirectTo('view', $slug);
		}

		if (!$this->data['ArticleCategory']['delete'])
		{
			$this->ArticleCategory->invalidate('delete', 'You need to confirm this action by marking the checkbox');
			$this->Session->setFlash('Please correct the errors below');
			$this->set(compact('category'));
			return;
		}

		if ($this->ArticleCategory->del($category['ArticleCategory']['id']))
		{
			$this->Session->setFlash('Article category deleted successfully');
		}
		else
		{
			$this->Session->setFlash('Error while deleting article category');
		}

		$this->redirect('/');
	}
}

?>
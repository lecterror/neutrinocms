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

class DownloadCategoriesController extends AppController
{
	var $name = 'DownloadCategories';
	var $helpers = array('Number');

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

		$similar = $this->DownloadCategory->findSimilar($slug);
		$this->Session->setFlash('The requested category was not found!');
		$this->set(compact('similar', 'slug'));

		// clear the cache in case something is broken
		@clearCache('element_cache_downloadmenu','views', '');
	}

	function index()
	{
		$categories = $this->DownloadCategory->find
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
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$category = $this->DownloadCategory->getSingle($slug);

		if (!$category)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$downloads = $this->DownloadCategory->findRelatedDownloads
			(
				$category['DownloadCategory']['id'],
				$this->Auth->user()
			);

		$this->set(compact('category', 'downloads'));
	}

	function edit($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$category = $this->DownloadCategory->getSingle($slug);

		if (!$category)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$this->DownloadCategory->id = $category['DownloadCategory']['id'];

		if (empty($this->data))
		{
			$this->data = $category;
			unset($this->data['DownloadCategory']['id']); // "fix" for automagic form

			return;
		}

		$this->DownloadCategory->data = $this->data;

		if (!$this->DownloadCategory->validates())
		{
			$this->data['DownloadCategory']['slug'] = $slug;
			$this->Session->setFlash('Please correct the errors below');
			return;
		}

		if (!$this->DownloadCategory->save($this->data))
		{
			$this->Session->setFlash('Category was not saved!');
			return;
		}

		$this->Session->setFlash('Category saved');
		$continue_editing = (strpos(low($this->data['Submit']['type']), 'continue editing') !== false);

		$newSlug = $this->DownloadCategory->getSlug($this->DownloadCategory->id);

		if ($continue_editing)
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
				$this->data['DownloadCategory']['name'] = $slug;
			}

			return;
		}

		$this->DownloadCategory->data = $this->data;

		if (!$this->DownloadCategory->validates())
		{
			$this->Session->setFlash('Please correct the errors below');
			return;
		}

		if (!$this->DownloadCategory->save($this->data))
		{
			$this->Session->setFlash('Category was not saved!');
			return;
		}

		$this->Session->setFlash('Category saved');
		$newSlug = $this->DownloadCategory->getSlug($this->DownloadCategory->id);

		if ($this->Session->check('DownloadCategory.Redirect'))
		{
			if ($this->Session->read('DownloadCategory.Redirect'))
			{
				$this->Session->del('DownloadCategory.Redirect');
				$this->redirect(array('controller' => 'downloads', 'action' => 'add', 'category' => $newSlug));
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

		$category = $this->DownloadCategory->getSingle($slug);

		if (!$category)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$this->DownloadCategory->id = $category['DownloadCategory']['id'];

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

		if (!$this->data['DownloadCategory']['delete'])
		{
			$this->DownloadCategory->invalidate('delete', 'You need to confirm this action by marking the checkbox');
			$this->Session->setFlash('Please correct the errors below');
			$this->set(compact('category'));
			return;
		}

		if ($this->DownloadCategory->del($category['DownloadCategory']['id']))
		{
			$this->Session->setFlash('Download category deleted successfully');
		}
		else
		{
			$this->Session->setFlash('Error while deleting download category');
		}

		$this->redirect('/');
	}
}

?>
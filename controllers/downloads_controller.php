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

class DownloadsController extends AppController
{
	var $name = 'Downloads';
	var $uses = array('Download', 'Attachment');
	var $components = array('Cookie');
	var $helpers = array('Number');

	function _setCategories()
	{
		$categories = $this->Download->DownloadCategory->find
			(
				'list',
				array
				(
					'fields' => array('id', 'name'),
					'order' => 'DownloadCategory.name ASC',
					'recursive' => -1
				)
			);

		$this->set(compact('categories'));
	}

	function _setAttachments()
	{
		$files = $this->Attachment->getAttachments();
		$attachments = array();

		foreach ($files as $file)
		{
			$attachments[$file->name] = $file->name;
		}

		$this->set(compact('attachments'));
	}

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->allow('get', 'view', 'not_found', 'rate');
		$this->Auth->deny('add', 'delete');
	}

	/**
	 * TODO
	 */
	function not_found($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

	//	$like = $this->Article->findSimilar('title', $slug);

	//	$this->set('similar', $like);
		$this->Session->setFlash('The requested download was not found!');
		$this->set(compact('slug'));
	}

	function get($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$download = $this->Download->getSingle($slug);

		if (!$download)
		{
			$this->_redirectTo('not_found', $slug);
		}

		if (!$this->RequestHandler->isPost())
		{
			$this->_redirectTo('view', $slug);
		}

		if (!file_exists(FILES.$download['Download']['real_file_name']))
		{
			$this->_redirectTo('not_found', $slug);
		}

		if (!$this->Auth->user())
		{
			$this->Download->hit($download['Download']['id']);
		}

		$fakeFile = new File($download['Download']['display_file_name']);

		$path = FILES_REL;
		$id = $download['Download']['real_file_name'];
		$name = $fakeFile->name();
		$extension = $fakeFile->ext();
		$download = true;

		$this->view = 'media';
		Configure::write('debug', 0);
		$this->set(compact('path', 'id', 'name', 'extension', 'download'));
	}

	function add()
	{
		$this->_setCategories();
		$this->_setAttachments();

		if (empty($this->data))
		{
			if (empty($this->viewVars['categories']))
			{
				$this->Session->setFlash('You need to create a category first');
				$this->Session->write('DownloadCategory.Redirect', true);
				$this->redirect(array('controller' => 'download_categories', 'action' => 'add'));
			}

			$this->data['Download']['downloaded'] = 0;

			if (isset($this->passedArgs['category']))
			{
				$cat = $this->Download->DownloadCategory->getSingle($this->passedArgs['category']);

				if ($cat)
					$this->data['Download']['download_category_id'] = $cat['DownloadCategory']['id'];
			}

			if (empty($this->viewVars['attachments']))
			{
				$this->Session->setFlash('No files available!');
				$this->set('disable', true);
			}

			return;
		}

		$this->Download->data = $this->data;

		if (!$this->Download->validates())
		{
			$this->Session->setFlash('Please correct the errors below');
			return;
		}

		if (!$this->Download->save($this->data))
		{
			$this->Session->setFlash('Error while creating a download!');
			return;
		}

		$this->Session->setFlash('Download created successfully');
		$continue_editing = (strpos(low($this->data['Submit']['type']), 'continue editing') !== false);

		if ($continue_editing)
		{
			$this->_redirectTo('edit', $this->data['Download']['slug']);
		}

		$this->_redirectTo('view', $this->data['Download']['slug']);
	}

	function edit($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$download = $this->Download->getSingle($slug);

		if (!$download)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$this->Download->id = $download['Download']['id'];
		$this->_setCategories();
		$this->_setAttachments();

		if (empty($this->data))
		{
			$this->data = $download;
			unset($this->data['Download']['id']); // "fix" for automagic form

			return;
		}

		$this->Download->data = $this->data;

		if (!$this->Download->validates())
		{
			$this->data['Download']['slug'] = $slug;
			$this->Session->setFlash('Please correct the errors below');
			return;
		}

		if (!$this->Download->save($this->data))
		{
			$this->Session->setFlash('Download was not saved!');
			return;
		}

		$this->Session->setFlash('Download saved');
		$continue_editing = (strpos(low($this->data['Submit']['type']), 'continue editing') !== false);

		$newSlug = $this->Download->getSlug($this->Download->id);

		if ($continue_editing)
		{
			$this->_redirectTo('edit', $newSlug);
		}

		$this->_redirectTo('view', $newSlug);
	}

	function view($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$download = $this->Download->getSingle($slug);

		if (!$download)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$cookie = $this->Cookie->read('Download-'.$download['Download']['id'].'-Rating');

		if ($cookie && !empty($download['Rating']))
		{
			$this->Download->Rating->setupVoted($download, $cookie);
		}

		$this->set(compact('download'));
	}

	function delete($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$download = $this->Download->getSingle($slug);

		if (!$download)
		{
			$this->_redirectTo('not_found', $slug);
		}

		if (empty($this->data))
		{
			$this->set(compact('download'));
			return;
		}

		$delete_button = (strpos(low($this->data['Submit']['type']), 'delete') !== false);

		if (!$delete_button)
		{
			$this->_redirectTo('view', $download['Download']['slug']);
		}

		if (!$this->data['Download']['delete'])
		{
			$this->Download->invalidate('delete', 'You need to confirm this action by marking the checkbox');
			$this->Session->setFlash('Please correct the errors below');

			$this->set(compact('download'));
			return;
		}

		if ($this->Download->del($download['Download']['id']))
		{
			$this->Session->setFlash('Download deleted successfully');
		}
		else
		{
			$this->Session->setFlash('Error while deleting download');
		}

		$this->redirect('/');
	}

	function rate($slug = null, $rating = null)
	{
		if (!$this->_isAjaxRequest())
		{
			$this->redirect('/');
		}

		if (empty($slug) || empty($rating))
		{
			$this->redirect('/');
		}

		$this->layout = 'ajax';

		if (!Configure::read('debug') && $this->Auth->user())
		{
			$message = "So, you'd like to vote for your own articles?\nYou naughty boy..";
			$this->set(compact('message'));
			return;
		}

		$download = $this->Download->getSingle($slug);

		if (!$download)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$ratingCookie = $this->Cookie->read('Download-'.$download['Download']['id'].'-Rating');

		if ($ratingCookie)
		{
			$message = 'You\'ve already voted!';
			$this->set(compact('message'));
			return;
		}

		$ratingData = array
			(
				'Rating' =>
					array
					(
						'class_name' => $this->Download->name,
						'foreign_id' => $download['Download']['id'],
						'rating' => $rating
					)
			);

		$this->Download->Rating->data = $ratingData;

		if (!$this->Download->Rating->validates())
		{
			$message = 'Bummer, eh?';
			$this->set(compact('message'));
			return;
		}

		$this->Download->Rating->create();

		if (!$this->Download->Rating->save($ratingData))
		{
			$message = 'Whoops...crash!';
			$this->set(compact('message'));
			return;
		}
		if (!Configure::read('debug'))
		{
			$this->Cookie->write('Download-'.$download['Download']['id'].'-Rating', $rating, true, '+1 year');
		}

		$message = 'Thanks for voting!';
		$this->set(compact('message'));
	}
}

?>
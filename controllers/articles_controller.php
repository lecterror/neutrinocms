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

/**
 * @property Article $Article
 */
class ArticlesController extends AppController
{
	var $name = 'Articles';
	var $uses = array();
	var $components = array('Cookie');
	var $helpers = array('Markdown'/*, 'MathPublisher'*/);

	function _setCategories()
	{
		$categories = $this->Article->ArticleCategory->find
			(
				'list',
				array
				(
					'fields' => array('id', 'name'),
					'order' => 'ArticleCategory.name ASC',
					'recursive' => -1
				)
			);

		$this->set(compact('categories'));
	}

	function isAuthorized()
	{
		$model = ((isset($this->Article) && !is_null($this->Article)) ? $this->Article : null);

		return parent::isAuthorized($model);
	}

	function beforeFilter()
	{
		parent::beforeFilter();

		// make sure we're not installing or updating
		if ($this->_installed && !$this->_needsMigration)
		{
			$this->loadModel('Article');
		}

		$this->Auth->deny
			(
				'edit',
				'add',
				'delete'
			);
		$this->Auth->allow
			(
				'not_found',
				'index',
				'view',
				'home',
				'rate',
				'mostPopular',
				'mostCommented',
				'highestRated'
			);
	}

	function not_found($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$similar = $this->Article->findSimilar($slug, $this->_user);
		$this->Session->setFlash(__('The requested article was not found!', true));
		$this->set(compact('similar', 'slug'));
	}

	function index()
	{
		if (!$this->RequestHandler->isRss())
		{
			$this->redirect(array('controller' => 'articles', 'action' => 'home'));
		}

		$this->set(
				'channel',
				array(
					'title' => sprintf(__('%s - Latest articles', true), Configure::read('Neutrino.SiteTitle')),
					'description' => __('Latest articles', true)
				)
			);

		$this->set('articles', $this->Article->findForRss());
		return;
	}

	function home()
	{
		$this->set('articles', $this->Article->findForHomepage($this->_user));
		$this->pageTitle = Configure::read('Neutrino.SiteDescription');
	}

	function view($slug = null)
	{
		$article = $this->Article->getSingle($slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$cookie = $this->Cookie->read('Article-'.$article['Article']['id'].'-Rating');

		if ($cookie && !empty($article['Rating']))
		{
			$this->Article->Rating->setupVoted($article, $cookie);
		}

		if (isset($this->passedArgs['highlight']) && strlen($this->passedArgs['highlight']) > 1)
		{
			$phrase = preg_quote($this->passedArgs['highlight']);
			$this->set('highlight_phrase', $phrase);
		}

		if (!$this->_user['is_root'])
		{
			// rss stats
			if (isset($this->passedArgs['from']) && $this->passedArgs['from'] == 'rss')
			{
				$this->Article->hit($article['Article']['id'], array('hitField' => 'hitcount_rss'));
			}
			// regular hitcount
			$this->Article->hit($article['Article']['id']);
		}

		//$this->data = $article;
		$this->set(compact('article'));
	}

	function edit($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$article = $this->Article->getSingle($slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$this->Article->id = $article['Article']['id'];
		$this->_setCategories();

		if (empty($this->data))
		{
			$this->data = $article;
			unset($this->data['Article']['id']); // "fix" for automagic form

			return;
		}

		if (!isset($this->data['Article']['user_id']))
		{
			$this->data['Article']['user_id'] = $this->_user['id'];
		}

		$this->Article->data = $this->data;

		if (!$this->Article->validates())
		{
			$this->data['Article']['slug'] = $slug;
			$this->Session->setFlash(__('Please correct the errors below', true));
			return;
		}

		if (!$this->Article->save($this->data))
		{
			$this->Session->setFlash(__('Article was not saved!', true));
			return;
		}

		$this->Session->setFlash(__('Article saved', true));
		$continue_editing = (strpos(low($this->data['Submit']['type']), __('continue editing', true)) !== false);

		$newSlug = $this->Article->getSlug($this->Article->id);

		if ($continue_editing)
		{
			$this->_redirectTo('edit', $newSlug);
		}

		$this->_redirectTo('view', $newSlug);
	}

	function add($slug = null)
	{
		$this->_setCategories();

		if (empty($this->data))
		{
			if (empty($this->viewVars['categories']))
			{
				$this->Session->setFlash(__('You need to create a category first', true));
				$this->Session->write('ArticleCategory.Redirect', true);
				$this->redirect(array('controller' => 'article_categories', 'action' => 'add'));
			}

			if (!empty($slug))
			{
				$this->data['Article']['title'] = $slug;
			}

			if (isset($this->passedArgs['category']))
			{
				$cat = $this->Article->ArticleCategory->getSingle($this->passedArgs['category']);

				if ($cat)
				{
					$this->data['Article']['article_category_id'] = $cat['ArticleCategory']['id'];
				}
			}

			return;
		}

		if (!isset($this->data['Article']['user_id']))
		{
			$this->data['Article']['user_id'] = $this->_user['id'];
		}

		$this->Article->data = $this->data;

		if (!$this->Article->validates())
		{
			$this->Session->setFlash(__('Please correct the errors below', true));
			return;
		}

		if (!$this->Article->save($this->data))
		{
			$this->Session->setFlash(__('Article was not saved!', true));
			return;
		}

		$this->Session->setFlash(__('Article saved', true));
		$continue_editing = (strpos(low($this->data['Submit']['type']), __('continue editing', true)) !== false);

		$newSlug = $this->Article->getSlug($this->Article->id);

		if ($continue_editing)
		{
			$this->_redirectTo('edit', $newSlug);
		}

		$this->_redirectTo('view', $newSlug);
	}

	function delete($slug = null)
	{
		if (empty($slug))
		{
			$this->redirect('/');
		}

		$article = $this->Article->getSingle($slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$this->Article->id = $article['Article']['id'];

		if (empty($this->data))
		{
			$this->set('article', $article);
			return;
		}

		$delete_button = (strpos(low($this->data['Submit']['type']), __('delete', true)) !== false);

		if (!$delete_button)
		{
			$this->_redirectTo('view', $article['Article']['slug']);
		}

		if (!$this->data['Article']['delete'])
		{
			$this->Article->invalidate('delete', __('You need to confirm this action by marking the checkbox', true));
			$this->Session->setFlash(__('Please correct the errors below', true));
			$this->set('article', $article);
			return;
		}

		if (!$this->Article->del($article['Article']['id']))
		{
			$this->Session->setFlash(__('Error while deleting article', true));
			return;
		}

		$this->Session->setFlash(__('Article deleted successfully', true));
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

		// @todo: check owner of the article
		if (!Configure::read('debug') && $this->_user)
		{
			$message = __("So, you'd like to vote for your own articles?\nYou naughty boy..", true);
			$this->set(compact('message'));
			return;
		}

		$article = $this->Article->getSingle($slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$ratingCookie = $this->Cookie->read('Article-'.$article['Article']['id'].'-Rating');

		if ($ratingCookie)
		{
			$message = __('You have already rated!', true);
			$this->set(compact('message'));
			return;
		}

		$ratingData = array
			(
				'Rating' =>
					array
					(
						'class_name' => $this->Article->name,
						'foreign_id' => $article['Article']['id'],
						'rating' => $rating
					)
			);

		$this->Article->Rating->data = $ratingData;

		if (!$this->Article->Rating->validates())
		{
			$message = __('Bummer, eh?', true);
			$this->set(compact('message'));
			return;
		}

		$this->Article->Rating->create();

		if (!$this->Article->Rating->save($ratingData))
		{
			$message = __('Whoops...something crashed!', true);
			$this->set(compact('message'));
			return;
		}

		if (!Configure::read('debug'))
		{
			$this->Cookie->write('Article-'.$article['Article']['id'].'-Rating', $rating, true, '+2 years');
		}

		$message = __('Thanks for rating!', true);
		$this->set(compact('message'));
	}

	function mostPopular()
	{
		if ($this->params['requested'] !== 1)
			$this->redirect('/');

		$limit = 5;
		if (!empty($this->params['limit']))
			$limit = $this->params['limit'];

		return $this->Article->getMostPopular($limit);
	}

	function mostCommented()
	{
		if ($this->params['requested'] !== 1)
			$this->redirect('/');

		$limit = 5;
		if (!empty($this->params['limit']))
			$limit = $this->params['limit'];

		return $this->Article->getMostCommented($limit);
	}

	function highestRated()
	{
		if ($this->params['requested'] !== 1)
			$this->redirect('/');

		$limit = 5;
		if (!empty($this->params['limit']))
			$limit = $this->params['limit'];

		return $this->Article->getHighestRated($limit);
	}
}

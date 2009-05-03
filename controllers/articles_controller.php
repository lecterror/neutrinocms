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

class ArticlesController extends AppController
{
	var $name = 'Articles';
	var $uses = array();
	var $components = array('Cookie');
	var $helpers = array('Markdown'/*, 'MathPublisher'*/);
	var $paginate = array('Comment' =>
			array
			(
				'limit'		=> 5,
				'page'		=> 1,
				'recursive'	=> -1
			)
		);

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

	function beforeFilter()
	{
		parent::beforeFilter();

		// make sure we're not installing or updating
		if (Configure::read('Neutrino.Installed') &&
			Configure::read('Neutrino.CurrentDbVersion')
				== $this->_configuration->requiredDbVersion)
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

		$similar = $this->Article->findSimilar($slug, $this->Auth->user());
		$this->Session->setFlash('The requested article was not found!');
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
					'title' => Configure::read('Neutrino.SiteTitle').' - Latest articles',
					'description' => 'Latest articles'
				)
			);

		$this->set('articles', $this->Article->findForRss());
		return;
	}

	function home()
	{
		$this->set('articles', $this->Article->findForHomepage($this->Auth->user()));
		$this->pageTitle = Configure::read('Neutrino.SiteDescription');
	}

	function view($slug = null)
	{
		$article = $this->Article->getSingle($slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$this->paginate['Comment']['conditions'] = 'Comment.article_id = '.$article['Article']['id'];
		$this->set('comments', $this->paginate('Article.Comment'));
		$this->set('comments_count', $this->params['paging']['Comment']['count']);

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

		if (!$this->Auth->user())
		{
			// rss stats
			if (isset($this->passedArgs['from']) && $this->passedArgs['from'] == 'rss')
			{
				$this->Article->hit($article['Article']['id'], array('hitField' => 'hitcount_rss'));
			}
			// regular hitcount
			$this->Article->hit($article['Article']['id']);
		}

		$this->data = $article;
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

		$this->Article->data = $this->data;

		if (!$this->Article->validates())
		{
			$this->data['Article']['slug'] = $slug;
			$this->Session->setFlash('Please correct the errors below');
			return;
		}

		if (!$this->Article->save($this->data))
		{
			$this->Session->setFlash('Article was not saved!');
			return;
		}

		$this->Session->setFlash('Article saved');
		$continue_editing = (strpos(low($this->data['Submit']['type']), 'continue editing') !== false);

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
				$this->Session->setFlash('You need to create a category first');
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

		$this->Article->data = $this->data;

		if (!$this->Article->validates())
		{
			$this->Session->setFlash('Please correct the errors below');
			return;
		}

		if (!$this->Article->save($this->data))
		{
			$this->Session->setFlash('Article was not saved!');
			return;
		}

		$this->Session->setFlash('Article saved');
		$continue_editing = (strpos(low($this->data['Submit']['type']), 'continue editing') !== false);

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

		$delete_button = (strpos(low($this->data['Submit']['type']), 'delete') !== false);

		if (!$delete_button)
		{
			$this->_redirectTo('view', $article['Article']['slug']);
		}

		if (!$this->data['Article']['delete'])
		{
			$this->Article->invalidate('delete', 'You need to confirm this action by marking the checkbox');
			$this->Session->setFlash('Please correct the errors below');
			$this->set('article', $article);
			return;
		}

		if (!$this->Article->del($article['Article']['id']))
		{
			$this->Session->setFlash('Error while deleting article');
			return;
		}

		$this->Session->setFlash('Article deleted successfully');
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

		$article = $this->Article->getSingle($slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $slug);
		}

		$ratingCookie = $this->Cookie->read('Article-'.$article['Article']['id'].'-Rating');

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
						'class_name' => $this->Article->name,
						'foreign_id' => $article['Article']['id'],
						'rating' => $rating
					)
			);

		$this->Article->Rating->data = $ratingData;

		if (!$this->Article->Rating->validates())
		{
			$message = 'Bummer, eh?';
			$this->set(compact('message'));
			return;
		}

		$this->Article->Rating->create();

		if (!$this->Article->Rating->save($ratingData))
		{
			$message = 'Whoops...crash!';
			$this->set(compact('message'));
			return;
		}

		if (!Configure::read('debug'))
		{
			$this->Cookie->write('Article-'.$article['Article']['id'].'-Rating', $rating, true, '+2 years');
		}

		$message = 'Thanks for voting!';
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

?>
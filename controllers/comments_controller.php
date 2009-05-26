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

class CommentsController extends AppController
{
	var $uses = array('Comment', 'User');
	var $name = 'Comments';
	var $components = array('Captcha', 'Cookie', 'Email');
	var $helpers = array('Captcha');

	function isAuthorized()
	{
		$model = ((isset($this->Comment) && !is_null($this->Comment)) ? $this->Comment : null);

		return parent::isAuthorized($model);
	}

	function _getComments($article_id)
	{
		return $this->Comment->find
			(
				'all',
				array
				(
					'conditions' => array
					(
						'article_id' => $article_id
					),
					'contain' => array()
				)
			);
	}

	function _sendNewCommentNotification($user, $comment, $article)
	{
		$this->Email->reset();
		$this->Email->sendAs = 'both';
		$this->Email->to = '"'.$user['User']['first_name'].' '.$user['User']['last_name'].'" <' . $user['User']['email'] . '>';
		$this->Email->from = '"'.Configure::read('Neutrino.SiteTitle').'" <noreply@'.env('HTTP_HOST').'>';
		$this->Email->replyTo = '"'.Configure::read('Neutrino.SiteTitle').'" <noreply@'.env('HTTP_HOST').'>';
		$this->Email->return = '"'.Configure::read('Neutrino.SiteTitle').'" <noreply@'.env('HTTP_HOST').'>';
		$this->Email->subject = sprintf(__('%s - comment posted', true), Configure::read('Neutrino.SiteTitle'));
		$this->Email->template = 'new_comment';

		$this->set('email_user', $user);
		$this->set('email_comment', $comment);
		$this->set('email_article', $article);

		if (Configure::read('debug'))
		{
			$this->Email->_debug = true;
		}

		$this->Email->send();
	}

	function _renderComments()
	{
		$this->autoRender = false;
		$this->viewPath = 'elements'.DS.'comments';
		$this->render('toolbar', 'ajax'); // @todo: change this
	}

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->allow('captcha', 'add', 'view', 'index');
		$this->Auth->deny('delete');
	}

	function captcha()
	{
		// for some reason, this line is necessary when Cookie component is included,
		// and a cookie exists in the request.. don't know why really, so here it is..
		srand(time());

		$this->disableCache();
		$this->Captcha->image();
	}

	function view($article_slug = null)
	{
		if (empty($article_slug) || !$this->_isAjaxRequest())
		{
			$this->_redirectToReferrer();
		}

		$article = $this->Comment->Article->getSingle($article_slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $article_slug);
		}

		$this->set('article', $article);
		$this->set('commentsCount', count($article['Comment']));
		$this->_renderComments();
	}

	function add($article_slug = null)
	{
		if (empty($article_slug) || !$this->_isAjaxRequest())
		{
			$this->_redirectToReferrer();
		}

		$article = $this->Comment->Article->getSingle($article_slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $article_slug);
		}

		$this->set(compact('article'));

		if (empty($this->data))
		{
			$user = $this->Auth->user();

			if ($user)
			{
				$this->data['Comment']['name']		= $user['User']['username'];
				$this->data['Comment']['website']	= Router::url('/', true);
				$this->data['Comment']['email']		= $user['User']['email'];
			}
			else
			{
				$old_data = $this->Cookie->read('CommentInfo');

				if (is_array($old_data) && !empty($old_data))
				{
					$this->data['Comment']['name']		= $old_data['name'];
					$this->data['Comment']['website']	= $old_data['website'];
					$this->data['Comment']['email']		= $old_data['email'];
				}
			}

			$this->set('commentsCount', count($article['Comment']));
			$this->layout = 'ajax';
			return;
		}

		$this->data['Comment']['article_id'] = $article['Article']['id'];
		$this->Comment->data = $this->data;

		$captcha_valid = $this->Captcha->check($this->data['Comment']['captcha'], true);
		$data_valid = $this->Comment->validates();

		if (!$data_valid || !$captcha_valid)
		{
			if (!$captcha_valid)
			{
				$this->Comment->invalidate('captcha', __('Please type the code from the image above', true));
			}

			$this->set('commentsCount', count($article['Comment']));
			$this->layout = 'ajax';
			return;
		}

		if ($this->Auth->user())
		{
			$this->data['Comment']['article_author'] = true;
		}

		if (!$this->Comment->save($this->data))
		{
			$this->data['Comment']['captcha'] = '';

			$this->set('commentsCount', count($article['Comment']));
			$this->_renderComments();
			return;
		}

		// store cookie with user info
		$cookie = array
			(
				'name'		=> $this->data['Comment']['name'],
				'website'	=> $this->data['Comment']['website'],
				'email'		=> $this->data['Comment']['email']
			);

		$this->Cookie->write('CommentInfo', $cookie, true, '+4 weeks');

		// send notification email
		if (!isset($this->data['Comment']['article_author'])
			|| $this->data['Comment']['article_author'] == false)
		{
			$users = $this->User->find
				(
					'all',
					array
					(
						'fields' => array('first_name', 'last_name', 'email'),
						'recursive' => -1
					)
				);

			foreach ($users as $user)
			{
				$this->_sendNewCommentNotification($user, $this->data, $article);
			}
		}

		$this->set('article', $this->Comment->Article->getSingle($article_slug));
		$this->set('commentsCount', count($article['Comment']));
		$this->_renderComments();
		return;
	}

	function delete($article_slug = null, $id = null)
	{
		if (empty($article_slug) || empty($id) || !$this->_isAjaxRequest())
		{
			$this->_redirectToReferrer();
		}

		$article = $this->Comment->Article->getSingle($article_slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $article_slug);
		}

		$this->Comment->del(Sanitize::escape($id));

		$this->set(compact('article'));
		$this->set('commentsCount', count($article['Comment']));
		$this->_renderComments();
	}

	function index()
	{
		if (!$this->RequestHandler->isRss())
		{
			$this->redirect('/');
		}

		$this->set
			(
				'channel',
				array
				(
					'title' => sprintf(__('%s - Latest comments', true), Configure::read('Neutrino.SiteTitle')),
					'description' => __('Latest comments', true)
				)
			);

		$this->set('comments', $this->Comment->findForRss());
	}
}

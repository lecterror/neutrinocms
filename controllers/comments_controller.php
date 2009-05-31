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
 * @property Comment $Comment
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

	private function _sendNewCommentNotification($user, $comment, $article)
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

		$this->Email->send();
	}

	private function _renderArticle($article)
	{
		$cookie = $this->Cookie->read('Article-'.$article['Article']['id'].'-Rating');

		if ($cookie && !empty($article['Rating']))
		{
			$this->Comment->Article->Rating->setupVoted($article, $cookie);
		}

		$this->set('article', $article);
		$this->render('/articles/view');
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

	function add($article_slug = null)
	{
		if (!$this->RequestHandler->isPost() || empty($article_slug)) // @todo: ??
		{
			$this->_redirectToReferrer();
		}

		$article = $this->Comment->Article->getSingle($article_slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $article_slug);
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

			$this->set('commentInputError', true);
			$this->_renderArticle($article);
			return;
		}

		// @todo: remove
		if ($this->Auth->user())
		{
			$this->data['Comment']['article_author'] = true;
		}

		if (!$this->Comment->save($this->data))
		{
			$this->data['Comment']['captcha'] = '';

			$this->_renderArticle($article);
			return;
		}

		$comment = $this->data;
		$comment['Comment']['id'] = $this->Comment->id;

		// store cookie with user info
		$cookie = array
			(
				'name'		=> $this->data['Comment']['name'],
				'website'	=> $this->data['Comment']['website'],
				'email'		=> $this->data['Comment']['email']
			);

		$this->Cookie->write('CommentInfo', $cookie, true, '+4 months');

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
				$this->_sendNewCommentNotification($user, $comment, $article);
			}
		}

		$this->redirect
			(
				array
				(
					'controller' => 'articles',
					'action' => 'view',
					$article_slug,
					sprintf('#comment-%s', $this->Comment->id)
				)
			);
		return;
	}

	function delete($article_slug = null, $id = null)
	{
		if (empty($article_slug) || empty($id))
		{
			$this->_redirectToReferrer();
		}

		$article = $this->Comment->Article->getSingle($article_slug);

		if (!$article)
		{
			$this->_redirectTo('not_found', $article_slug);
		}

		$this->Comment->del(Sanitize::escape($id));
		$this->redirect
			(
				array
				(
					'controller' => 'articles',
					'action' => 'view',
					$article_slug,
					'#comments'
				)
			);
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

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

class FeedHelper extends Helper
{
	var $helpers = array('Html');

	function articleTransformRss($data)
	{
		return
			array
			(
				'title' => $data['Article']['title'],
				'link'  => array('action' => 'view', $data['Article']['slug'].'/from:rss'),
				'guid'  => array('action' => 'view', $data['Article']['slug'].'/from:rss'),
				'description' => $data['Article']['intro'],
				'author' => '',
				'pubDate' => $data['Article']['updated']
			);
	}

	function articleLink()
	{
		return $this->output
			(
				$this->Html->meta
					(
						'rss',
						'/articles.rss',
						array('title' => __('Latest Articles Feed', true))
					)
			);
	}

	function commentTransformRss($data)
	{
		return
			array
			(
				'title' => $data['Comment']['name'],
				'link'  => array('controller' => 'articles', 'action' => 'view', $data['Article']['slug'].'/from:rss#comments'),
				'guid'  => array('controller' => 'articles', 'action' => 'view', $data['Article']['slug'].'/from:rss/cid:'.$data['Comment']['id']),
				'description' => $data['Comment']['comment'],
				'author' => '', // $data['Comment']['email'], // whoops...
				'pubDate' => $data['Comment']['created']
			);
	}

	function commentLink()
	{
		return $this->output
			(
				$this->Html->meta
					(
						'rss',
						'/comments.rss',
						array('title' => __('Latest Comments Feed', true))
					)
			);
	}

	function links()
	{
		return sprintf
			(
				'%s%s',
				$this->articleLink(),
				$this->commentLink()
			);
	}
}

?>
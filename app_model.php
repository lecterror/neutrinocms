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

class AppModel extends Model
{
	var $actsAs = array('Containable');

	/**
	 * Validation rule to see if we have too many keywords for meta tag.
	 *
	 * @param string $keywords
	 * @param int $limit
	 * @return bool
	 */
	function keywordLimit($keywords, $limit)
	{
		foreach ($keywords as $item)
		{
			$keywordsArray = split(',', $item);
			$keywordsCount = count($keywordsArray);

			if ($keywordsCount > $limit)
			{
				return false;
			}
		}

		return true;
	}

	function getOwner($slug, $currentUserId = null)
	{
		// special case for users, they have no user_id,
		// but they are owners of their own profiles etc..
		if ($this->name == 'User' && !empty($currentUserId))
		{
			$userId = Sanitize::escape($slug);

			if ($userId == $currentUserId)
			{
				return $currentUserId;
			}
		}

		if (!$this->hasField('slug') || !$this->hasField('user_id'))
		{
			return false;
		}

		$_slug = Sanitize::escape($slug);

		$result = $this->find
			(
				'first',
				array
				(
					'fields' => array('user_id'),
					'conditions' => array('slug' => $_slug),
					'recursive' => -1
				)
			);

		if (!$result)
		{
			return false;
		}

		return $result[$this->alias]['user_id'];
	}
}

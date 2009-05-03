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

class Rating extends AppModel
{
	var $name = 'Rating';
	var $actsAs =
		array
		(
			'Polymorphic' =>
				array
				(
					'classField' => 'class_name',
					'foreignKey' => 'foreign_id'
				)
		);

	var $validate =
		array
		(
			'class_name'	=>
				array
				(
					'rule'		=> 'alphaNumeric',
					'message'	=> 'Class name cannot be empty'
				),
			'foreign_id'	=>
				array
				(
					'rule'		=> VALID_NOT_EMPTY,
					'message'	=> 'Foreign id cannot be empty'
				),
			'rating'	=>
				array
				(
					'rule'		=> '/^[1-5]$/',
					'message'	=> 'Invalid rating value (1 to 5 only)'
				)
		);

	function afterFind($results, $primary = false)
	{
		if (!$primary)
		{
			$data = Set::extract('/Rating/rating', $results);

			if (!empty($data) && Set::numeric($data))
			{
				$total = 0;
				$votes = count($data);

				foreach ($data as $value)
					$total = $total + $value;

				$rating = round($total / $votes, 2);

				$results[0]['Rating']['Summary'] =
					array
					(
						'voted' => false, // ??
						'rating' => 0, // ??
						'totalVotes' => $votes,
						'totalRating' => $rating
					);
			}
		}

		return $results;
	}

	function setupVoted(&$data, $cookieVal)
	{
		$data['Rating']['Summary']['voted'] = true;
		$data['Rating']['Summary']['rating'] = $cookieVal;
	}
}

?>
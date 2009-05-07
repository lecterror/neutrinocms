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

class CoalesceBehavior extends ModelBehavior
{
	var $name = 'Coalesce';

	function setup(&$model, $config = array())
	{
		parent::setup($model, $config);

		$this->settings[$model->alias] = $config;
	}

	function cleanup(&$model)
	{
		parent::cleanup($model);

		unset($this->settings[$model->alias]);
	}

	function beforeFind(&$model, $query)
	{
		if (is_string($query['fields']))
		{
			$query['fields'] = array($query['fields']);
		}

		if (is_array($query['fields']))
		{
			foreach ($this->settings[$model->alias] as $columnAlias => $coalesceFields)
			{
				$index = array_search($columnAlias, $query['fields']);

				if ($index === false || is_null($index))
				{
					continue;
				}

				$escapedFields = array();

				foreach ($coalesceFields as $field)
				{
					$escapedFields[] = $model->escapeField($field);
				}

				$query['fields'][$index] =
					'COALESCE('.
					implode(', ', $escapedFields).
					') as '.
					$columnAlias;
			}
		}

		return $query;
	}

	function afterFind(&$model, $results, $primary)
	{
		if (empty($results))
		{
			return $results;
		}

		foreach ($results as $key => $item)
		{
			if (isset($item[0]) && is_array($item[0]))
			{
				foreach ($this->settings[$model->alias] as $columnAlias => $coalesceFields)
				{
					if (isset($item[0][$columnAlias]))
					{
						$item[$model->alias][$columnAlias] = $item[0][$columnAlias];
						unset($item[0][$columnAlias]);
					}
				}

				if (empty($item[0]))
				{
					unset($item[0]);
				}

				$results[$key] = $item;
			}
		}

		return $results;
	}
}

?>
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

class StatsController extends AppController
{
	var $name = 'Stats';
	var $uses = array('Article', 'Download');

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->deny('visits', 'downloads');
	}

	function visits()
	{
		$this->set('stats', $this->Article->getHitStats());
	}

	function downloads()
	{
		$this->set('stats', $this->Download->getDownloadStats());
	}
}

?>
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
 * This file is used to configure the basic behaviour of NeutrinoCMS.
 * It is also used to assist in internal configuration and install/upgrade operations.
 */

if (!defined('FAILED'))
	define('FAILED', -1);

if (!defined('SUCCESS'))
	define('SUCCESS', 0);

if (!defined('FILES'))
	define('FILES', CONFIGS.'files'.DS);

if (!defined('FILES_REL'))
	define('FILES_REL', 'config'.DS.'files'.DS);

class NEUTRINO_CONFIG
{
	var $availableThemes =
		array
		(
			'envision'		=> 'Envision',
			'blue-pigment'	=> 'Blue pigment'
		);

	var $defaultTheme = 'envision';

	var $initialConfiguration =
		array
		(
			'DateDisplayFormat'		=> 'd.m.Y.',
			'DatetimeDisplayFormat'	=> 'd.m.Y H:i:s',
			'Installed'				=> true
		);

	var $currentAppVersion	= '0.1-beta';
	var $requiredDbVersion	= '0.1-beta';

	var $dbMigration =
		array
		(
			'0.1-alpha' =>
				array
				(
					'install' => 'neutrino.initdb-0.1-alpha.sql'
				),
			'0.1-beta' =>
				array
				(
					'install' => 'neutrino.initdb-0.1-beta.sql',
					'0.1-alpha' => 'neutrino.migrate-0.1a--0.1b.sql'
				)
		);
}

?>
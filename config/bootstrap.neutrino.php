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

define('FAILED', -1);
define('SUCCESS', 0);

define('FILES', CONFIGS.'files'.DS);
define('FILES_REL', 'config'.DS.'files'.DS);

// @todo: remove
// define('MIGRATIONS', APP.'migrations'.DS);

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

	var $currentAppVersion	= '0.1-RC1';

	var $dbMigration =
		array
		(
			'0.1-beta'		=> '0000001',
			'0.1-RC1'		=> '0000005'
		);

	/**
	 * An array which defines action mapping for Auth/Acl.
	 */
	static $ACL_ACTION_MAP = array
		(
			'index'				=> 'read',
			'add'				=> 'create',
			'edit'				=> 'update',
			'view'				=> 'read',
			'remove'			=> 'delete',
			'get'				=> 'read',
			'visits'			=> 'read',
			'downloads'			=> 'read',
			'manage'			=> 'read', // @todo: rework attachments
			'configure'			=> 'update', // neutrino config
			'change_password'	=> 'update',
			'permissions'		=> 'update'
		);

	function features()
	{
		return array
			(
				__('Everything', true),
				__('ArticleCategories', true),
				__('Articles', true),
				__('Attachments', true),
				__('Comments', true),
				__('DownloadCategories', true),
				__('Downloads', true),
				__('Neutrino', true),
				__('Stats', true),
				__('Users', true)
			);
	}

	function requiredMigration()
	{
		return $this->dbMigration[$this->currentAppVersion];
	}
}

?>
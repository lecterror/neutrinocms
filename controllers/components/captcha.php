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

App::import('Vendor', 'phpcaptcha'.DS.'php-captcha');

class CaptchaComponent extends Object
{
	var $controller;

	function startup(&$controller)
	{
		$this->controller = &$controller;
	}

	function image()
	{
		$imagesPath = APP.'vendors'.DS.'phpcaptcha'.DS.'fonts'.DS;

		$aFonts = array
			(
				$imagesPath.'VeraBd.ttf',
				$imagesPath.'VeraIt.ttf',
				$imagesPath.'Vera.ttf'
			);

		$oVisualCaptcha = new PhpCaptcha($aFonts, 200, 60);
	//	$oVisualCaptcha->UseColour(true);
		$oVisualCaptcha->SetFileType('png');
		$oVisualCaptcha->SetOwnerText(Configure::read('Neutrino.CaptchaSidenote'));
		$oVisualCaptcha->SetNumChars(6);
		$oVisualCaptcha->Create();
	}

	function audio()
	{
		$oAudioCaptcha = new AudioPhpCaptcha('/usr/bin/flite', '/tmp/');
		$oAudioCaptcha->Create();
	}

	function check($userCode, $caseInsensitive = true)
	{
		if ($caseInsensitive)
		{
			$userCode = strtoupper($userCode);
		}

		if (!empty($_SESSION[CAPTCHA_SESSION_ID]) && $userCode == $_SESSION[CAPTCHA_SESSION_ID])
		{
			// clear to prevent re-use
			unset($_SESSION[CAPTCHA_SESSION_ID]);

			return true;
		}
		else
			return false;
	}

	function getCode()
	{
		return (isset($_SESSION[CAPTCHA_SESSION_ID]) ? $_SESSION[CAPTCHA_SESSION_ID] : false);
	}

	function clearCode()
	{
		if (isset($_SESSION[CAPTCHA_SESSION_ID]))
		{
			unset($_SESSION[CAPTCHA_SESSION_ID]);
		}
	}
}

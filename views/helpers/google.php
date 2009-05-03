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

class GoogleHelper extends AppHelper
{
	var $helpers = array('Html', 'Javascript');
	var $endl = "\n";

	function webmasterToolsVerificationCode($inline = true)
	{
		$code = Configure::read('Neutrino.GoogleWebmasterToolsVerificationCode');
		$tag = '';

		if (empty($code))
		{
			$tag = '';
		}
		else
		{
			$tag = $this->Html->meta(
					array('name' => 'verify-v1'),
					null,
					array(
						'content' => $code
					),
					$inline
				);
		}

		return $this->output($tag.$this->endl);
	}

	function analyticsTracker()
	{
		$accountCode = Configure::read('Neutrino.GoogleAnalyticsAccountCode');

		if (empty($accountCode))
		{
			return $this->output('');
		}

		$includeBlock = $this->Javascript->codeBlock
			(
				'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");'.$this->endl.
				'document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));'
			);

		$codeBlock = $this->Javascript->codeBlock
			(
				'try {'.$this->endl.
				'var pageTracker = _gat._getTracker("'.$accountCode.'");'.$this->endl.
				'pageTracker._initData();'.$this->endl.
				'pageTracker._trackPageview();'.$this->endl.
				'} catch(e) {}'
			);

		return $this->output($includeBlock.$this->endl.$codeBlock);
	}
}

?>
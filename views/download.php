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

App::import('Core', 'File');

class DownloadView extends View
{
	function __construct(&$controller)
	{
		parent::__construct($controller);
	}

	function render()
	{
		/* @var File */
		$file = null;
		/* @var File */
		$fakeFile = null;
		$fileName = null;
		$fileExt = null;
		$modified = null;
		extract($this->viewVars, EXTR_OVERWRITE);

		if (empty($file) || !$file->exists())
		{
			$this->log('The file to download is not set or does not exist!');
			return false;
		}

		if (connection_status() != 0)
		{
			$this->log('Connection error before downloading!');
			return false;
		}

		$chunkSize = 1 * (1024 * 8);
		$buffer = '';
		$fileSize = $file->size();
		$file->open('rb');

		if ($file->handle === false)
		{
			$this->log(sprintf('Could not open file for download (%s)!', $file->pwd()));
			return false;
		}

		if (isset($modified) && !empty($modified))
		{
			$modified = gmdate('D, d M Y H:i:s', strtotime($modified, time())) . ' GMT';
		}
		else
		{
			$modified = gmdate('D, d M Y H:i:s', $file->lastChange()).' GMT';
		}

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Last-Modified: $modified");

		$contentType = 'application/octet-stream';
		$agent = env('HTTP_USER_AGENT');

		if (preg_match('%Opera(/| )([0-9].[0-9]{1,2})%', $agent) || preg_match('/MSIE ([0-9].[0-9]{1,2})/', $agent))
		{
			$contentType = 'application/octetstream';
		}

		if (!empty($fakeFile))
		{
			$name = $fakeFile->name();
			$extension = $fakeFile->ext();
		}
		else if (!empty($fileName) || !empty($fileExt))
		{
			$name = strval($fileName);
			$extension = strval($fileExt);
		}
		else
		{
			$name = $file->name();
			$extension = $file->ext();
		}

		header('Content-Type: ' . $contentType);
		header("Content-Disposition: attachment; filename=\"".$name. (empty($extension) ? '' : '.'.$extension) ."\";");
		header("Expires: 0");
		header('Accept-Ranges: bytes');
		header("Cache-Control: private", false);
		header("Pragma: private");

		$httpRange = env('HTTP_RANGE');

		if (!isset($httpRange))
		{
			header("Content-Length: " . $fileSize);
		}
		else
		{
			list($toss, $range) = explode("=", $httpRange);
			str_replace($range, "-", $range);

			$size = $fileSize - 1;
			$length = $fileSize - $range;

			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $length");
			header("Content-Range: bytes $range$size/$fileSize");
			$file->offset($range);
		}

		@ob_end_clean();

		while (!feof($file->handle) && connection_status() == 0)
		{
			set_time_limit(0);
			$buffer = $file->read($chunkSize);
			echo $buffer;
			@flush();
			@ob_flush();
		}

		$file->close();
		return ((connection_status() == 0) && !connection_aborted());
	}
}
?>
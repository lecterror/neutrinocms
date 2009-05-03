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
App::import('Core', 'Folder');

class Attachment extends AppModel
{
	var $name = 'Attachment';
	var $useTable = false;

	function getAttachments()
	{
		$folder = new Folder(FILES);

		$filenames = $folder->read(true, true);
		$files = array();

		foreach ($filenames[1] as $filename)
		{
			$files[] = new File($filename);
		}

		return $files;
	}

	function validateUploadedFile($data)
	{
		$file = $data['Attachment']['file'];

		switch ($file['error'])
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$this->invalidate('file', 'Uploaded file is too big');
				return false;
			case UPLOAD_ERR_PARTIAL:
				$this->invalidate('file', 'File uploaded partially');
				return false;
			case UPLOAD_ERR_NO_FILE:
				$this->invalidate('file', 'No file uploaded');
				return false;
			case UPLOAD_ERR_NO_TMP_DIR:
				$this->invalidate('file', 'No temporary dir available');
				return false;
			case UPLOAD_ERR_CANT_WRITE:
				$this->invalidate('file', 'Cannot write to temporary dir');
				return false;
		}

		if (!is_uploaded_file($file['tmp_name']))
		{
			$this->invalidate('file', 'Security error: not an uploaded file!');
			return false;
		}

		if ($file['error'] === UPLOAD_ERR_OK)
			return true;

		$this->invalidate('file', 'Unknown error');
		return false;
	}

	function saveUploadedFile(&$data)
	{
		if (!isset($data['Attachment']['file']))
			return false;

		if (!move_uploaded_file(
			$data['Attachment']['file']['tmp_name'],
			FILES.$data['Attachment']['file']['name']))
			return false;

		return true;
	}
}
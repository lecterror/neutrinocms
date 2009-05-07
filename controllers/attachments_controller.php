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

class AttachmentsController extends AppController
{
	var $name = 'Attachments';
	var $uses = array('Attachment');
	var $helpers = array('Number');

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->deny('manage', 'add', 'delete');
	}

	function manage()
	{
		$files = $this->Attachment->getAttachments();

		$this->set(compact('files'));
	}

	// TODO: confirmation?
	function delete()
	{
		if (empty($this->data))
			$this->_redirectTo('manage', '');

		$flash = '';
		$deleted = 0;
		$failed = 0;

		foreach (array_values($this->data['Attachment']) as $filename)
		{
			if ($filename != '0')
			{
				$file = new File(FILES.$filename);

				if ($file->exists())
				{
					if ($file->delete())
					{
						$flash .= sprintf(__('%s deleted successfully<br />', true), $filename);
						$deleted++;
					}
					else
					{
						$flash .= sprintf(__('%s <b>not</b> deleted<br />', true), $filename);
						$failed++;
					}
				}
				else
				{
					$flash .= sprintf(__('%s not found<br />', true), $filename);
				}
			}
		}

		if ($deleted == 0)
		{
			$flash .= __('No files deleted!', true);
		}
		else if ($failed != 0)
		{
			$flash .= sprintf
				(
					__n
					(
						'Failed to delete %s file',
						'Failed to delete %s files',
						$failed,
						true
					),
					$failed
				);
		}

		$this->Session->setFlash($flash);
		$this->_redirectTo('manage', '');
	}

	function add()
	{
		if (!empty($this->data))
		{
			if (!$this->Attachment->validateUploadedFile($this->data))
				return;

			if ($this->Attachment->saveUploadedFile($this->data))
			{
				$this->Session->setFlash(__('File uploaded successfully', true));
				$this->_redirectTo('manage', '');
			}
			else
			{
				$this->Session->setFlash(__('Error while uploading file!', true));
			}
		}
	}
}

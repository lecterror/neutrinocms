<?php
$this->viewVars['content_description_for_layout'] = $download['Download']['content_description'];
$this->viewVars['content_keywords_for_layout'] = $download['Download']['content_keywords'];

$fullDownloadView = true;
echo $this->element('download', compact('download', 'fullDownloadView')); ?>
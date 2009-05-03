<?php
$this->log('controller: '.$controller.' action: '.$action.' file: '.$file, LOG_DEBUG);
$this->log('Missing view occured', LOG_DEBUG);
echo $this->element('weberror');
?>
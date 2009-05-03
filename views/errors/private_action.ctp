<?php
$this->log('controller: '.$controller.' action: '.$action, LOG_DEBUG);
$this->log('Private action occured', LOG_DEBUG);
echo $this->element('weberror');
?>
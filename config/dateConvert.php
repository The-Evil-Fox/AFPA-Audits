<?php

function dateConvert($date) {
    
    return (strftime('%d/%m/%Y à %H:%M:%S', strtotime($date)));
    
}

setlocale (LC_TIME, 'fr_FR.utf8','fra');

?>
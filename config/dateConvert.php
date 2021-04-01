<?php

// Convert a date/hour timestamp to the french format

function dateConvert($date) {
    
    return (strftime('%d/%m/%Y à %H:%M', strtotime($date)));
    
}

// Set the local timezone to France

setlocale (LC_TIME, 'fr_FR.utf8','fra');

?>
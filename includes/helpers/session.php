<?php
function updateSessionByLastView():void  {
    if (isset($_SESSION['tipo']) && !empty($_SESSION['tipo']) && isset($_SESSION['last_activity'])) {
         $_SESSION['last_activity'] = time(); 
    }
}
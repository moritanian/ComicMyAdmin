<?php
class MyTemplate
{
    function show($view)
    {
        $v = $this;
        include("View/Header.php"); 
        include("View/TopBar.php");
        include("View/{$view}.php");
    	include("View/Footer.php");
    }
}
?>
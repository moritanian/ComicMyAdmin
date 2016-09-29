<?php
class MyTemplate
{
    function show($view, $notLogin = false)
    {
        $v = $this;
        include("View/Header.php"); 
        if(!$notLogin){
           
            include("View/TopBar.php");
        }
        include("View/{$view}.php");
    	if(!$notLogin){
            include("View/Footer.php");
        }
    }

    public function h($str) {
    	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}
}
?>
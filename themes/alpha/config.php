<?php
View_Resource::addCssURI(url('resources/fonts/fonts.css'),-1000);
View_Resource::addJs('jquery-1.11.0.min.js',-1000);
View_Resource::addJs('html5.js',-900,array('condition'=>'IF IE'));
?>

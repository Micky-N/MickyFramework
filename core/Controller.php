<?php

namespace Core;

class Controller{
    
    protected function notFound(){
		header('HTTP/.htaccess.0 404 Not Found');
		die('Page introuvable');
	}
	
	protected function forbidden(){
		header('HTTP/.htaccess.0 403 Forbidden');
		die('Acces Interdit');
	}
}
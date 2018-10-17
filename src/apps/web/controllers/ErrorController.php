<?php
namespace EuroMillions\web\controllers;
use EuroMillions\shared\controllers\PublicSiteControllerBase;
class ErrorController extends PublicSiteControllerBase{
    
    public function page404Action(){
	$this->tag->prependTitle('Page Not Found');

    }



    public function page500Action(){
	$this->tag->prependTitle('Website Error');

    }

}

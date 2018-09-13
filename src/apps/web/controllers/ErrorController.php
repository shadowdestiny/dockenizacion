<?php
namespace EuroMillions\web\controllers;
class ErrorController extends PublicSiteControllerBase{
    
    public function page404Action(){
	$this->tag->prependTitle('Page Not Found');
	$this->response->setStatusCode(404, 'Not Found');

    }



    public function page500Action(){
	$this->tag->prependTitle('Website Error');

    }

}

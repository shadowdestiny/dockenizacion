<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;

class LegalController extends PublicSiteControllerBase{
    public function indexAction(){
	
	$this->tag->prependTitle('Terms and Conditions');
	MetaDescriptionTag::setDescription('Read carrefully the Terms and Conditions of EuroMillions.com to play the EuroMillions Lottery online. Our customer support team will be happy to answer eventual questions you may have.');
    }
   
    public function privacyAction(){
	
	$this->tag->prependTitle('Privacy Policy');
	MetaDescriptionTag::setDescription('EuroMillions.com is the sole owner of the information collected at various points on this website. We will not sell, share, or rent this information to third parties.');

    }
    
    public function aboutAction(){
	$this->tag->prependTitle('About Us');

    }

    public function cookiesAction(){

	$this->tag->prependTitle('Cookies');
	MetaDescriptionTag::setDescription('A cookie is a small piece of text passed to your browser by a website you visit. It helps the website to remember information about your visit.');

    }
}

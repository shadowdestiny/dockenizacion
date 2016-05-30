<?php


namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;

class HelpController extends PublicSiteControllerBase
{

    public function indexAction()
    {
	$this->tag->prependTitle('Help: How to Play Euromillions online');
	MetaDescriptionTag::setDescription('EuroMillions.com offers you now the possibiliy to play the EuroMillions lottery worldwide from the palm of your hand!');
    }
}

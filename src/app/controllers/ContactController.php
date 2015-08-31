<?php
namespace EuroMillions\controllers;

class ContactController extends PublicSiteControllerBase
{
    public function guestAction()
    {
        $errors = null;
        $contact_form = new ContactForm();

        //EMTD to be completed by Raul (example to follow: UserAccess/signUp )
        //Tip: create a value object ContactFormInfo
    }

    public function registeredAction()
    {
        //EMTD to be completed by Raul
    }
}
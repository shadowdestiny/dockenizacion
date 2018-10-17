<?php
namespace EuroMillions\web\controllers\ajax;

use EuroMillions\web\controllers\ControllerBase;

class AjaxControllerBase extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->noRender();
    }
}
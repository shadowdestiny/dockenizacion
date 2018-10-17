<?php
namespace EuroMillions\web\controllers\ajax;

use EuroMillions\shared\controllers\ControllerBase;

class AjaxControllerBase extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->noRender();
    }
}
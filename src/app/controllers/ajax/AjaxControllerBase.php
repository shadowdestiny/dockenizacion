<?php
namespace EuroMillions\controllers\ajax;

use EuroMillions\controllers\ControllerBase;

class AjaxControllerBase extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->noRender();
    }
}
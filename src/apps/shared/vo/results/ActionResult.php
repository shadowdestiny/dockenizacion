<?php
namespace EuroMillions\shared\vo\results;

class ActionResult extends ResultBase
{
    public function __construct($success, $returnValues = null)
    {
        $this->success = $success;
        if ($success) {
            parent::__construct($success, $returnValues, null);
        } else {
            parent::__construct($success, null, $returnValues);
        }
    }

    public function getValues()
    {
        return $this->returnValues();
    }
}

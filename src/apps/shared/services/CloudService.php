<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 28/06/18
 * Time: 17:21
 */

namespace EuroMillions\shared\services;


use EuroMillions\shared\interfaces\ICloud;

class CloudService
{

    protected $cloud;

    public function __construct(ICloud $cloud)
    {
        $this->cloud= $cloud;
    }

    public function cloud()
    {
        return $this->cloud;
    }

}
<?php


namespace EuroMillions\forms;


class EMForm
{
    public static function className()
    {
        return get_class();
    }

    public static function getClass()
    {
        static::className();
    }


}
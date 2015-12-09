<?php


namespace apps\components\widgets;


class WidgetsManager
{
    public static function get($widgetClass , $parameters = null)
    {
        return new $widgetClass($parameters);
    }

}
<?php


namespace EuroMillions\web\components\tags;


use Phalcon\Tag;

class MetaDescriptionTag extends Tag
{

    protected static $_documentDescription = '';

    /**
     * Set meta description for current document
     *
     * @param string $description
     */
    public static function setDescription($description) {
        self::$_documentDescription = $description;
    }

    /**
     * Get current document meta description
     *
     * @param boolean $tags
     * @return string
     */
    public static function getDescription($tags = true) {
        if ($tags) {
            return '<meta name="description" content="'. self::$_documentDescription .'">';
        }
        return self::$_documentDescription;
    }
}
<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;

class Article extends EntityBase implements IEntity
{

    protected $id;
    protected $content;

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
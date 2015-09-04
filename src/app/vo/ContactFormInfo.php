<?php


namespace EuroMillions\vo;


class ContactFormInfo
{

    protected $email;
    protected $fullName;
    protected $content;
    protected $topic;

    public function __construct(Email $email, $fullName, $content, $topic)
    {
        $this->email=$email;
        $this->fullName=$fullName;
        $this->content=$content;
        $this->topic=$topic;
    }


    public function setEmail(Email $email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFullName($fullName)
    {
        $this->fullName=$fullName;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setTopic($topic)
    {
        $this->topic=$topic;
    }

    public function getTopic()
    {
        return $this->topic;
    }
}
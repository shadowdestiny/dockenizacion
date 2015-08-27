<?php
namespace EuroMillions\vo;

use Assert\Assertion;
use EuroMillions\interfaces\IValueObject;
use EuroMillions\vo\base\Domain;
use EuroMillions\vo\base\ValueObject;

final class Url extends ValueObject
{
    /** @var SchemeName */
    private $scheme;
    /** @var Domain */
    private $domain;
    private $path;
    private $port;
    private $queryString;

    public function __construct($urlString)
    {
        if (false === strpos($urlString, '//localhost')) {
            Assertion::url($urlString);
        }
        $this->scheme = new SchemeName(\parse_url($urlString, PHP_URL_SCHEME));
        $this->domain = Domain::specifyType(\parse_url($urlString, PHP_URL_HOST));
        $this->path = new Path(\parse_url($urlString, PHP_URL_PATH));
        $port = \parse_url($urlString, PHP_URL_PORT);
        $this->port = $port ? new PortNumber($port) : new NullPortNumber();
        $query_string = \parse_url($urlString, PHP_URL_QUERY);
        $this->queryString = $query_string ? new QueryString(\sprintf('?%s', $query_string)) : new NullQueryString();
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Returns a object taking PHP native value(s) as argument(s).
     *
     * @return IValueObject
     */
    public static function fromNative()
    {
        return new static(\func_get_arg(0));
    }

    /**
     * Returns a native PHP value
     *
     * @return mixed
     */
    public function toNative()
    {
        $port = '';
        $null_port = new NullPortNumber();
        if (!$null_port->sameValueAs($this->port)) {
            $port = \sprintf(':%d', $this->port->toNative());
        }
        $urlString = \sprintf('%s://%s%s%s%s', $this->scheme, $this->domain->toNative(), $port, $this->path->toNative(), $this->queryString->toNative());
        return $urlString;
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toNative();
    }
}
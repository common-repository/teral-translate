<?php

namespace Teral\Translate\Lib\Util;

/**
 * Class Url
 */
class Url
{

    private $parsed;
    private $url;
    private $scheme;
    private $host;
    private $port;
    private $path;
    private $query;
    private $fragment;

    public function __construct($url) {
        $this->url = $url;

        $this->parsed = parse_url($url);

        $this->scheme   = $this->parsed['scheme'];
        $this->host     = $this->parsed['host'];
        $this->port     = isset($this->parsed['port']) ? $this->parsed['port'] : null;
        $this->path     = $this->parsed['path'];
        $this->query    = isset($this->parsed['query']) ? $this->parsed['query'] : '';
        $this->fragment = isset($this->parsed['fragment']) ? $this->parsed['fragment'] : null;
    }

    /**
     * @return string
     */
    public function get() {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return string
     */
    public function geQuery() {
        return $this->query;
    }

    /**
     * @return string
     */
    public function geFragment() {
        return $this->fragment;
    }

    /**
     * @return array
     */
    public function toArray(){
        return $this->parsed;
    }
}

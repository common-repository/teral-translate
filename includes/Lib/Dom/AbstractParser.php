<?php

namespace Teral\Translate\Lib\Dom\Selector;

abstract class AbstractParser
{

    protected $dom;

    public function __construct($dom)
    {
        $this->setDom($dom);
    }

    public function setDom($dom)
    {
        $this->dom = $dom;

        return $this;
    }

    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @return mixed
     */
    abstract public function handle();
}

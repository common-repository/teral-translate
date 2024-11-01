<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;
use Teral\Translate\Lib\Util\Text;

/**
 * Class AbstractChecker
 */
abstract class AbstractSelector
{
    /**
     * DOM node to match
     *
     * @var string
     */
    const DOM = '';

    /**
     * DOM property to get
     *
     * @var string
     */
    const PROPERTY = '';

    /**
     * Type of content returned by DOM property
     *
     * @var string
     */
    const NODE_TYPE = NodeType::TEXT;

    /**
	 * Need to escape DOM attribute
	 *
	 * @var bool
	 */
	const ESCAPE_SPECIAL_CHAR = false;

    /**
     * @var
     */
    protected $node;

    /**
     * @var string
     */
    protected $property;

    /**
     * AbstractChecker constructor.
     * @param $node
     * @param string $property
     */
    public function __construct($node, $property)
    {
        $this
            ->setNode($node)
            ->setProperty($property);
    }

    /**
     * @param $node
     * @return $this
     */
    public function setNode($node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * @return
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param string $property
     * @return $this
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return bool
     */
    public function handle()
    {
        return $this->defaultCheck() && $this->check();
    }

    /**
     * @return bool
     */
    protected function defaultCheck()
    {
        $property = $this->property;

        return (
            Text::fullTrim($this->node->$property) != '' &&
            !$this->node->hasAncestorAttribute("data-tl-ignore")
        );
    }

    /**
     * @return bool
     */
    public function isValidNode() {
        return $this->defaultCheck();
    }

    /**
     * @return bool
     */
    protected function check()
    {
        return true;
    }

    /**
     * @return array
     */
    public static function toArray()
    {
        $class = \get_called_class();

        return [
            $class::DOM,
            $class::PROPERTY,
            $class::NODE_TYPE
        ];
    }
}

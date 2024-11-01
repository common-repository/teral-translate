<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class LinkDataHover
 */
class LinkDataHover extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-hover';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::TEXT;
}

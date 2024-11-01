<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class LinkDataTitle
 */
class LinkDataTitle extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = '*';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-title';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::TEXT;
}

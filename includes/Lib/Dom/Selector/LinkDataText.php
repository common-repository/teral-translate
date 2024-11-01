<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class LinkDataText
 */
class LinkDataText extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-text';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::TEXT;
}

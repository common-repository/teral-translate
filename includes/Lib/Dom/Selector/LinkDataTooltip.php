<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class LinkDataTooltip
 */
class LinkDataTooltip extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-tooltip';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::TEXT;
}

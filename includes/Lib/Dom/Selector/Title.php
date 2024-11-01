<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class Title
 */
class Title extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = '[title]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'title';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::TEXT;
}

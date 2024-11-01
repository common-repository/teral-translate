<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class TdDataTitle
 */
class TdDataTitle extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'td';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-title';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::VALUE;
}

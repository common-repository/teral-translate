<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\Text as TextUtil;
use Teral\Translate\Lib\Util\NodeType;

/**
 * Class LinkHref
 */
class LinkHref extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'href';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::OTHER;


    /**
     * {@inheritdoc}
     */
    protected function check()
    {
        return false;
    }
}

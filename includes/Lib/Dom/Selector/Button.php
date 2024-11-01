<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\Text as TextUtil;
use Teral\Translate\Lib\Util\NodeType;

/**
 * Class Button
 */
class Button extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'input[type="submit"],input[type="button"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'value';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::VALUE;

    /**
     * {@inheritdoc}
     */
    protected function check()
    {
        return (!is_numeric(TextUtil::fullTrim($this->node->value))
            && !preg_match('/^\d+%$/', TextUtil::fullTrim($this->node->value)));
    }
}

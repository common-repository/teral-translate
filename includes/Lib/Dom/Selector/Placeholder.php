<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;
use Teral\Translate\Lib\Util\Text as TextUtil;

/**
 * Class Placeholder
 */
class Placeholder extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'input[type="text"],input[type="password"],input[type="search"],input[type="email"],textarea, input[type="tel"], input[type="number"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'placeholder';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::PLACEHOLDER;

    /**
     * {@inheritdoc}
     */
    protected function check()
    {
        return (!is_numeric(TextUtil::fullTrim($this->node->placeholder))
            && !preg_match('/^\d+%$/', TextUtil::fullTrim($this->node->placeholder)));
    }
}

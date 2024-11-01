<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class InputButtonOrderText
 */
class InputButtonOrderText extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'input[type="submit"],input[type="button"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-order_button_text';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::TEXT;
}

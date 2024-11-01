<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class InputRadioOrderText
 */
class InputRadioOrderText extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'input[type="radio"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-order_button_text';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::VALUE;
}

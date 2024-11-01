<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\Text as TextUtil;
use Teral\Translate\Lib\Util\NodeType;

/**
 * Class Text
 */
class Text extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'text';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'outertext';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::TEXT;

    /**
     * {@inheritdoc}
     */
    protected function check()
    {
        return ($this->node->parent()->tag != 'script'
            && $this->node->parent()->tag != 'style'
            && $this->node->parent()->tag != 'noscript'
            && $this->node->parent()->tag != 'code'
            && !is_numeric(TextUtil::fullTrim($this->node->outertext))
            && !preg_match('/^\d+%$/', TextUtil::fullTrim($this->node->outertext))
            && strpos($this->node->outertext, '<?php') === false);
    }
}

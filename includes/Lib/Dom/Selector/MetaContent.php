<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;
use Teral\Translate\Lib\Util\Text as TextUtil;

/**
 * Class MetaContent
 */
class MetaContent extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'meta[name="description"],meta[property="og:title"],meta[property="og:description"],meta[property="og:site_name"],meta[name="twitter:title"],meta[name="twitter:description"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'content';

    /**
     * {@inheritdoc}
     */
    const NODE_TYPE = NodeType::META_CONTENT;

}

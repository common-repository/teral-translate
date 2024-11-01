<?php

namespace Teral\Translate\Lib\Dom\Selector;

use Teral\Translate\Lib\Util\NodeType;

/**
 * Class ImageAlt
 */
class ImageAlt extends AbstractSelector
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'img';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'alt';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = NodeType::IMG_ALT;

    /**
	 * {@inheritdoc}
	 */
	const ESCAPE_SPECIAL_CHAR =true;
}

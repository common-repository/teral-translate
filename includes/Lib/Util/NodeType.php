<?php

namespace Teral\Translate\Lib\Util;

/**
 * Class NodeType
 * Used to define where was the text we are parsing
 */
abstract class NodeType
{
    const OTHER = 0;
    const TEXT = 1;
    const VALUE = 2;
    const PLACEHOLDER = 3;
    const META_CONTENT = 4;
    const IFRAME_SRC = 5;
    const IMG_SRC = 6;
    const IMG_ALT = 7;
    const PDF_HREF = 8;
}

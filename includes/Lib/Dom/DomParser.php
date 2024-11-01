<?php

namespace Teral\Translate\Lib\Dom;

use Teral\Translate\Lib\Dom\Selector\AbstractDomSelector;
use Teral\Translate\Lib\Dom\Selector\LinkHref;
use Teral\Translate\Lib\Util\Text;
use Teral\Translate\Lib\Util\NodeProperty;

/**
 * Class domParser
 */
class DomParser
{
    const ATTRIBUTE_NO_TRANSLATE = 'data-tl-ignore';

    const ATTRIBUTE_TRANSLATED = 'data-tl-translated';

    const SELECTORS_NAMESPACE = '\\Teral\\Translate\\Lib\\Dom\\Selector\\';

    /**
     * @var array
     */
    protected $selectors = [];

    /**
     * @var array
     */
    protected $nodesCache = [];

    /**
     * @var Dom
     */
    protected $dom;

    /**
     * @var array
     */
    protected $uniqueWords = [];

    /**
     * @var array
     */
    protected $nodes = [];

    /**
     * DomChecker constructor.
     * @param
     */
    public function __construct()
    {
        $this->loadDefaultSelectors();
    }

    /**
     * @param array $selectors
     * @return $this
     */
    public function addSelectors(array $selectors)
    {
        $this->selectors = array_merge($this->selectors, $selectors);

        return $this;
    }

    /**
     * @return array
     */
    public function getSelectors()
    {
        return $this->selectors;
    }

    /**
     * @param $domToSearch
     * @param $dom
     * @return
     */
    public function findNodes($domToSearch, $dom)
    {
        if (!isset($nodesCache[$domToSearch])) {
            $this->nodesCache[$domToSearch] = $dom->find($domToSearch);
        }

        return $this->nodesCache[$domToSearch];
    }

    /**
     * Load default checkers
     */
    protected function loadDefaultSelectors()
    {
        $files = array_diff(scandir(__DIR__ . '/Selector'), ['AbstractSelector.php', '..', '.']);

        $selectors = array_map(function ($filename) {
            return self::SELECTORS_NAMESPACE . Text::removeFileExtension($filename);
        }, $files);

        $this->addSelectors($selectors);
    }

    /**
     * @param $dom
     * @return array
     * @throws Exception
     */
    public function process($dom, $excludedBlocks)
    {
        $this->dom = $dom;

        // exclude blocks
        if (!empty($excludedBlocks)) {
            $this->processExcludeBlock($excludedBlocks);
        }

        $nodes = [];
        $selectors = $this->getSelectors();

        foreach ($selectors as $class) {
            list($selector, $property, $nodeType) = $class::toArray();

            $foundNodes = $this->findNodes($selector, $this->dom);

            foreach ($foundNodes as $i => $node) {

                $instance = new $class($node, $property);

                if ($instance->handle()) {
                    $word = html_entity_decode(trim($node->$property), ENT_COMPAT, 'UTF-8');

                    $this->uniqueWords[$word] = 1;

                    $this->nodes[] = [
                        'type' => $nodeType,
                        'node' => $node,
                        'class' => $class,
                        'property' => $property,
                        'value' => $word
                    ];
                }
            }
        }

        return $this->nodes;
    }

    public function processExcludeBlock($excludedBlocks) {

        foreach ($excludedBlocks as $excludeSelector) {
            foreach ($this->dom->find($excludeSelector) as $i => $row) {
                $attribute = self::ATTRIBUTE_NO_TRANSLATE;
                $row->$attribute = '';
            }
        }
    }

    /**
     * @param $translated_words
     */
    public function setTranslation($translated_words) {

        if ($translated_words && \sizeof($translated_words) > 0) {
            // code...
            for ($i = 0; $i < \count($this->nodes); ++$i) {
                $currentNode = $this->nodes[$i];

                $value = $currentNode['value'];

                if ($translated_words[$value] !== null) {

                    $currentTranslated = $translated_words[$value];

                    $this->updateNode($currentNode, $currentTranslated);
                }
            }
        }
    }

    /**
     * @param $translated_words
     */
    public function updateRoot($currentLanguage) {

        $nodes = $this->dom->find('html');

        if($nodes && sizeof($nodes) > 0) {

            $langAttribute = NodeProperty::LANG;
            $translatedAttribute = self::ATTRIBUTE_TRANSLATED;

            $nodes[0]->$langAttribute = $currentLanguage;
            $nodes[0]->$translatedAttribute = 1;
        }
    }

    /**
     * @param $targetLanguage
     */
    public function updateCanonicalLink($url, $targetLanguage) {
        $nodes = $this->dom->find('link[rel="canonical"]');

        if($nodes && sizeof($nodes) > 0) {

            $attribute = NodeProperty::HREF;

            $canonicalLink = $nodes[0]->$attribute;

            $urlWithLang = teral_get_url_with_language(parse_url($canonicalLink), $targetLanguage);

            if ($urlWithLang) {
                $nodes[0]->$attribute = $urlWithLang;
            }
        }
    }

    /**
     * @param $details
     * @param $translatedText
     */
    protected function updateNode(array $details, $translatedText) {
        $property = $details['property'];

        $value = trim($details['node']->{$property});

        $details['node']->$property = str_replace($value, $translatedText, $details['node']->$property);
    }

    /**
     * @return array
     */
    public function getUniqueWords() {
        return array_keys($this->uniqueWords);
    }

    /**
     * @return array
     */
    public function getSource() {
        return $this->dom;
    }

    /**
     * @param $host
     * @param $targetLanguage
     */
    public function replaceLinks($host, $targetLanguage) {
        list($selector, $property, $nodeType) = LinkHref::toArray();

        $foundNodes = $this->findNodes($selector, $this->dom);

        foreach ($foundNodes as $k => $node) {

            $instance = new LinkHref($node, $property);

            if ($instance->isValidNode()) {
              $href = $node->$property;

              $parts = parse_url($href);

              $isValidHost = isset($parts['host']) && $parts['host'] == $host;

              if ($parts && $isValidHost && !teral_is_wordpress_path($parts['path'])) {

                  $href = teral_get_url_with_language($parts, $targetLanguage);

                  $node->$property = $href;
              }
            }
        }

    }
}

<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Lists;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-coverage-for-attributes.html.
class DOMTokenListCoverageForAttributesTest extends WPTTestHarness
{
    public function helperTestAttr($pair, $new_el)
    {
        return $pair['attr'] === 'classList' || $pair['attr'] === 'relList' && $new_el->localName === 'a' && $new_el->namespaceURI === 'http://www.w3.org/2000/svg' || $new_el->namespaceURI === 'http://www.w3.org/1999/xhtml' && array_search($new_el->localName, $pair->sup) != -1;
    }
    public function testDOMTokenListCoverageForAttributes()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-coverage-for-attributes.html');
        $pairs = [
            // Defined in DOM
            ['attr' => 'classList', 'sup' => ['anyElement']],
            // Defined in HTML except for a which is also SVG
            ['attr' => 'relList', 'sup' => ['a', 'area', 'link']],
            // Defined in HTML
            ['attr' => 'htmlFor', 'sup' => ['output']],
            ['attr' => 'sandbox', 'sup' => ['iframe']],
            ['attr' => 'sizes', 'sup' => ['link']],
        ];
        $namespaces = ['http://www.w3.org/1999/xhtml', 'http://www.w3.org/2000/svg', 'http://www.w3.org/1998/Math/MathML', 'http://example.com/', ''];
        $elements = ['a', 'area', 'link', 'iframe', 'output', 'td', 'th'];
        foreach ($pairs as $pair) {
            foreach ($namespaces as $ns) {
                foreach ($elements as $el) {
                    $new_el = $this->doc->createElementNS($ns, $el);
                    if ($this->helperTestAttr($pair, $new_el)) {
                        $this->assertTest(function () use(&$new_el, &$pair) {
                            $this->wptAssertClassString($new_el->{$pair['attr']}, 'DOMTokenList');
                        }, $new_el->localName . '.' . $pair['attr'] . ' in ' . $new_el->namespaceURI . ' namespace should be DOMTokenList.');
                    } else {
                        $this->assertTest(function () use(&$new_el, &$pair) {
                            $this->wptAssertEquals($new_el->{$pair['attr']}, null);
                        }, $new_el->localName . '.' . $pair['attr'] . ' in ' . $new_el->namespaceURI . ' namespace should be undefined.');
                    }
                }
            }
        }
    }
}

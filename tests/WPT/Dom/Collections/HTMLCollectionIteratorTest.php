<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Collections;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-iterator.html.
class HTMLCollectionIteratorTest extends WPTTestHarness
{
    public function testHTMLCollectionIterator()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-iterator.html');
        $paragraphs = $this->doc->getElementsByTagName('p');
        $this->assertTest(function () use(&$paragraphs) {
            $this->wptAssertTrue(isset($paragraphs['length']));
        }, 'HTMLCollection has length method.');
        $this->assertTest(function () use(&$paragraphs) {
            $this->wptAssertFalse(isset($paragraphs['values']));
        }, "HTMLCollection does not have iterable's values method.");
        $this->assertTest(function () use(&$paragraphs) {
            $this->wptAssertFalse(isset($paragraphs['entries']));
        }, "HTMLCollection does not have iterable's entries method.");
        $this->assertTest(function () use(&$paragraphs) {
            $this->wptAssertFalse(isset($paragraphs['forEach']));
        }, "HTMLCollection does not have iterable's forEach method.");
        $this->assertTest(function () use(&$paragraphs) {
            $this->wptAssertTrue(isset($paragraphs[Symbol::iterator]));
        }, 'HTMLCollection has Symbol.iterator.');
        $this->assertTest(function () use(&$paragraphs) {
            $ids = '12345';
            $idx = 0;
            foreach ($paragraphs as $element) {
                $this->wptAssertEquals($element->getAttribute('id'), $ids[$idx++]);
            }
        }, 'HTMLCollection is iterable via for-of loop.');
    }
}

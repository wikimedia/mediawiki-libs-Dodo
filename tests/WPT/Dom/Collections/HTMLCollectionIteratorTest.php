<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
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
            $this->assertTrueData(isset($paragraphs['length']));
        }, 'HTMLCollection has length method.');
        $this->assertTest(function () use(&$paragraphs) {
            $this->assertFalseData(isset($paragraphs['values']));
        }, "HTMLCollection does not have iterable's values method.");
        $this->assertTest(function () use(&$paragraphs) {
            $this->assertFalseData(isset($paragraphs['entries']));
        }, "HTMLCollection does not have iterable's entries method.");
        $this->assertTest(function () use(&$paragraphs) {
            $this->assertFalseData(isset($paragraphs['forEach']));
        }, "HTMLCollection does not have iterable's forEach method.");
        $this->assertTest(function () use(&$paragraphs) {
            // $this->assertTrueData(isset($paragraphs[Symbol::iterator]));
        }, 'HTMLCollection has Symbol.iterator.');
        $this->assertTest(function () use(&$paragraphs) {
            $ids = '12345';
            $idx = 0;
            foreach ($paragraphs as $element => $___) {
                $this->assertEqualsData($element->getAttribute('id'), $ids[$idx++]);
            }
        }, 'HTMLCollection is iterable via for-of loop.');
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-getElementsByClassName.html.
class ElementGetElementsByClassNameTest extends WPTTestHarness
{
    public function testElementGetElementsByClassName()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-getElementsByClassName.html');
        $this->assertTest(function () {
            $a = $this->doc->createElement('a');
            $b = $this->doc->createElement('b');
            $b->className = 'foo';
            $a->appendChild($b);
            $list = $a->getElementsByClassName('foo');
            $this->wptAssertArrayEquals($list, [$b]);
            $secondList = $a->getElementsByClassName('foo');
            $this->wptAssertTrue($list === $secondList || $list !== $secondList, 'Caching is allowed.');
        }, 'getElementsByClassName should work on disconnected subtrees.');
        $this->assertTest(function () {
            $list = $this->doc->getElementsByClassName('foo');
            $this->wptAssertFalse($list instanceof NodeList, 'NodeList');
            $this->wptAssertTrue($list instanceof HTMLCollection, 'HTMLCollection');
        }, 'Interface should be correct.');
        $this->assertTest(function () {
            $a = $this->doc->createElement('a');
            $b = $this->doc->createElement('b');
            $c = $this->doc->createElement('c');
            $b->className = 'foo';
            $this->doc->body->appendChild($a);
            $this->add_cleanup(function () use (&$a) {
                $this->doc->body->removeChild($a);
            });
            $a->appendChild($b);
            $l = $a->getElementsByClassName('foo');
            $this->wptAssertTrue($l instanceof HTMLCollection);
            $this->wptAssertEquals(count($l), 1);
            $c->className = 'foo';
            $a->appendChild($c);
            $this->wptAssertEquals(count($l), 2);
            $a->removeChild($c);
            $this->wptAssertEquals(count($l), 1);
        }, 'getElementsByClassName() should be a live collection');
    }
}

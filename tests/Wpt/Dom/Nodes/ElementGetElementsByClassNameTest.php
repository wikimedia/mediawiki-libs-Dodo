<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-getElementsByClassName.html.
class ElementGetElementsByClassNameTest extends WptTestHarness
{
    public function testElementGetElementsByClassName()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Element-getElementsByClassName.html';
        $this->assertTest(function () {
            $a = $this->doc->createElement('a');
            $b = $this->doc->createElement('b');
            $b->className = 'foo';
            $a->appendChild($b);
            $list = $a->getElementsByClassName('foo');
            $this->assertArrayEqualsData($list, [$b]);
            $secondList = $a->getElementsByClassName('foo');
            $this->assertTrueData($list === $secondList || $list !== $secondList, 'Caching is allowed.');
        }, 'getElementsByClassName should work on disconnected subtrees.');
        $this->assertTest(function () {
            $list = $this->doc->getElementsByClassName('foo');
            $this->assertFalseData($list instanceof NodeList, 'NodeList');
            $this->assertTrueData($list instanceof HTMLCollection, 'HTMLCollection');
        }, 'Interface should be correct.');
        $this->assertTest(function () {
            $a = $this->doc->createElement('a');
            $b = $this->doc->createElement('b');
            $c = $this->doc->createElement('c');
            $b->className = 'foo';
            $this->doc->body->appendChild($a);
            $this->{$this}->addCleanup(function () use(&$a) {
                $this->doc->body->removeChild($a);
            });
            $a->appendChild($b);
            $l = $a->getElementsByClassName('foo');
            $this->assertTrueData($l instanceof HTMLCollection);
            $this->assertEqualsData(count($l), 1);
            $c->className = 'foo';
            $a->appendChild($c);
            $this->assertEqualsData(count($l), 2);
            $a->removeChild($c);
            $this->assertEqualsData(count($l), 1);
        }, 'getElementsByClassName() should be a live collection');
    }
}

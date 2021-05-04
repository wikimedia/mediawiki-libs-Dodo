<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/NodeList-Iterable.html.
class NodeListIterableTest extends WptTestHarness
{
    public function testNodeListIterable()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/NodeList-Iterable.html');
        $paragraphs = null;
        // setup()
        $paragraphs = $this->doc->querySelectorAll('p');
        $this->assertTest(function () use(&$paragraphs) {
            $this->assertTrueData(isset($paragraphs['length']));
        }, 'NodeList has length method.');
        $this->assertTest(function () use(&$paragraphs) {
            $this->assertTrueData(isset($paragraphs['values']));
        }, 'NodeList has values method.');
        $this->assertTest(function () use(&$paragraphs) {
            $this->assertTrueData(isset($paragraphs['entries']));
        }, 'NodeList has entries method.');
        $this->assertTest(function () use(&$paragraphs) {
            $this->assertTrueData(isset($paragraphs['forEach']));
        }, 'NodeList has forEach method.');
        $this->assertTest(function () use(&$paragraphs) {
            // $this->assertTrueData(isset($paragraphs[Symbol::iterator]));
        }, 'NodeList has Symbol.iterator.');
        $this->assertTest(function () use(&$paragraphs) {
            $ids = '12345';
            $idx = 0;
            foreach ($paragraphs as $node => $___) {
                $this->assertEqualsData($node->getAttribute('id'), $ids[$idx++]);
            }
        }, 'NodeList is iterable via for-of loop.');
        $this->assertTest(function () use(&$paragraphs) {
            $this->assertArrayEqualsData(get_object_vars($paragraphs), ['0', '1', '2', '3', '4']);
        }, 'NodeList responds to Object.keys correctly');
        $this->assertTest(function () {
            $container = $this->doc->getElementById('live');
            $nodeList = $container->childNodes;
            $ids = [];
            foreach ($nodeList as $el => $___) {
                $ids[] = $el->id;
                $this->assertEqualsData($el->localName, 'b');
                if (count($ids) < 3) {
                    $newEl = $this->doc->createElement('b');
                    $newEl->id = 'after' . $el->id;
                    $container->appendChild($newEl);
                }
            }
            $this->assertArrayEqualsData($ids, ['b1', 'b2', 'b3', 'afterb1', 'afterb2']);
        }, 'live NodeLists are for-of iterable and update appropriately');
    }
}

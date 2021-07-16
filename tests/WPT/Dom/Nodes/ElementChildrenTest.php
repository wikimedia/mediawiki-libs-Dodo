<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-children.html.
class ElementChildrenTest extends WPTTestHarness
{
    public function testElementChildren()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-children.html');
        // Add some non-HTML elements in there to test what happens with those.
        // setup()
        $container = $this->doc->getElementById('test');
        $child = $this->doc->createElementNS('', 'img');
        $child->setAttribute('id', 'baz');
        $container->appendChild($child);
        $child = $this->doc->createElementNS('', 'img');
        $child->setAttribute('name', 'qux');
        $container->appendChild($child);
        $this->assertTest(function () {
            $container = $this->doc->getElementById('test');
            $result = $container->children->item('foo');
            $this->wptAssertTrue($result instanceof Element, 'Expected an Element.');
            $this->wptAssertFalse($result->hasAttribute('id'), 'Expected the IDless Element.');
        });
        $this->assertTest(function () {
            $container = $this->doc->getElementById('test');
            $list = $container->children;
            $result = [];
            foreach ($list as $p) {
                if ($list->hasOwnProperty($p)) {
                    $result[] = $p;
                }
            }
            $this->wptAssertArrayEquals($result, ['0', '1', '2', '3', '4', '5']);
            $result = $this->getOwnPropertyNames($list);
            $this->wptAssertArrayEquals($result, ['0', '1', '2', '3', '4', '5', 'foo', 'bar', 'baz']);
            // Mapping of exposed names to their indices in the list.
            $exposedNames = ['foo' => 1, 'bar' => 3, 'baz' => 4];
            foreach ($exposedNames as $exposedName) {
                $this->wptAssertTrue(isset($list[$exposedName]));
                $this->wptAssertTrue($list->hasOwnProperty($exposedName));
                $this->wptAssertEquals($list[$exposedName], $list->namedItem($exposedName));
                $this->wptAssertEquals($list[$exposedName], $list->item($exposedNames[$exposedName]));
                $this->wptAssertTrue($list[$exposedName] instanceof Element);
            }
            $unexposedNames = ['qux'];
            foreach ($unexposedNames as $unexposedName) {
                $this->wptAssertFalse(isset($list[$unexposedName]));
                $this->wptAssertFalse($list->hasOwnProperty($unexposedName));
                $this->wptAssertEquals($list[$unexposedName], null);
                $this->wptAssertEquals($list->namedItem($unexposedName), null);
            }
        });
    }
}

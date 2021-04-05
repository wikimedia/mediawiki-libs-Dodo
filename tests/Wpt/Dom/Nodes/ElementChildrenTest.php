<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-children.html.
class ElementChildrenTest extends WptTestHarness
{
    public function testElementChildren()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Element-children.html';
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
            $this->assertTrueData($result instanceof Element, 'Expected an Element.');
            $this->assertFalseData($result->hasAttribute('id'), 'Expected the IDless Element.');
        });
        $this->assertTest(function () {
            $container = $this->doc->getElementById('test');
            $list = $container->children;
            $result = [];
            foreach ($list as $p => $___) {
                if ($list->hasOwnProperty($p)) {
                    $result[] = $p;
                }
            }
            $this->assertArrayEqualsData($result, ['0', '1', '2', '3', '4', '5']);
            $result = get_object_vars($list);
            $this->assertArrayEqualsData($result, ['0', '1', '2', '3', '4', '5', 'foo', 'bar', 'baz']);
            // Mapping of exposed names to their indices in the list.
            $exposedNames = ['foo' => 1, 'bar' => 3, 'baz' => 4];
            foreach ($exposedNames as $exposedName => $___) {
                $this->assertTrueData(isset($list[$exposedName]));
                $this->assertTrueData($list->hasOwnProperty($exposedName));
                $this->assertEqualsData($list[$exposedName], $list->namedItem($exposedName));
                $this->assertEqualsData($list[$exposedName], $list->item($exposedNames[$exposedName]));
                $this->assertTrueData($list[$exposedName] instanceof Element);
            }
            $unexposedNames = ['qux'];
            foreach ($unexposedNames as $unexposedName => $___) {
                $this->assertFalseData(isset($list[$unexposedName]));
                $this->assertFalseData($list->hasOwnProperty($unexposedName));
                $this->assertEqualsData($list[$unexposedName], null);
                $this->assertEqualsData($list->namedItem($unexposedName), null);
            }
        });
    }
}

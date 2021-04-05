<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\CharacterData;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createTextNode.html.
class DocumentCreateTextNodeTest extends WptTestHarness
{
    public function assertTestCreate($method, $iface, $nodeType, $nodeName)
    {
        foreach (["\\u000b", 'a -- b', 'a-', '-b', null, null] as $value) {
            $this->assertTest(function () use(&$method, &$value, &$iface, &$nodeType, &$nodeName) {
                $c = $this->doc[$method]($value);
                $expected = strval($value);
                $this->assertTrueData($c instanceof $iface);
                $this->assertTrueData($c instanceof CharacterData);
                $this->assertTrueData($c instanceof Node);
                $this->assertEqualsData($c->ownerDocument, $this->doc);
                $this->assertEqualsData($c->data, $expected, 'data');
                $this->assertEqualsData($c->nodeValue, $expected, 'nodeValue');
                $this->assertEqualsData($c->textContent, $expected, 'textContent');
                $this->assertEqualsData(count($c), count($expected));
                $this->assertEqualsData($c->nodeType, $nodeType);
                $this->assertEqualsData($c->nodeName, $nodeName);
                $this->assertEqualsData($c->hasChildNodes(), false);
                $this->assertEqualsData(count($c->childNodes), 0);
                $this->assertEqualsData($c->firstChild, null);
                $this->assertEqualsData($c->lastChild, null);
            }, $method . '(' . $this->formatValue($value) . ')');
        }
    }
    public function testDocumentCreateTextNode()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Document-createTextNode.html';
        $this->assertTestCreate('createTextNode', Text, 3, '#text');
    }
}

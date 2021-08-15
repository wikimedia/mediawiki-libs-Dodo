<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\CharacterData;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createTextNode.html.
class DocumentCreateTextNodeTest extends WPTTestHarness
{
    public function helperTestCreate($method, $iface, $nodeType, $nodeName)
    {
        foreach (["\\u000b", 'a -- b', 'a-', '-b', null, null] as $value) {
            $this->assertTest(function () use(&$method, &$value, &$iface, &$nodeType, &$nodeName) {
                $c = $this->doc->{$method}($value);
                $expected = strval($value);
                $this->wptAssertTrue($c instanceof $iface);
                $this->wptAssertTrue($c instanceof CharacterData);
                $this->wptAssertTrue($c instanceof Node);
                $this->wptAssertEquals($c->ownerDocument, $this->doc);
                $this->wptAssertEquals($c->data, $expected, 'data');
                $this->wptAssertEquals($c->nodeValue, $expected, 'nodeValue');
                $this->wptAssertEquals($c->textContent, $expected, 'textContent');
                $this->wptAssertEquals(count($c), count($expected));
                $this->wptAssertEquals($c->nodeType, $nodeType);
                $this->wptAssertEquals($c->nodeName, $nodeName);
                $this->wptAssertEquals($c->hasChildNodes(), false);
                $this->wptAssertEquals(count($c->childNodes), 0);
                $this->wptAssertEquals($c->firstChild, null);
                $this->wptAssertEquals($c->lastChild, null);
            }, $method . '(' . $this->formatValue($value) . ')');
        }
    }
    public function testDocumentCreateTextNode()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-createTextNode.html');
        $this->helperTestCreate('createTextNode', Text, 3, '#text');
    }
}

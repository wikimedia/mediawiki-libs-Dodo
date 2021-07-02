<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-isConnected-shadow-dom.html.
class NodeIsConnectedShadowDomTest extends WPTTestHarness
{
    public function testIsConnected($mode)
    {
        $this->assertTest(function () use(&$mode) {
            $host = $this->doc->createElement('div');
            $this->doc->body->appendChild($host);
            $root = $host->attachShadow(['mode' => $mode]);
            $node = $this->doc->createElement('div');
            $root->appendChild($node);
            $this->wptAssertTrue($node->isConnected);
        }, "Node.isConnected in a {$mode} shadow tree");
    }
    public function testNodeIsConnectedShadowDom()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-isConnected-shadow-dom.html');
        foreach (['closed', 'open'] as $mode => $___) {
            $this->testIsConnected($mode);
        }
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-isConnected.html.
class NodeIsConnectedTest extends WPTTestHarness
{
    public function checkNodes($aConnectedNodes, $aDisconnectedNodes)
    {
        foreach ($aConnectedNodes as $node) {
            return $this->assertTrueData($node->isConnected);
        }
        foreach ($aDisconnectedNodes as $node) {
            return $this->assertFalseData($node->isConnected);
        }
    }
    public function testNodeIsConnected()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-isConnected.html');
        $this->assertTest(function () {
            $nodes = [$this->doc->createElement('div'), $this->doc->createElement('div'), $this->doc->createElement('div')];
            $this->checkNodes([], $nodes);
            // Append nodes[0].
            $this->doc->body->appendChild($nodes[0]);
            $this->checkNodes([$nodes[0]], [$nodes[1], $nodes[2]]);
            // Append nodes[1] and nodes[2] together.
            $nodes[1]->appendChild($nodes[2]);
            $this->checkNodes([$nodes[0]], [$nodes[1], $nodes[2]]);
            $nodes[0]->appendChild($nodes[1]);
            $this->checkNodes($nodes, []);
            // Remove nodes[2].
            $nodes[2]->remove();
            $this->checkNodes([$nodes[0], $nodes[1]], [$nodes[2]]);
            // Remove nodes[0] and nodes[1] together.
            $nodes[0]->remove();
            $this->checkNodes([], $nodes);
        }, 'Test with ordinary child nodes');
        $this->assertTest(function () {
            $nodes = [$this->doc->createElement('iframe'), $this->doc->createElement('iframe'), $this->doc->createElement('iframe'), $this->doc->createElement('iframe'), $this->doc->createElement('div')];
            $frames = [$nodes[0], $nodes[1], $nodes[2], $nodes[3]];
            $this->checkNodes([], $nodes);
            // Since we cannot append anything to the contentWindow of an iframe before it
            // is appended to the main DOM tree, we append the iframes one after another.
            $this->doc->body->appendChild($nodes[0]);
            $this->checkNodes([$nodes[0]], [$nodes[1], $nodes[2], $nodes[3], $nodes[4]]);
            $frames[0]->getOwnerDocument()->body->appendChild($nodes[1]);
            $this->checkNodes([$nodes[0], $nodes[1]], [$nodes[2], $nodes[3], $nodes[4]]);
            $frames[1]->getOwnerDocument()->body->appendChild($nodes[2]);
            $this->checkNodes([$nodes[0], $nodes[1], $nodes[2]], [$nodes[3], $nodes[4]]);
            $frames[2]->getOwnerDocument()->body->appendChild($nodes[3]);
            $this->checkNodes([$nodes[0], $nodes[1], $nodes[2], $nodes[3]], [$nodes[4]]);
            $frames[3]->getOwnerDocument()->body->appendChild($nodes[4]);
            $this->checkNodes($nodes, []);
            $frames[3]->remove();
            // Since node[4] is still under the doument of frame[3], it's still connected.
            $this->checkNodes([$nodes[0], $nodes[1], $nodes[2], $nodes[4]], [$nodes[3]]);
            $frames[0]->remove();
            // Since node[1] and node[2] are still under the doument of frame[0], they are
            // still connected.
            $this->checkNodes([$nodes[1], $nodes[2], $nodes[4]], [$nodes[0], $nodes[3]]);
        }, 'Test with iframes');
    }
}

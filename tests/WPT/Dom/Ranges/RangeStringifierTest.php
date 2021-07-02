<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-stringifier.html.
class RangeStringifierTest extends WPTTestHarness
{
    public function testRangeStringifier()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-stringifier.html');
        $this->assertTest(function () {
            $r = new Range();
            $testDiv = $this->doc->getElementById('test');
            $this->assertTest(function () use(&$r, &$testDiv) {
                $r->selectNodeContents($testDiv);
                $this->wptAssertEquals($r->collapsed, false);
                $this->wptAssertEquals($r, $testDiv->textContent);
            }, 'Node contents of a single div');
            $textNode = $testDiv->childNodes[0];
            $this->assertTest(function () use(&$r, &$textNode) {
                $r->setStart($textNode, 5);
                $r->setEnd($textNode, 7);
                $this->wptAssertEquals($r->collapsed, false);
                $this->wptAssertEquals($r, 'di');
            }, 'Text node with offsets');
            $anotherDiv = $this->doc->getElementById('another');
            $this->assertTest(function () use(&$r, &$testDiv, &$anotherDiv) {
                $r->setStart($testDiv, 0);
                $r->setEnd($anotherDiv, 0);
                $this->wptAssertEquals($r, "Test div\n");
            }, 'Two nodes, each with a text node');
            $lastDiv = $this->doc->getElementById('last');
            $lastText = $lastDiv->childNodes[0];
            $this->assertTest(function () use(&$r, &$textNode, &$lastText) {
                $r->setStart($textNode, 5);
                $r->setEnd($lastText, 4);
                $this->wptAssertEquals($r, "div\nAnother div\nLast");
            }, 'Three nodes with start offset and end offset on text nodes');
        });
    }
}

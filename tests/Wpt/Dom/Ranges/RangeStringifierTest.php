<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\IDLeDOM\Range;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-stringifier.html.
class RangeStringifierTest extends WptTestHarness
{
    public function testRangeStringifier()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-stringifier.html');
        $this->assertTest(function () {
            $r = new Range();
            $testDiv = $this->doc->getElementById('test');
            $this->assertTest(function () use(&$r, &$testDiv) {
                $r->selectNodeContents($testDiv);
                $this->assertEqualsData($r->collapsed, false);
                $this->assertEqualsData($r, $testDiv->textContent);
            }, 'Node contents of a single div');
            $textNode = $testDiv->childNodes[0];
            $this->assertTest(function () use(&$r, &$textNode) {
                $r->setStart($textNode, 5);
                $r->setEnd($textNode, 7);
                $this->assertEqualsData($r->collapsed, false);
                $this->assertEqualsData($r, 'di');
            }, 'Text node with offsets');
            $anotherDiv = $this->doc->getElementById('another');
            $this->assertTest(function () use(&$r, &$testDiv, &$anotherDiv) {
                $r->setStart($testDiv, 0);
                $r->setEnd($anotherDiv, 0);
                $this->assertEqualsData($r, "Test div\n");
            }, 'Two nodes, each with a text node');
            $lastDiv = $this->doc->getElementById('last');
            $lastText = $lastDiv->childNodes[0];
            $this->assertTest(function () use(&$r, &$textNode, &$lastText) {
                $r->setStart($textNode, 5);
                $r->setEnd($lastText, 4);
                $this->assertEqualsData($r, "div\nAnother div\nLast");
            }, 'Three nodes with start offset and end offset on text nodes');
        });
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-intersectsNode-2.html.
class RangeIntersectsNode2Test extends WPTTestHarness
{
    public function testRangeIntersectsNode2()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-intersectsNode-2.html');
        // Taken from Chromium bug: http://crbug.com/822510
        $this->assertTest(function () {
            $range = new Range();
            $div = $this->doc->getElementById('div');
            $s0 = $this->doc->getElementById('s0');
            $s1 = $this->doc->getElementById('s1');
            $s2 = $this->doc->getElementById('s2');
            // Range encloses s0
            $range->setStart($div, 0);
            $range->setEnd($div, 1);
            $this->wptAssertTrue($range->intersectsNode($s0), '[s0] range.intersectsNode(s0)');
            $this->wptAssertFalse($range->intersectsNode($s1), '[s0] range.intersectsNode(s1)');
            $this->wptAssertFalse($range->intersectsNode($s2), '[s0] range.intersectsNode(s2)');
            // Range encloses s1
            $range->setStart($div, 1);
            $range->setEnd($div, 2);
            $this->wptAssertFalse($range->intersectsNode($s0), '[s1] range.intersectsNode(s0)');
            $this->wptAssertTrue($range->intersectsNode($s1), '[s1] range.intersectsNode(s1)');
            $this->wptAssertFalse($range->intersectsNode($s2), '[s1] range.intersectsNode(s2)');
            // Range encloses s2
            $range->setStart($div, 2);
            $range->setEnd($div, 3);
            $this->wptAssertFalse($range->intersectsNode($s0), '[s2] range.intersectsNode(s0)');
            $this->wptAssertFalse($range->intersectsNode($s1), '[s2] range.intersectsNode(s1)');
            $this->wptAssertTrue($range->intersectsNode($s2), '[s2] range.intersectsNode(s2)');
        }, 'Range.intersectsNode() simple cases');
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-test-iframe.html.
class RangeTestIframeTest extends WptTestHarness
{
    public function run()
    {
        try {
            $this->window->unexpectedException = null;
            if (gettype($this->window->testNodeInput) != NULL) {
                $this->window->testNode = eval($this->window->testNodeInput);
            }
            $rangeEndpoints = null;
            if (gettype($this->window->testRangeInput) == NULL) {
                // Use the hash (old way of doing things, bad because it requires
                // navigation)
                if ($location->hash == '') {
                    return;
                }
                $rangeEndpoints = eval(substr($location->hash, 1));
            } else {
                // Get the variable directly off the window, faster and can be done
                // synchronously
                $rangeEndpoints = eval($this->window->testRangeInput);
            }
            $range = null;
            if ($rangeEndpoints == 'detached') {
                $range = $this->doc->createRange();
                $range->detach();
            } else {
                $range = ownerDocument($rangeEndpoints[0])->createRange();
                $range->setStart($rangeEndpoints[0], $rangeEndpoints[1]);
                $range->setEnd($rangeEndpoints[2], $rangeEndpoints[3]);
            }
            $this->window->testRange = $range;
        } catch (Exception $e) {
            $this->window->unexpectedException = $e;
        }
    }
    public function testRangeTestIframe()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/ranges/Range-test-iframe.html';
        // This script only exists because we want to evaluate the range endpoints
        // in each iframe using that iframe's local variables set up by common.js.  It
        // just creates the range and does nothing else.  The data is returned via
        // window.testRange, and if an exception is thrown, it's put in
        // window.unexpectedException.
        $this->window->unexpectedException = null;
        $testDiv->style->display = 'none';
    }
}

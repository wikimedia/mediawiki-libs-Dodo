<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/getElementsByClassName-empty-set.html.
class GetElementsByClassNameEmptySetTest extends WPTTestHarness
{
    public function testGetElementsByClassNameEmptySet()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/getElementsByClassName-empty-set.html');
        $this->assertTest(function () {
            $elements = $this->doc->getElementsByClassName('');
            $this->assertArrayEqualsData($elements, []);
        }, 'Passing an empty string to getElementsByClassName should return an empty HTMLCollection');
        $this->assertTest(function () {
            $elements = $this->doc->getElementsByClassName(' ');
            $this->assertArrayEqualsData($elements, []);
        }, 'Passing a space to getElementsByClassName should return an empty HTMLCollection');
        $this->assertTest(function () {
            $elements = $this->doc->getElementsByClassName('   ');
            $this->assertArrayEqualsData($elements, []);
        }, 'Passing three spaces to getElementsByClassName should return an empty HTMLCollection');
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-case-insensitive.html.
class ParentNodeQuerySelectorCaseInsensitiveTest extends WptTestHarness
{
    public function testParentNodeQuerySelectorCaseInsensitive()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-case-insensitive.html');
        $input = $this->doc->getElementById('testInput');
        $this->assertTest(function () use(&$input) {
            $this->assertEqualsData($this->doc->querySelector('input[name*=user i]'), $input);
        }, 'querySelector');
        $this->assertTest(function () use(&$input) {
            $this->assertArrayEqualsData($this->doc->querySelectorAll('input[name*=user i]'), [$input]);
        }, 'querySelectorAll');
    }
}

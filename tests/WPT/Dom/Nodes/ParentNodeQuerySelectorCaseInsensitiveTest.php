<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-case-insensitive.html.
class ParentNodeQuerySelectorCaseInsensitiveTest extends WPTTestHarness
{
    public function testParentNodeQuerySelectorCaseInsensitive()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-case-insensitive.html');
        $input = $this->doc->getElementById('testInput');
        $this->assertTest(function () use (&$input) {
            $this->wptAssertEquals($this->doc->querySelector('input[name*=user i]'), $input);
        }, 'querySelector');
        $this->assertTest(function () use (&$input) {
            $this->wptAssertArrayEquals($this->doc->querySelectorAll('input[name*=user i]'), [$input]);
        }, 'querySelectorAll');
    }
}

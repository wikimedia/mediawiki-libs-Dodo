<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-scope.html.
class ParentNodeQuerySelectorScopeTest extends WPTTestHarness
{
    public function testParentNodeQuerySelectorScope()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-scope.html');
        $div = $this->doc->querySelector('div');
        $p = $this->doc->querySelector('p');
        $this->assertTest(function () use (&$div, &$p) {
            $this->wptAssertEquals($div->querySelector(':scope > p'), $p);
            $this->wptAssertEquals($div->querySelector(':scope > span'), null);
        }, 'querySelector');
        $this->assertTest(function () use (&$div, &$p) {
            $this->wptAssertArrayEquals($div->querySelectorAll(':scope > p'), [$p]);
            $this->wptAssertArrayEquals($div->querySelectorAll(':scope > span'), []);
        }, 'querySelectorAll');
    }
}

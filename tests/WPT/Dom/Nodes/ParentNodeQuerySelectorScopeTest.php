<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-scope.html.
class ParentNodeQuerySelectorScopeTest extends WPTTestHarness
{
    public function testParentNodeQuerySelectorScope()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-scope.html');
        $div = $this->doc->querySelector('div');
        $p = $this->doc->querySelector('p');
        $this->assertTest(function () use(&$div, &$p) {
            $this->assertEqualsData($div->querySelector(':scope > p'), $p);
            $this->assertEqualsData($div->querySelector(':scope > span'), null);
        }, 'querySelector');
        $this->assertTest(function () use(&$div, &$p) {
            $this->assertArrayEqualsData($div->querySelectorAll(':scope > p'), [$p]);
            $this->assertArrayEqualsData($div->querySelectorAll(':scope > span'), []);
        }, 'querySelectorAll');
    }
}

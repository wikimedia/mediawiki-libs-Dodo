<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/svg-template-querySelector.html.
class SvgTemplateQuerySelectorTest extends WptTestHarness
{
    public function testSvgTemplateQuerySelector()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/svg-template-querySelector.html';
        $this->assertTest(function () {
            $fragment = $this->doc->querySelector('#template1')->content;
            $this->assertNotEqualsData($fragment->querySelector('div'), null);
        }, 'querySelector works on template contents fragments with HTML elements (sanity check)');
        $this->assertTest(function () {
            $fragment = $this->doc->querySelector('#template2')->content;
            $this->assertNotEqualsData($fragment->querySelector('svg'), null);
        }, 'querySelector works on template contents fragments with SVG elements');
        $this->assertTest(function () {
            $fragment = $this->doc->querySelector('#template3')->content;
            $this->assertNotEqualsData($fragment->firstChild->querySelector('svg'), null);
        }, 'querySelector works on template contents fragments with nested SVG elements');
    }
}

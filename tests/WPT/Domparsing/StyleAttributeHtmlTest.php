<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/style_attribute_html.html.
class StyleAttributeHtmlTest extends WPTTestHarness
{
    public function testStyleAttributeHtml()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/style_attribute_html.html');
        $div = null;
        // setup()
        $input = '<div style="color: red">Foo</div>';
        $doc = (new DOMParser())->parseFromString($input, 'text/html');
        $div = $doc->querySelector('div');
        $this->assertTest(function () use (&$div) {
            $style = $div->style;
            $this->wptAssertEquals($style->cssText, 'color: red;');
            $this->wptAssertEquals($style->color, 'red');
            $this->wptAssertEquals($div->getAttribute('style'), 'color: red', 'Value of style attribute should match the string value that was set');
        }, 'Parsing of initial style attribute');
        $this->assertTest(function () use (&$div) {
            $style = $div->style;
            $div->setAttribute('style', 'color:: invalid');
            $this->wptAssertEquals($style->cssText, '');
            $this->wptAssertEquals($style->color, '');
            $this->wptAssertEquals($div->getAttribute('style'), 'color:: invalid', 'Value of style attribute should match the string value that was set');
        }, 'Parsing of invalid style attribute');
        $this->assertTest(function () use (&$div) {
            $style = $div->style;
            $div->setAttribute('style', 'color: green');
            $this->wptAssertEquals($style->cssText, 'color: green;');
            $this->wptAssertEquals($style->color, 'green');
            $this->wptAssertEquals($div->getAttribute('style'), 'color: green', 'Value of style attribute should match the string value that was set');
        }, 'Parsing of style attribute');
        $this->assertTest(function () use (&$div) {
            $style = $div->style;
            $style->backgroundColor = 'blue';
            $this->wptAssertEquals($style->cssText, 'color: green; background-color: blue;', 'Should not drop the existing style');
            $this->wptAssertEquals($style->color, 'green', 'Should not drop the existing style');
            $this->wptAssertEquals($div->getAttribute('style'), 'color: green; background-color: blue;', 'Should update style attribute');
        }, 'Update style.backgroundColor');
    }
}

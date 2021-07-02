<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createCDATASection.html.
class DocumentCreateCDATASectionTest extends WPTTestHarness
{
    public function testDocumentCreateCDATASection()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-createCDATASection.html');
        $this->wptAssertThrowsDom('NotSupportedError', function () {
            return $this->doc->createCDATASection('foo');
        });
        $this->done();
    }
}

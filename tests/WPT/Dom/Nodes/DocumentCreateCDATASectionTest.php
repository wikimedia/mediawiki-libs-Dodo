<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createCDATASection.html.
class DocumentCreateCDATASectionTest extends WPTTestHarness
{
    public function testDocumentCreateCDATASection()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-createCDATASection.html');
        $this->assertThrowsDomData('NotSupportedError', function () {
            return $this->doc->createCDATASection('foo');
        });
        $this->done();
    }
}

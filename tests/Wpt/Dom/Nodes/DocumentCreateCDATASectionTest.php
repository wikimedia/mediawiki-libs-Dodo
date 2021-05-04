<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createCDATASection.html.
class DocumentCreateCDATASectionTest extends WptTestHarness
{
    public function testDocumentCreateCDATASection()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-createCDATASection.html');
        $this->assertThrowsDomData('NotSupportedError', function () {
            return $this->doc->createCDATASection('foo');
        });
        $this->done();
    }
}

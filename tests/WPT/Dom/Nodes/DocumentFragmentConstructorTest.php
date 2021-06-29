<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DocumentFragment-constructor.html.
class DocumentFragmentConstructorTest extends WPTTestHarness
{
    public function testDocumentFragmentConstructor()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DocumentFragment-constructor.html');
        $this->assertTest(function () {
            $fragment = new DocumentFragment($this->doc);
            $this->assertEqualsData($fragment->ownerDocument, $this->doc);
        }, 'Sets the owner document to the current global object associated document');
        $this->assertTest(function () {
            $fragment = new DocumentFragment($this->doc);
            $text = $this->doc->createTextNode('');
            $fragment->appendChild($text);
            $this->assertEqualsData($fragment->firstChild, $text);
        }, 'Create a valid document DocumentFragment');
    }
}

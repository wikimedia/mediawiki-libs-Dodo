<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DocumentFragment-constructor.html.
class DocumentFragmentConstructorTest extends WPTTestHarness
{
    public function testDocumentFragmentConstructor()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DocumentFragment-constructor.html');
        $this->assertTest(function () {
            $fragment = new DocumentFragment($this->doc);
            $this->wptAssertEquals($fragment->ownerDocument, $this->doc);
        }, 'Sets the owner document to the current global object associated document');
        $this->assertTest(function () {
            $fragment = new DocumentFragment($this->doc);
            $text = $this->doc->createTextNode('');
            $fragment->appendChild($text);
            $this->wptAssertEquals($fragment->firstChild, $text);
        }, 'Create a valid document DocumentFragment');
    }
}

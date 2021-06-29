<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DocumentFragment-querySelectorAll-after-modification.html.
class DocumentFragmentQuerySelectorAllAfterModificationTest extends WPTTestHarness
{
    public function testDocumentFragmentQuerySelectorAllAfterModification()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DocumentFragment-querySelectorAll-after-modification.html');
        $frag = $this->doc->createDocumentFragment();
        $frag->appendChild($this->doc->createElement('div'));
        $this->assertArrayEqualsData($frag->querySelectorAll('img'), [], 'before modification');
        $frag->appendChild($this->doc->createElement('div'));
        // If the bug is present, this will throw.
        $this->assertArrayEqualsData($frag->querySelectorAll('img'), [], 'after modification');
        $this->done();
    }
}

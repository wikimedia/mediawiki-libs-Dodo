<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-nodeValue.html.
class NodeNodeValueTest extends WPTTestHarness
{
    public function testNodeNodeValue()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-nodeValue.html');
        $this->assertTest(function () {
            $the_text = $this->doc->createTextNode('A span!');
            $this->assertEqualsData($the_text->nodeValue, 'A span!');
            $this->assertEqualsData($the_text->data, 'A span!');
            $the_text->nodeValue = 'test again';
            $this->assertEqualsData($the_text->nodeValue, 'test again');
            $this->assertEqualsData($the_text->data, 'test again');
            $the_text->nodeValue = null;
            $this->assertEqualsData($the_text->nodeValue, '');
            $this->assertEqualsData($the_text->data, '');
        }, 'Text.nodeValue');
        $this->assertTest(function () {
            $the_comment = $this->doc->createComment('A comment!');
            $this->assertEqualsData($the_comment->nodeValue, 'A comment!');
            $this->assertEqualsData($the_comment->data, 'A comment!');
            $the_comment->nodeValue = 'test again';
            $this->assertEqualsData($the_comment->nodeValue, 'test again');
            $this->assertEqualsData($the_comment->data, 'test again');
            $the_comment->nodeValue = null;
            $this->assertEqualsData($the_comment->nodeValue, '');
            $this->assertEqualsData($the_comment->data, '');
        }, 'Comment.nodeValue');
        $this->assertTest(function () {
            $the_pi = $this->doc->createProcessingInstruction('pi', 'A PI!');
            $this->assertEqualsData($the_pi->nodeValue, 'A PI!');
            $this->assertEqualsData($the_pi->data, 'A PI!');
            $the_pi->nodeValue = 'test again';
            $this->assertEqualsData($the_pi->nodeValue, 'test again');
            $this->assertEqualsData($the_pi->data, 'test again');
            $the_pi->nodeValue = null;
            $this->assertEqualsData($the_pi->nodeValue, '');
            $this->assertEqualsData($the_pi->data, '');
        }, 'ProcessingInstruction.nodeValue');
        $this->assertTest(function () {
            $the_link = $this->doc->createElement('a');
            $this->assertEqualsData($the_link->nodeValue, null);
            $the_link->nodeValue = 'foo';
            $this->assertEqualsData($the_link->nodeValue, null);
        }, 'Element.nodeValue');
        $this->assertTest(function () {
            $this->assertEqualsData($this->doc->nodeValue, null);
            $this->doc->nodeValue = 'foo';
            $this->assertEqualsData($this->doc->nodeValue, null);
        }, 'Document.nodeValue');
        $this->assertTest(function () {
            $the_frag = $this->doc->createDocumentFragment();
            $this->assertEqualsData($the_frag->nodeValue, null);
            $the_frag->nodeValue = 'foo';
            $this->assertEqualsData($the_frag->nodeValue, null);
        }, 'DocumentFragment.nodeValue');
        $this->assertTest(function () {
            $the_doctype = $this->doc->doctype;
            $this->assertEqualsData($the_doctype->nodeValue, null);
            $the_doctype->nodeValue = 'foo';
            $this->assertEqualsData($the_doctype->nodeValue, null);
        }, 'DocumentType.nodeValue');
    }
}

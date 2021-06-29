<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-removeChild.html.
class NodeRemoveChildTest extends WPTTestHarness
{
    public function testNodeRemoveChild()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-removeChild.html');
        $this->docs = [[function () {
            return $this->doc;
        }, 'the main document'], [function () {
            return $frames[0]->document;
        }, 'a frame document'], [function () {
            return $this->doc->implementation->createHTMLDocument();
        }, 'a synthetic document']];
        foreach ($docs as $d) {
            $get = $d[0];
            $description = $d[1];
            foreach ($creators as $p => $___) {
                $creator = $creators[$p];
                $this->assertTest(function () use(&$get, &$creator) {
                    $doc = $get();
                    $s = $doc[$creator]('a');
                    $this->assertEqualsData($s->ownerDocument, $doc);
                    $this->assertThrowsDomData('NOT_FOUND_ERR', function () use(&$s) {
                        $this->getDocBody( $this->doc )->removeChild($s);
                    });
                    $this->assertEqualsData($s->ownerDocument, $doc);
                }, 'Passing a detached ' . $p . ' from ' . $description . ' to removeChild should not affect it.');
                $this->assertTest(function () use(&$get, &$creator) {
                    $doc = $get();
                    $s = $doc[$creator]('b');
                    $doc->documentElement->appendChild($s);
                    $this->assertEqualsData($s->ownerDocument, $doc);
                    $this->assertThrowsDomData('NOT_FOUND_ERR', function () use(&$s) {
                        $this->getDocBody( $this->doc )->removeChild($s);
                    });
                    $this->assertEqualsData($s->ownerDocument, $doc);
                }, 'Passing a non-detached ' . $p . ' from ' . $description . ' to removeChild should not affect it.');
                $this->assertTest(function () use(&$get, &$creator) {
                    $doc = $get();
                    $s = $doc[$creator]('test');
                    $doc->body->appendChild($s);
                    $this->assertEqualsData($s->ownerDocument, $doc);
                    $this->assertThrowsDomData('NOT_FOUND_ERR', ($doc->defaultView || $self)::DOMException, function () use(&$s, &$doc) {
                        $s->removeChild($doc);
                    });
                }, 'Calling removeChild on a ' . $p . ' from ' . $description . ' with no children should throw NOT_FOUND_ERR.');
            }
        }
        $this->assertTest(function () {
            $this->assertThrowsJsData($this->type_error, function () {
                $this->getDocBody( $this->doc )->removeChild(null);
            });
            $this->assertThrowsJsData($this->type_error, function () {
                $this->getDocBody( $this->doc )->removeChild(['a' => 'b']);
            });
        }, 'Passing a value that is not a Node reference to removeChild should throw TypeError.');
        $creators = ['element' => 'createElement', 'text' => 'createTextNode', 'comment' => 'createComment'];
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
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
                    $this->wptAssertEquals($s->ownerDocument, $doc);
                    $this->wptAssertThrowsDom('NOT_FOUND_ERR', function () use(&$s) {
                        $this->doc->body->removeChild($s);
                    });
                    $this->wptAssertEquals($s->ownerDocument, $doc);
                }, 'Passing a detached ' . $p . ' from ' . $description . ' to removeChild should not affect it.');
                $this->assertTest(function () use(&$get, &$creator) {
                    $doc = $get();
                    $s = $doc[$creator]('b');
                    $doc->documentElement->appendChild($s);
                    $this->wptAssertEquals($s->ownerDocument, $doc);
                    $this->wptAssertThrowsDom('NOT_FOUND_ERR', function () use(&$s) {
                        $this->doc->body->removeChild($s);
                    });
                    $this->wptAssertEquals($s->ownerDocument, $doc);
                }, 'Passing a non-detached ' . $p . ' from ' . $description . ' to removeChild should not affect it.');
                $this->assertTest(function () use(&$get, &$creator) {
                    $doc = $get();
                    $s = $doc[$creator]('test');
                    $doc->body->appendChild($s);
                    $this->wptAssertEquals($s->ownerDocument, $doc);
                    $this->wptAssertThrowsDom('NOT_FOUND_ERR', ($doc->defaultView || $self)::DOMException, function () use(&$s, &$doc) {
                        $s->removeChild($doc);
                    });
                }, 'Calling removeChild on a ' . $p . ' from ' . $description . ' with no children should throw NOT_FOUND_ERR.');
            }
        }
        $this->assertTest(function () {
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->body->removeChild(null);
            });
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->body->removeChild(['a' => 'b']);
            });
        }, 'Passing a value that is not a Node reference to removeChild should throw TypeError.');
        $creators = ['element' => 'createElement', 'text' => 'createTextNode', 'comment' => 'createComment'];
    }
}

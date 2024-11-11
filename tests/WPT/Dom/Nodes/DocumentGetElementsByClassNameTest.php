<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-getElementsByClassName.html.
class DocumentGetElementsByClassNameTest extends WPTTestHarness
{
    public function testDocumentGetElementsByClassName()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-getElementsByClassName.html');
        $this->assertTest(function () {
            $a = $this->doc->createElement('a');
            $b = $this->doc->createElement('b');
            $a->className = 'foo';
            $this->add_cleanup(function () use (&$a) {
                $this->doc->body->removeChild($a);
            });
            $this->doc->body->appendChild($a);
            $l = $this->doc->getElementsByClassName('foo');
            $this->wptAssertTrue($l instanceof HTMLCollection);
            $this->wptAssertEquals(count($l), 1);
            $b->className = 'foo';
            $this->doc->body->appendChild($b);
            $this->wptAssertEquals(count($l), 2);
            $this->doc->body->removeChild($b);
            $this->wptAssertEquals(count($l), 1);
        }, 'getElementsByClassName() should be a live collection');
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-getElementsByClassName.html.
class DocumentGetElementsByClassNameTest extends WptTestHarness
{
    public function testDocumentGetElementsByClassName()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-getElementsByClassName.html');
        $this->assertTest(function () {
            $a = $this->doc->createElement('a');
            $b = $this->doc->createElement('b');
            $a->className = 'foo';
            $this->{$this}->addCleanup(function () use(&$a) {
                $this->doc->body->removeChild($a);
            });
            $this->doc->body->appendChild($a);
            $l = $this->doc->getElementsByClassName('foo');
            $this->assertTrueData($l instanceof HTMLCollection);
            $this->assertEqualsData(count($l), 1);
            $b->className = 'foo';
            $this->doc->body->appendChild($b);
            $this->assertEqualsData(count($l), 2);
            $this->doc->body->removeChild($b);
            $this->assertEqualsData(count($l), 1);
        }, 'getElementsByClassName() should be a live collection');
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
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
            $this->{$this}->addCleanup(function () use(&$a) {
                $this->getDocBody( $this->doc )->removeChild($a);
            });
            $this->getDocBody( $this->doc )->appendChild($a);
            $l = $this->doc->getElementsByClassName('foo');
            $this->assertTrueData($l instanceof HTMLCollection);
            $this->assertEqualsData(count($l), 1);
            $b->className = 'foo';
            $this->getDocBody( $this->doc )->appendChild($b);
            $this->assertEqualsData(count($l), 2);
            $this->getDocBody( $this->doc )->removeChild($b);
            $this->assertEqualsData(count($l), 1);
        }, 'getElementsByClassName() should be a live collection');
    }
}

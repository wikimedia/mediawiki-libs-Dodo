<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Text-wholeText.html.
class TextWholeTextTest extends WptTestHarness
{
    public function testTextWholeText()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Text-wholeText.html';
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $t1 = $this->doc->createTextNode('a');
            $t2 = $this->doc->createTextNode('b');
            $t3 = $this->doc->createTextNode('c');
            $this->assertEqualsData($t1->wholeText, $t1->textContent);
            $parent->appendChild($t1);
            $this->assertEqualsData($t1->wholeText, $t1->textContent);
            $parent->appendChild($t2);
            $this->assertEqualsData($t1->wholeText, $t1->textContent + $t2->textContent);
            $this->assertEqualsData($t2->wholeText, $t1->textContent + $t2->textContent);
            $parent->appendChild($t3);
            $this->assertEqualsData($t1->wholeText, $t1->textContent + $t2->textContent + $t3->textContent);
            $this->assertEqualsData($t2->wholeText, $t1->textContent + $t2->textContent + $t3->textContent);
            $this->assertEqualsData($t3->wholeText, $t1->textContent + $t2->textContent + $t3->textContent);
            $a = $this->doc->createElement('a');
            $a->textContent = "I'm an Anchor";
            $parent->insertBefore($a, $t3);
            $span = $this->doc->createElement('span');
            $span->textContent = "I'm a Span";
            $parent->appendChild($this->doc->createElement('span'));
            $this->assertEqualsData($t1->wholeText, $t1->textContent + $t2->textContent);
            $this->assertEqualsData($t2->wholeText, $t1->textContent + $t2->textContent);
            $this->assertEqualsData($t3->wholeText, $t3->textContent);
        }, 'wholeText returns text of all Text nodes logically adjacent to the node, in document order.');
    }
}

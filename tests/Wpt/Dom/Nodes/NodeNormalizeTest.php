<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-normalize.html.
class NodeNormalizeTest extends WptTestHarness
{
    public function testNodeNormalize()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-normalize.html');
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $t1 = $this->doc->createTextNode('1');
            $t2 = $this->doc->createTextNode('2');
            $t3 = $this->doc->createTextNode('3');
            $t4 = $this->doc->createTextNode('4');
            $df->appendChild($t1);
            $df->appendChild($t2);
            $this->assertEqualsData(count($df->childNodes), 2);
            $this->assertEqualsData($df->textContent, '12');
            $el = $this->doc->createElement('x');
            $df->appendChild($el);
            $el->appendChild($t3);
            $el->appendChild($t4);
            $this->doc->normalize();
            $this->assertEqualsData(count($el->childNodes), 2);
            $this->assertEqualsData($el->textContent, '34');
            $this->assertEqualsData(count($df->childNodes), 3);
            $this->assertEqualsData($t1->data, '1');
            $df->normalize();
            $this->assertEqualsData(count($df->childNodes), 2);
            $this->assertEqualsData($df->firstChild, $t1);
            $this->assertEqualsData($t1->data, '12');
            $this->assertEqualsData($t2->data, '2');
            $this->assertEqualsData($el->firstChild, $t3);
            $this->assertEqualsData($t3->data, '34');
            $this->assertEqualsData($t4->data, '4');
        });
        // https://www.w3.org/Bugs/Public/show_bug.cgi?id=19837
        $this->assertTest(function () {
            $div = $this->doc->createElement('div');
            $t1 = $div->appendChild($this->doc->createTextNode(''));
            $t2 = $div->appendChild($this->doc->createTextNode('a'));
            $t3 = $div->appendChild($this->doc->createTextNode(''));
            $this->assertArrayEqualsData($div->childNodes, [$t1, $t2, $t3]);
            $div->normalize();
            $this->assertArrayEqualsData($div->childNodes, [$t2]);
        }, 'Empty text nodes separated by a non-empty text node');
        $this->assertTest(function () {
            $div = $this->doc->createElement('div');
            $t1 = $div->appendChild($this->doc->createTextNode(''));
            $t2 = $div->appendChild($this->doc->createTextNode(''));
            $this->assertArrayEqualsData($div->childNodes, [$t1, $t2]);
            $div->normalize();
            $this->assertArrayEqualsData($div->childNodes, []);
        }, 'Empty text nodes');
        // The specification for normalize is clear that only "exclusive Text
        // nodes" are to be modified. This excludes CDATASection nodes, which
        // derive from Text. Naïve implementations may fail to skip
        // CDATASection nodes, or even worse, try to test textContent or
        // nodeValue without taking care to check the node type. They will
        // fail this test.
        $this->assertTest(function () {
            // We create an XML document so that we can create CDATASection.
            // Except for the CDATASection the result should be the same for
            // an HTML document. (No non-Text node should be touched.)
            $doc = $this->parseFromString('<div/>', 'text/xml');
            $div = $doc->documentElement;
            $t1 = $div->appendChild($doc->createTextNode('a'));
            // The first parameter is the "target" of the processing
            // instruction, and the 2nd is the text content.
            $t2 = $div->appendChild($doc->createProcessingInstruction('pi', ''));
            $t3 = $div->appendChild($doc->createTextNode('b'));
            $t4 = $div->appendChild($doc->createCDATASection(''));
            $t5 = $div->appendChild($doc->createTextNode('c'));
            $t6 = $div->appendChild($doc->createComment(''));
            $t7 = $div->appendChild($doc->createTextNode('d'));
            $t8 = $div->appendChild($doc->createElement('el'));
            $t9 = $div->appendChild($doc->createTextNode('e'));
            $expected = [$t1, $t2, $t3, $t4, $t5, $t6, $t7, $t8, $t9];
            $this->assertArrayEqualsData($div->childNodes, $expected);
            $div->normalize();
            $this->assertArrayEqualsData($div->childNodes, $expected);
        }, 'Non-text nodes with empty textContent values.');
    }
}

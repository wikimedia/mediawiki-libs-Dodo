<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/insert-adjacent.html.
class InsertAdjacentTest extends WptTestHarness
{
    public function testInsertAdjacent()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/insert-adjacent.html';
        $possiblePositions = ['beforebegin' => 'previousSibling', 'afterbegin' => 'firstChild', 'beforeend' => 'lastChild', 'afterend' => 'nextSibling'];
        $texts = ['beforebegin' => 'raclette', 'afterbegin' => 'tartiflette', 'beforeend' => 'lasagne', 'afterend' => 'gateau aux pommes'];
        $el = $this->doc->querySelector('#element');
        foreach ($get_object_vars as $position) {
            $div = $this->doc->createElement('h3');
            $this->assertTest(function () use(&$div, &$texts, &$position, &$el, &$possiblePositions) {
                $div->id = $texts[$position];
                $el->insertAdjacentElement($position, $div);
                $this->assertEqualsData($el[$possiblePositions[$position]]->id, $texts[$position]);
            }, 'insertAdjacentElement(' . $position . ', ' . $div . ' )');
            $this->assertTest(function () use(&$el, &$position, &$texts, &$possiblePositions) {
                $el->insertAdjacentText($position, $texts[$position]);
                $this->assertEqualsData($el[$possiblePositions[$position]]->textContent, $texts[$position]);
            }, 'insertAdjacentText(' . $position . ', ' . $texts[$position] . ' )');
        }
        $this->assertTest(function () use(&$el) {
            $this->assertThrowsJsData($this->type_error, function () use(&$el) {
                $el->insertAdjacentElement('afterbegin', $this->doc->implementation->createDocumentType('html'));
            });
        }, 'invalid object argument insertAdjacentElement');
        $this->assertTest(function () {
            $el = $this->doc->implementation->createHTMLDocument()->documentElement;
            $this->assertThrowsDomData('HIERARCHY_REQUEST_ERR', function () use(&$el) {
                $el->insertAdjacentElement('beforebegin', $this->doc->createElement('banane'));
            });
        }, 'invalid caller object insertAdjacentElement');
        $this->assertTest(function () {
            $el = $this->doc->implementation->createHTMLDocument()->documentElement;
            $this->assertThrowsDomData('HIERARCHY_REQUEST_ERR', function () use(&$el) {
                $el->insertAdjacentText('beforebegin', 'tomate farcie');
            });
        }, 'invalid caller object insertAdjacentText');
        $this->assertTest(function () use(&$el) {
            $div = $this->doc->createElement('h3');
            $this->assertThrowsDomData('SYNTAX_ERR', function () use(&$el, &$div) {
                $el->insertAdjacentElement('heeeee', $div);
            });
        }, 'invalid syntax for insertAdjacentElement');
        $this->assertTest(function () use(&$el) {
            $this->assertThrowsDomData('SYNTAX_ERR', function () use(&$el) {
                $el->insertAdjacentText('hoooo', 'magret de canard');
            });
        }, 'invalid syntax for insertAdjacentText');
        $this->assertTest(function () use(&$el) {
            $div = $this->doc->createElement('div');
            $this->assertEqualsData($div->insertAdjacentElement('beforebegin', $el), null);
        }, 'insertAdjacentText should return null');
    }
}

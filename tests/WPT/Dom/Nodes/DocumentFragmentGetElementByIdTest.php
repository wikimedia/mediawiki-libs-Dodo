<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DocumentFragment-getElementById.html.
class DocumentFragmentGetElementByIdTest extends WPTTestHarness
{
    public function testDocumentFragmentGetElementById()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DocumentFragment-getElementById.html');
        $this->assertTest(function () {
            $this->assertEqualsData((new \ReflectionClass( DocumentFragment::class))->hasMethod( 'getElementById'), 'It must exist on the prototype');
            $this->assertEqualsData(gettype($this->doc->createDocumentFragment()->getElementById), 'function', 'It must exist on an instance');
        }, 'The method must exist');
        $this->assertTest(function () {
            $this->assertEqualsData($this->doc->createDocumentFragment()->getElementById('foo'), null);
            $this->assertEqualsData($this->doc->createDocumentFragment()->getElementById(''), null);
        }, 'It must return null when there are no matches');
        $this->assertTest(function () {
            $frag = $this->doc->createDocumentFragment();
            $frag->appendChild($this->doc->createElement('div'));
            $frag->appendChild($this->doc->createElement('span'));
            $frag->childNodes[0]->id = 'foo';
            $frag->childNodes[1]->id = 'foo';
            $this->assertEqualsData($frag->getElementById('foo'), $frag->childNodes[0]);
        }, 'It must return the first element when there are matches');
        $this->assertTest(function () {
            $frag = $this->doc->createDocumentFragment();
            $frag->appendChild($this->doc->createElement('div'));
            $frag->childNodes[0]->setAttribute('id', '');
            $this->assertEqualsData($frag->getElementById(''), null, 'Even if there is an element with an empty-string ID attribute, it must not be returned');
        }, 'Empty string ID values');
        $this->assertTest(function () {
            $frag = $this->doc->querySelector('template')->content;
            $this->assertTrueData($frag->getElementById('foo')->hasAttribute('data-yes'));
        }, 'It must return the first element when there are matches, using a template');
    }
}

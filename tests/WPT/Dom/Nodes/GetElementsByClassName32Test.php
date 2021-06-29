<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/getElementsByClassName-32.html.
class GetElementsByClassName32Test extends WPTTestHarness
{
    public function testGetElementsByClassName32()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/getElementsByClassName-32.html');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $p->className = 'unknown';
            $this->doc->body->appendChild($p);
            $elements = $this->doc->getElementsByClassName('first-p');
            $this->assertArrayEqualsData($elements, []);
        }, 'cannot find the class name');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $p->className = 'first-p';
            $this->doc->body->appendChild($p);
            $elements = $this->doc->getElementsByClassName('first-p');
            $this->assertArrayEqualsData($elements, [$p]);
        }, 'finds the class name');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $p->className = 'the-p second third';
            $this->doc->body->appendChild($p);
            $elements1 = $this->doc->getElementsByClassName('the-p');
            $this->assertArrayEqualsData($elements1, [$p]);
            $elements2 = $this->doc->getElementsByClassName('second');
            $this->assertArrayEqualsData($elements2, [$p]);
            $elements3 = $this->doc->getElementsByClassName('third');
            $this->assertArrayEqualsData($elements3, [$p]);
        }, 'finds the same element with multiple class names');
        $this->assertTest(function () {
            $elements = $this->doc->getElementsByClassName('df-article');
            $this->assertEqualsData(count($elements), 3);
            $this->assertArrayEqualsData($this->arrayMap($elements, function ($el) {
                return $el->id;
            }), ['1', '2', '3']);
        }, 'does not get confused by numeric IDs');
    }
}

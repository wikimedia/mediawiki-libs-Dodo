<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Text-splitText.html.
class TextSplitTextTest extends WptTestHarness
{
    public function testTextSplitText()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Text-splitText.html';
        $this->assertTest(function () {
            $text = $this->doc->createTextNode('camembert');
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$text) {
                $text->splitText(10);
            });
        }, 'Split text after end of data');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode('');
            $new_text = $text->splitText(0);
            $this->assertEqualsData($text->data, '');
            $this->assertEqualsData($new_text->data, '');
        }, 'Split empty text');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode("comté");
            $new_text = $text->splitText(0);
            $this->assertEqualsData($text->data, '');
            $this->assertEqualsData($new_text->data, "comté");
        }, 'Split text at beginning');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode("comté");
            $new_text = $text->splitText(5);
            $this->assertEqualsData($text->data, "comté");
            $this->assertEqualsData($new_text->data, '');
        }, 'Split text at end');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode("comté");
            $new_text = $text->splitText(3);
            $this->assertEqualsData($text->data, 'com');
            $this->assertEqualsData($new_text->data, "té");
            $this->assertEqualsData($new_text->parentNode, null);
        }, 'Split root');
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $text = $this->doc->createTextNode('bleu');
            $parent->appendChild($text);
            $new_text = $text->splitText(2);
            $this->assertEqualsData($text->data, 'bl');
            $this->assertEqualsData($new_text->data, 'eu');
            $this->assertEqualsData($text->nextSibling, $new_text);
            $this->assertEqualsData($new_text->parentNode, $parent);
        }, 'Split child');
    }
}

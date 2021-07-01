<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Text-splitText.html.
class TextSplitTextTest extends WPTTestHarness
{
    public function testTextSplitText()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Text-splitText.html');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode('camembert');
            $this->wptAssertThrowsDom('INDEX_SIZE_ERR', function () use(&$text) {
                $text->splitText(10);
            });
        }, 'Split text after end of data');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode('');
            $new_text = $text->splitText(0);
            $this->wptAssertEquals($text->data, '');
            $this->wptAssertEquals($new_text->data, '');
        }, 'Split empty text');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode("comté");
            $new_text = $text->splitText(0);
            $this->wptAssertEquals($text->data, '');
            $this->wptAssertEquals($new_text->data, "comté");
        }, 'Split text at beginning');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode("comté");
            $new_text = $text->splitText(5);
            $this->wptAssertEquals($text->data, "comté");
            $this->wptAssertEquals($new_text->data, '');
        }, 'Split text at end');
        $this->assertTest(function () {
            $text = $this->doc->createTextNode("comté");
            $new_text = $text->splitText(3);
            $this->wptAssertEquals($text->data, 'com');
            $this->wptAssertEquals($new_text->data, "té");
            $this->wptAssertEquals($new_text->parentNode, null);
        }, 'Split root');
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $text = $this->doc->createTextNode('bleu');
            $parent->appendChild($text);
            $new_text = $text->splitText(2);
            $this->wptAssertEquals($text->data, 'bl');
            $this->wptAssertEquals($new_text->data, 'eu');
            $this->wptAssertEquals($text->nextSibling, $new_text);
            $this->wptAssertEquals($new_text->parentNode, $parent);
        }, 'Split child');
    }
}

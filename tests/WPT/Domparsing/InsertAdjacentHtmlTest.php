<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/insert_adjacent_html.html.
class InsertAdjacentHtmlTest extends WPTTestHarness
{
    public function testPositions($node, $testDesc)
    {
        $this->assertTest(function () use(&$node) {
            $script_ran = false;
            $node->insertAdjacentHTML('beforeBegin', '<script>script_ran = true;</script><i></i>');
            $this->wptAssertEquals($node->getPreviousSibling()->localName, 'i', 'Should have had <i> as previous sibling');
            $this->wptAssertEquals($node->getPreviousSibling()->getPreviousSibling()->localName, 'script', 'Should have had <script> as second previous child');
            $this->wptAssertFalse($script_ran, 'script should not have run');
        }, 'beforeBegin ' . $node->id . ' ' . $testDesc);
        $this->assertTest(function () use(&$node) {
            $script_ran = false;
            $node->insertAdjacentHTML('Afterbegin', '<b></b><script>script_ran = true;</script>');
            $this->wptAssertEquals($node->firstChild->localName, 'b', 'Should have had <b> as first child');
            $this->wptAssertEquals($node->firstChild->nextSibling->localName, 'script', 'Should have had <script> as second child');
            $this->wptAssertFalse($script_ran, 'script should not have run');
        }, 'Afterbegin ' . $node->id . ' ' . $testDesc);
        $this->assertTest(function () use(&$node) {
            $script_ran = false;
            $node->insertAdjacentHTML('BeforeEnd', '<script>script_ran = true;</script><u></u>');
            $this->wptAssertEquals($node->lastChild->localName, 'u', 'Should have had <u> as last child');
            $this->wptAssertEquals($node->lastChild->getPreviousSibling()->localName, 'script', 'Should have had <script> as penultimate child');
            $this->wptAssertFalse($script_ran, 'script should not have run');
        }, 'BeforeEnd ' . $node->id . ' ' . $testDesc);
        $this->assertTest(function () use(&$node) {
            $script_ran = false;
            $node->insertAdjacentHTML('afterend', '<a></a><script>script_ran = true;</script>');
            $this->wptAssertEquals($node->nextSibling->localName, 'a', 'Should have had <a> as next sibling');
            $this->wptAssertEquals($node->nextSibling->nextSibling->localName, 'script', 'Should have had <script> as second next sibling');
            $this->wptAssertFalse($script_ran, 'script should not have run');
        }, 'afterend ' . $node->id . ' ' . $testDesc);
    }
    public function testThrowingNoParent($element, $desc)
    {
        $this->assertTest(function () use(&$element) {
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () use(&$element) {
                $element->insertAdjacentHTML('afterend', '');
            });
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () use(&$element) {
                $element->insertAdjacentHTML('beforebegin', '');
            });
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () use(&$element) {
                $element->insertAdjacentHTML('afterend', 'foo');
            });
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () use(&$element) {
                $element->insertAdjacentHTML('beforebegin', 'foo');
            });
        }, 'When the parent node is ' . $desc . ', insertAdjacentHTML should throw for beforebegin and afterend (text)');
        $this->assertTest(function () use(&$element) {
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () use(&$element) {
                $element->insertAdjacentHTML('afterend', '<!-- fail -->');
            });
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () use(&$element) {
                $element->insertAdjacentHTML('beforebegin', '<!-- fail -->');
            });
        }, 'When the parent node is ' . $desc . ', insertAdjacentHTML should throw for beforebegin and afterend (comments)');
        $this->assertTest(function () use(&$element) {
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () use(&$element) {
                $element->insertAdjacentHTML('afterend', '<div></div>');
            });
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () use(&$element) {
                $element->insertAdjacentHTML('beforebegin', '<div></div>');
            });
        }, 'When the parent node is ' . $desc . ', insertAdjacentHTML should throw for beforebegin and afterend (elements)');
    }
    public function testInsertAdjacentHtml()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/insert_adjacent_html.html');
        $script_ran = false;
        $content = $this->doc->getElementById('content');
        testPositions($content, 'without next sibling');
        testPositions($content, 'again, with next sibling');
        $this->assertTest(function () use(&$content) {
            $this->wptAssertThrowsDom('SYNTAX_ERR', function () use(&$content) {
                $content->insertAdjacentHTML('bar', 'foo');
            });
            $this->wptAssertThrowsDom('SYNTAX_ERR', function () use(&$content) {
                $content->insertAdjacentHTML("beforebegİn", 'foo');
            });
            $this->wptAssertThrowsDom('SYNTAX_ERR', function () use(&$content) {
                $content->insertAdjacentHTML("beforebegın", 'foo');
            });
        }, 'Should throw when inserting with invalid position string');
        $parentElement = $this->doc->createElement('div');
        $child = $this->doc->createElement('div');
        $child->id = 'child';
        testThrowingNoParent($child, 'null');
        testThrowingNoParent($this->doc->documentElement, 'a document');
        $this->assertTest(function () use(&$child, &$parentElement) {
            $child->insertAdjacentHTML('afterBegin', 'foo');
            $child->insertAdjacentHTML('beforeend', 'bar');
            $this->wptAssertEquals($child->textContent, 'foobar');
            $parentElement->appendChild($child);
        }, 'Inserting after being and before end should order things correctly');
        testPositions($child, 'node not in tree but has parent');
        $this->assertTest(function () use(&$content, &$parentElement) {
            $script_ran = false;
            $content->appendChild($parentElement);
            // must not run scripts
            $this->wptAssertFalse($script_ran, 'script should not have run');
        }, 'Should not run script when appending things which have descendant <script> inserted via insertAdjacentHTML');
        $content2 = $this->doc->getElementById('content2');
        testPositions($content2, 'without next sibling');
        testPositions($content2, "test again, now that there's a next sibling");
        // HTML only
        $this->assertTest(function () {
            $this->doc->body->insertAdjacentHTML('afterend', '<p>');
            $this->doc->head->insertAdjacentHTML('beforebegin', '<p>');
            $this->wptAssertEquals(count($this->doc->getElementsByTagName('head')), 1, 'Should still have one head');
            $this->wptAssertEquals(count($this->doc->getElementsByTagName('body')), 1, 'Should still have one body');
        }, 'Inserting kids of the <html> element should not do weird things with implied <body>/<head> tags');
    }
}

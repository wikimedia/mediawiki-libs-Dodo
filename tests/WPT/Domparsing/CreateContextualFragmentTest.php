<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/createContextualFragment.html.
class CreateContextualFragmentTest extends WPTTestHarness
{
    public function helperTestEquivalence($element1, $fragment1, $element2, $fragment2)
    {
        $range1 = $element1->ownerDocument->createRange();
        $range1->selectNodeContents($element1);
        $range2 = $element2->ownerDocument->createRange();
        $range2->selectNodeContents($element2);
        $result1 = $range1->createContextualFragment($fragment1);
        $result2 = $range2->createContextualFragment($fragment2);
        $this->wptAssertTrue($result1->isEqualNode($result2), 'Results are supposed to be equivalent');
        // Throw in partial ownerDocument tests on the side, since the algorithm
        // does specify that and we don't want to completely not test it.
        if ($result1->firstChild != null) {
            $this->wptAssertEquals($result1->firstChild->ownerDocument, $element1->ownerDocument, 'ownerDocument must be set to that of the reference node');
        }
        if ($result2->firstChild != null) {
            $this->wptAssertEquals($result2->firstChild->ownerDocument, $element2->ownerDocument, 'ownerDocument must be set to that of the reference node');
        }
    }
    public function testCreateContextualFragment()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/createContextualFragment.html');
        // We are not testing XML documents here, because apparently it's not clear
        // what we want to happen there.  We also aren't testing the HTML parser in any
        // depth, just some basic sanity checks.
        // Exception-throwing
        $this->assertTest(function () {
            $range = $this->doc->createRange();
            $range->detach();
            $range->createContextualFragment('');
        }, 'Must not throw INVALID_STATE_ERR for a detached node.');
        $this->assertTest(function () {
            $range = $this->doc->createRange();
            $this->wptAssertThrowsJs($this->type_error, function () use (&$range) {
                $range->createContextualFragment();
            });
        }, 'Must throw TypeError when calling without arguments');
        $this->assertTest(function () {
            // Simple test
            $range = $this->doc->createRange();
            $range->selectNodeContents($this->doc->body);
            $fragment = '<p CLaSs=testclass> Hi! <p>Hi!';
            $expected = $this->doc->createDocumentFragment();
            $tmpNode = $this->doc->createElement('p');
            $tmpNode->setAttribute('class', 'testclass');
            $tmpNode->appendChild($this->doc->createTextNode(' Hi! '));
            $expected->appendChild($tmpNode);
            $tmpNode = $this->doc->createElement('p');
            $tmpNode->appendChild($this->doc->createTextNode('Hi!'));
            $expected->appendChild($tmpNode);
            $result = $range->createContextualFragment($fragment);
            $this->wptAssertTrue($expected->isEqualNode($result), 'Unexpected result (collapsed Range)');
            // Token test that the end node makes no difference
            $range->setEnd($this->doc->body->getElementsByTagName('script')[0], 0);
            $result = $range->createContextualFragment($fragment);
            $this->wptAssertTrue($expected->isEqualNode($result), 'Unexpected result (Range ends in <script>)');
        }, 'Simple test with paragraphs');
        $this->assertTest(function () {
            // This test based on https://bugzilla.mozilla.org/show_bug.cgi?id=585819,
            // from a real-world compat bug
            $range = $this->doc->createRange();
            $range->selectNodeContents($this->doc->documentElement);
            $fragment = '<span>Hello world</span>';
            $expected = $this->doc->createDocumentFragment();
            $tmpNode = $this->doc->createElement('span');
            $tmpNode->appendChild($this->doc->createTextNode('Hello world'));
            $expected->appendChild($tmpNode);
            $result = $range->createContextualFragment($fragment);
            $this->wptAssertTrue($expected->isEqualNode($result), 'Unexpected result (collapsed Range)');
            // Another token test that the end node makes no difference
            $range->setEnd($this->doc->head, 0);
            $result = $range->createContextualFragment($fragment);
            $this->wptAssertTrue($expected->isEqualNode($result), 'Unexpected result (Range ends in <head>)');
        }, "Don't auto-create <body> when applied to <html>");
        // Scripts should be run if inserted (that's what the "Unmark all scripts
        // . . ." line means, I'm told)
        $passed = false;
        $this->assertTest(function () use (&$passed) {
            $this->wptAssertFalse($passed, 'Sanity check');
            $range = $this->doc->createRange();
            $range->selectNodeContents($this->doc->documentElement);
            $fragment = $range->createContextualFragment('<script>passed = true</s' . 'cript>');
            $this->wptAssertFalse($passed, 'Fragment created but not yet added to document, should not have run');
            $this->doc->body->appendChild($fragment);
            $this->wptAssertTrue($passed, 'Fragment created and added to document, should run');
        }, '<script>s should be run when appended to the document (but not before)');
        foreach ([
            // Void
            'area',
            'base',
            'basefont',
            'bgsound',
            'br',
            'col',
            'embed',
            'frame',
            'hr',
            'img',
            'input',
            'keygen',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr',
            // Historical
            'menuitem',
            'image',
        ] as $name) {
            $this->assertTest(function () use (&$name) {
                $range = $this->doc->createRange();
                $contextNode = $this->doc->createElement($name);
                $selectedNode = $this->doc->createElement('div');
                $contextNode->appendChild($selectedNode);
                $range->selectNode($selectedNode);
                $range->createContextualFragment('some text');
            }, "createContextualFragment should work even when the context is <>{$name}");
        }
        $doc_fragment = $this->doc->createDocumentFragment();
        $comment = $this->doc->createComment('~o~');
        $doc_fragment->appendChild($comment);
        $tests = [['<html> and <body> must work the same, 1', $this->doc->documentElement, '<span>Hello world</span>', $this->doc->body, '<span>Hello world</span>'], ['<html> and <body> must work the same, 2', $this->doc->documentElement, '<body><p>Hello world', $this->doc->body, '<body><p>Hello world'], ['Implicit <body> creation', $this->doc->documentElement, '<body><p>', $this->doc->documentElement, '<p>'], ["Namespace generally shouldn't matter", $this->doc->createElementNS('http://fake-namespace', 'div'), '<body><p><span>Foo', $this->doc->createElement('div'), '<body><p><span>Foo'], ["<html> in a different namespace shouldn't be special", $this->doc->createElementNS('http://fake-namespace', 'html'), '<body><p>', $this->doc->createElement('div'), '<body><p>'], ["SVG namespace shouldn't be special", $this->doc->createElementNS('http://www.w3.org/2000/svg', 'div'), '<body><p>', $this->doc->createElement('div'), '<body><p>'], ['null should be stringified', $this->doc->createElement('span'), null, $this->doc->createElement('span'), 'null'], ['undefined should be stringified', $this->doc->createElement('span'), null, $this->doc->createElement('span'), NULL], ["Text nodes shouldn't be special", $this->doc->createTextNode('?'), '<body><p>', $this->doc->createElement('div'), '<body><p>'], ['Non-Element parent should not be special', $comment, '<body><p>', $this->doc->createElement('div'), '<body><p>']];
        $this->generateTests([$this, 'helperTestEquivalence'], $tests);
    }
}

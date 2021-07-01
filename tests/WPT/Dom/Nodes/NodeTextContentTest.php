<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-textContent.html.
class NodeTextContentTest extends WPTTestHarness
{
    public function testNodeTextContent()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-textContent.html');
        // XXX mutation observers?
        // XXX Range gravitation?
        $this->docs = null;
        $doctypes = null;
        // setup()
        $this->docs = [[$this->doc, 'parser'], [$this->doc->implementation->createDocument('', 'test', null), 'createDocument'], [$this->doc->implementation->createHTMLDocument('title'), 'createHTMLDocument']];
        $doctypes = [[$this->doc->doctype, 'parser'], [$this->doc->implementation->createDocumentType('x', '', ''), 'script']];
        // Getting
        // DocumentFragment, Element:
        $this->assertTest(function () {
            $element = $this->doc->createElement('div');
            $this->wptAssertEquals($element->textContent, '');
        }, 'For an empty Element, textContent should be the empty string');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createDocumentFragment()->textContent, '');
        }, 'For an empty DocumentFragment, textContent should be the empty string');
        $this->assertTest(function () {
            $el = $this->doc->createElement('div');
            $el->appendChild($this->doc->createComment(' abc '));
            $el->appendChild($this->doc->createTextNode("\tDEF\t"));
            $el->appendChild($this->doc->createProcessingInstruction('x', ' ghi '));
            $this->wptAssertEquals($el->textContent, "\tDEF\t");
        }, 'Element with children');
        $this->assertTest(function () {
            $el = $this->doc->createElement('div');
            $child = $this->doc->createElement('div');
            $el->appendChild($child);
            $child->appendChild($this->doc->createComment(' abc '));
            $child->appendChild($this->doc->createTextNode("\tDEF\t"));
            $child->appendChild($this->doc->createProcessingInstruction('x', ' ghi '));
            $this->wptAssertEquals($el->textContent, "\tDEF\t");
        }, 'Element with descendants');
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $df->appendChild($this->doc->createComment(' abc '));
            $df->appendChild($this->doc->createTextNode("\tDEF\t"));
            $df->appendChild($this->doc->createProcessingInstruction('x', ' ghi '));
            $this->wptAssertEquals($df->textContent, "\tDEF\t");
        }, 'DocumentFragment with children');
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $child = $this->doc->createElement('div');
            $df->appendChild($child);
            $child->appendChild($this->doc->createComment(' abc '));
            $child->appendChild($this->doc->createTextNode("\tDEF\t"));
            $child->appendChild($this->doc->createProcessingInstruction('x', ' ghi '));
            $this->wptAssertEquals($df->textContent, "\tDEF\t");
        }, 'DocumentFragment with descendants');
        // Text, ProcessingInstruction, Comment:
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createTextNode('')->textContent, '');
        }, 'For an empty Text, textContent should be the empty string');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createProcessingInstruction('x', '')->textContent, '');
        }, 'For an empty ProcessingInstruction, textContent should be the empty string');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createComment('')->textContent, '');
        }, 'For an empty Comment, textContent should be the empty string');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createTextNode('abc')->textContent, 'abc');
        }, 'For a Text with data, textContent should be that data');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createProcessingInstruction('x', 'abc')->textContent, 'abc');
        }, 'For a ProcessingInstruction with data, textContent should be that data');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createComment('abc')->textContent, 'abc');
        }, 'For a Comment with data, textContent should be that data');
        // Any other node:
        foreach ($docs as $argument) {
            $doc = $argument[0];
            $creator = $argument[1];
            $this->assertTest(function () use(&$doc) {
                $this->wptAssertEquals($doc->textContent, null);
            }, 'For Documents created by ' . $creator . ', textContent should be null');
        }
        foreach ($doctypes as $argument) {
            $doctype = $argument[0];
            $creator = $argument[1];
            $this->assertTest(function () use(&$doctype) {
                $this->wptAssertEquals($doctype->textContent, null);
            }, 'For DocumentType created by ' . $creator . ', textContent should be null');
        }
        // Setting
        // DocumentFragment, Element:
        $testArgs = [[null, null], [null, null], ['', null], [42, '42'], ['abc', 'abc'], ['<b>xyz</b>', '<b>xyz</b>'], ["d\\u0000e", "d\\u0000e"]];
        foreach ($testArgs as $aValue) {
            $argument = $aValue[0];
            $expectation = $aValue[1];
            $check = function ($aElementOrDocumentFragment) use(&$expectation) {
                if ($expectation === null) {
                    $this->wptAssertEquals($aElementOrDocumentFragment->textContent, '');
                    $this->wptAssertEquals($aElementOrDocumentFragment->firstChild, null);
                } else {
                    $this->wptAssertEquals($aElementOrDocumentFragment->textContent, $expectation);
                    $this->wptAssertEquals(count($aElementOrDocumentFragment->childNodes), 1, 'Should have one child');
                    $firstChild = $aElementOrDocumentFragment->firstChild;
                    $this->wptAssertTrue($firstChild instanceof Text, 'child should be a Text');
                    $this->wptAssertEquals($firstChild->data, $expectation);
                }
            };
            $this->assertTest(function () use(&$argument, &$check) {
                $el = $this->doc->createElement('div');
                $el->textContent = $argument;
                $check($el);
            }, 'Element without children set to ' . $this->formatValue($argument));
            $this->assertTest(function () use(&$argument, &$check) {
                $el = $this->doc->createElement('div');
                $text = $el->appendChild($this->doc->createTextNode(''));
                $el->textContent = $argument;
                $check($el);
                $this->wptAssertEquals($text->parentNode, null, 'Preexisting Text should have been removed');
            }, 'Element with empty text node as child set to ' . $this->formatValue($argument));
            $this->assertTest(function () use(&$argument, &$check) {
                $el = $this->doc->createElement('div');
                $el->appendChild($this->doc->createComment(' abc '));
                $el->appendChild($this->doc->createTextNode("\tDEF\t"));
                $el->appendChild($this->doc->createProcessingInstruction('x', ' ghi '));
                $el->textContent = $argument;
                $check($el);
            }, 'Element with children set to ' . $this->formatValue($argument));
            $this->assertTest(function () use(&$argument, &$check) {
                $el = $this->doc->createElement('div');
                $child = $this->doc->createElement('div');
                $el->appendChild($child);
                $child->appendChild($this->doc->createComment(' abc '));
                $child->appendChild($this->doc->createTextNode("\tDEF\t"));
                $child->appendChild($this->doc->createProcessingInstruction('x', ' ghi '));
                $el->textContent = $argument;
                $check($el);
                $this->wptAssertEquals(count($child->childNodes), 3, 'Should not have changed the internal structure of the removed nodes.');
            }, 'Element with descendants set to ' . $this->formatValue($argument));
            $this->assertTest(function () use(&$argument, &$check) {
                $df = $this->doc->createDocumentFragment();
                $df->textContent = $argument;
                $check($df);
            }, 'DocumentFragment without children set to ' . $this->formatValue($argument));
            $this->assertTest(function () use(&$argument, &$check) {
                $df = $this->doc->createDocumentFragment();
                $text = $df->appendChild($this->doc->createTextNode(''));
                $df->textContent = $argument;
                $check($df);
                $this->wptAssertEquals($text->parentNode, null, 'Preexisting Text should have been removed');
            }, 'DocumentFragment with empty text node as child set to ' . $this->formatValue($argument));
            $this->assertTest(function () use(&$argument, &$check) {
                $df = $this->doc->createDocumentFragment();
                $df->appendChild($this->doc->createComment(' abc '));
                $df->appendChild($this->doc->createTextNode("\tDEF\t"));
                $df->appendChild($this->doc->createProcessingInstruction('x', ' ghi '));
                $df->textContent = $argument;
                $check($df);
            }, 'DocumentFragment with children set to ' . $this->formatValue($argument));
            $this->assertTest(function () use(&$argument, &$check) {
                $df = $this->doc->createDocumentFragment();
                $child = $this->doc->createElement('div');
                $df->appendChild($child);
                $child->appendChild($this->doc->createComment(' abc '));
                $child->appendChild($this->doc->createTextNode("\tDEF\t"));
                $child->appendChild($this->doc->createProcessingInstruction('x', ' ghi '));
                $df->textContent = $argument;
                $check($df);
                $this->wptAssertEquals(count($child->childNodes), 3, 'Should not have changed the internal structure of the removed nodes.');
            }, 'DocumentFragment with descendants set to ' . $this->formatValue($argument));
        }
        // Text, ProcessingInstruction, Comment:
        $this->assertTest(function () {
            $text = $this->doc->createTextNode('abc');
            $text->textContent = 'def';
            $this->wptAssertEquals($text->textContent, 'def');
            $this->wptAssertEquals($text->data, 'def');
        }, 'For a Text, textContent should set the data');
        $this->assertTest(function () {
            $pi = $this->doc->createProcessingInstruction('x', 'abc');
            $pi->textContent = 'def';
            $this->wptAssertEquals($pi->textContent, 'def');
            $this->wptAssertEquals($pi->data, 'def');
            $this->wptAssertEquals($pi->target, 'x');
        }, 'For a ProcessingInstruction, textContent should set the data');
        $this->assertTest(function () {
            $comment = $this->doc->createComment('abc');
            $comment->textContent = 'def';
            $this->wptAssertEquals($comment->textContent, 'def');
            $this->wptAssertEquals($comment->data, 'def');
        }, 'For a Comment, textContent should set the data');
        // Any other node:
        foreach ($docs as $argument) {
            $doc = $argument[0];
            $creator = $argument[1];
            $this->assertTest(function () use(&$doc) {
                $root = $doc->documentElement;
                $doc->textContent = 'a';
                $this->wptAssertEquals($doc->textContent, null);
                $this->wptAssertEquals($doc->documentElement, $root);
            }, 'For Documents created by ' . $creator . ', setting textContent should do nothing');
        }
        foreach ($doctypes as $argument) {
            $doctype = $argument[0];
            $creator = $argument[1];
            $this->assertTest(function () use(&$doctype) {
                $props = ['name' => $doctype->name, 'publicId' => $doctype->publicId, 'systemId' => $doctype->systemId];
                $doctype->textContent = 'b';
                $this->wptAssertEquals($doctype->textContent, null);
                $this->wptAssertEquals($doctype->name, $props->name, 'name should not change');
                $this->wptAssertEquals($doctype->publicId, $props->publicId, 'publicId should not change');
                $this->wptAssertEquals($doctype->systemId, $props->systemId, 'systemId should not change');
            }, 'For DocumentType created by ' . $creator . ', setting textContent should do nothing');
        }
    }
}

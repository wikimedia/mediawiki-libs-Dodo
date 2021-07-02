<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Traversal;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-basic.html.
class TreeWalkerBasicTest extends WPTTestHarness
{
    public function createSampleDOM()
    {
        // Tree structure:
        //             #a
        //             |
        //        +----+----+
        //        |         |
        //       "b"        #c
        //                  |
        //             +----+----+
        //             |         |
        //            #d      <!--j-->
        //             |
        //        +----+----+
        //        |    |    |
        //       "e"  #f   "i"
        //             |
        //          +--+--+
        //          |     |
        //         "g" <!--h-->
        $div = $this->doc->createElement('div');
        $div->id = 'a';
        // div.innerHTML = 'b<div id="c"><div id="d">e<span id="f">g<!--h--></span>i</div><!--j--></div>';
        $div->appendChild($this->doc->createTextNode('b'));
        $c = $this->doc->createElement('div');
        $c->id = 'c';
        $div->appendChild($c);
        $d = $this->doc->createElement('div');
        $d->id = 'd';
        $c->appendChild($d);
        $e = $this->doc->createTextNode('e');
        $d->appendChild($e);
        $f = $this->doc->createElement('span');
        $f->id = 'f';
        $d->appendChild($f);
        $g = $this->doc->createTextNode('g');
        $f->appendChild($g);
        $h = $this->doc->createComment('h');
        $f->appendChild($h);
        $i = $this->doc->createTextNode('i');
        $d->appendChild($i);
        $j = $this->doc->createComment('j');
        $c->appendChild($j);
        return $div;
    }
    public function checkWalker($walker, $root, $whatToShowValue)
    {
        $whatToShowValue = $whatToShowValue === null ? 0xffffffff : $whatToShowValue;
        $this->wptAssertEquals($walker, '[object TreeWalker]', 'toString');
        $this->wptAssertEquals($walker->root, $root, 'root');
        $this->wptAssertEquals($walker->whatToShow, $whatToShowValue, 'whatToShow');
        $this->wptAssertEquals($walker->filter, null, 'filter');
        $this->wptAssertEquals($walker->currentNode, $root, 'currentNode');
        $this->wptAssertReadonly($walker, 'root');
        $this->wptAssertReadonly($walker, 'whatToShow');
        $this->wptAssertReadonly($walker, 'filter');
    }
    public function assertNode($actual, $expected)
    {
        $this->wptAssertTrue($actual instanceof $expected->type, 'Node type mismatch: actual = ' . $actual->nodeType . ', expected = ' . $expected->nodeType);
        if (gettype($expected->id) !== NULL) {
            $this->wptAssertEquals($actual->id, $expected->id);
        }
        if (gettype($expected->nodeValue) !== NULL) {
            $this->wptAssertEquals($actual->nodeValue, $expected->nodeValue);
        }
    }
    public function testTreeWalkerBasic()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-basic.html');
        $this->assertTest(function () {
            $root = $this->createSampleDOM();
            $walker = $this->doc->createTreeWalker($root);
            $this->checkWalker($walker, $root);
        }, 'Construct a TreeWalker by document.createTreeWalker(root).');
        $this->assertTest(function () {
            $root = $this->createSampleDOM();
            $walker = $this->doc->createTreeWalker($root, null, null);
            $this->checkWalker($walker, $root, 0);
        }, 'Construct a TreeWalker by document.createTreeWalker(root, null, null).');
        $this->assertTest(function () {
            $root = $this->createSampleDOM();
            $walker = $this->doc->createTreeWalker($root, null, null);
            $this->checkWalker($walker, $root);
        }, 'Construct a TreeWalker by document.createTreeWalker(root, undefined, undefined).');
        $this->assertTest(function () {
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->createTreeWalker();
            });
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->createTreeWalker(null);
            });
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->createTreeWalker(null);
            });
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->createTreeWalker(new Object());
            });
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->createTreeWalker(1);
            });
        }, 'Give an invalid root node to document.createTreeWalker().');
        $this->assertTest(function () {
            $root = $this->createSampleDOM();
            $walker = $this->doc->createTreeWalker($root);
            $f = $root->lastChild->firstChild->childNodes[1];
            // An element node: div#f.
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'a']);
            $this->wptAssertEquals($walker->parentNode(), null);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'a']);
            $this->wptAssertNode($walker->firstChild(), ['type' => Text, 'nodeValue' => 'b']);
            $this->wptAssertNode($walker->currentNode, ['type' => Text, 'nodeValue' => 'b']);
            $this->wptAssertNode($walker->nextSibling(), ['type' => Element, 'id' => 'c']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'c']);
            $this->wptAssertNode($walker->lastChild(), ['type' => Comment, 'nodeValue' => 'j']);
            $this->wptAssertNode($walker->currentNode, ['type' => Comment, 'nodeValue' => 'j']);
            $this->wptAssertNode($walker->getPreviousSibling()(), ['type' => Element, 'id' => 'd']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'd']);
            $this->wptAssertNode($walker->nextNode(), ['type' => Text, 'nodeValue' => 'e']);
            $this->wptAssertNode($walker->currentNode, ['type' => Text, 'nodeValue' => 'e']);
            $this->wptAssertNode($walker->parentNode(), ['type' => Element, 'id' => 'd']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'd']);
            $this->wptAssertNode($walker->previousNode(), ['type' => Element, 'id' => 'c']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'c']);
            $this->wptAssertEquals($walker->nextSibling(), null);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'c']);
            $walker->currentNode = $f;
            $this->wptAssertEquals($walker->currentNode, $f);
        }, 'Walk over nodes.');
        $this->assertTest(function () {
            $treeWalker = $this->doc->createTreeWalker($this->doc->body, 42, null);
            $this->wptAssertEquals($treeWalker->root, $this->doc->body);
            $this->wptAssertEquals($treeWalker->currentNode, $this->doc->body);
            $this->wptAssertEquals($treeWalker->whatToShow, 42);
            $this->wptAssertEquals($treeWalker->filter, null);
        }, 'Optional arguments to createTreeWalker should be optional (3 passed, null).');
    }
}

<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-matches.html.
class ElementMatchesTest extends WptTestHarness
{
    public function interfaceCheckMatches($method, $type, $obj)
    {
        if ($obj->nodeType === $obj::ELEMENT_NODE) {
            $this->assertTest(function () use(&$obj, &$method, &$type) {
                $this->assertIdlAttributeData($obj, $method, $type . ' supports ' . $method);
            }, $type . ' supports ' . $method);
        } else {
            $this->assertTest(function () use(&$obj, &$method, &$type) {
                $this->assertFalseData(isset($obj[$method]), $type . ' supports ' . $method);
            }, $type . ' should not support ' . $method);
        }
    }
    public function runSpecialMatchesTests($method, $type, $element)
    {
        $this->assertTest(function () use(&$element, &$method) {
            // 1
            if (strtolower($element->tagName) === 'null') {
                $this->assertTrueData($element[$method](null), "An element with the tag name '" . strtolower($element->tagName) . "' should match.");
            } else {
                $this->assertFalseData($element[$method](null), "An element with the tag name '" . strtolower($element->tagName) . "' should not match.");
            }
        }, $type . '.' . $method . '(null)');
        $this->assertTest(function () use(&$element, &$method) {
            // 2
            if (strtolower($element->tagName) === NULL) {
                $this->assertTrueData($element[$method](null), "An element with the tag name '" . strtolower($element->tagName) . "' should match.");
            } else {
                $this->assertFalseData($element[$method](null), "An element with the tag name '" . strtolower($element->tagName) . "' should not match.");
            }
        }, $type . '.' . $method . '(undefined)');
        $this->assertTest(function () use(&$element, &$method) {
            // 3
            $this->assertThrowsJsData($element->ownerDocument->defaultView->TypeError, function () use(&$element, &$method) {
                $element[$method]();
            }, 'This should throw a TypeError.');
        }, $type . '.' . $method . ' no parameter');
    }
    public function runInvalidSelectorTestMatches($method, $type, $root, $selectors)
    {
        if ($root->nodeType === $root::ELEMENT_NODE) {
            for ($i = 0; $i < count($selectors); $i++) {
                $s = $selectors[$i];
                $n = $s['name'];
                $q = $s['selector'];
                $this->assertTest(function () use(&$root, &$method, &$q) {
                    $this->assertThrowsDomData('SyntaxError', $root->ownerDocument->defaultView->DOMException, function () use(&$root, &$method, &$q) {
                        $root[$method]($q);
                    });
                }, $type . '.' . $method . ': ' . $n . ': ' . $q);
            }
        }
    }
    public function runMatchesTest($method, $type, $root, $selectors, $docType)
    {
        $nodeType = $this->getNodeType($root);
        for ($i = 0; $i < count($selectors); $i++) {
            $s = $selectors[$i];
            $n = $s['name'];
            $q = $s['selector'];
            $e = $s['expect'];
            $u = $s['unexpected'];
            $ctx = $s['ctx'];
            $ref = $s['ref'];
            if ((!$s['exclude'] || array_search($nodeType, $s['exclude']) === -1 && array_search($docType, $s['exclude']) === -1) && $s['testType'] & $TEST_MATCH) {
                if ($ctx && !$ref) {
                    $this->assertTest(function () use(&$e, &$root, &$ctx, &$method, &$q, &$u) {
                        $j = null;
                        $element = null;
                        $refNode = null;
                        for ($j = 0; $j < count($e); $j++) {
                            $element = $root->querySelector('#' . $e[$j]);
                            $refNode = $root->querySelector($ctx);
                            $this->assertTrueData($element[$method]($q, $refNode), 'The element #' . $e[$j] . ' should match the selector.');
                        }
                        if ($u) {
                            for ($j = 0; $j < count($u); $j++) {
                                $element = $root->querySelector('#' . $u[$j]);
                                $refNode = $root->querySelector($ctx);
                                $this->assertFalseData($element[$method]($q, $refNode), 'The element #' . $u[$j] . ' should not match the selector.');
                            }
                        }
                    }, $type . ' Element.' . $method . ': ' . $n . ' (with refNode Element): ' . $q);
                }
                if ($ref) {
                    $this->assertTest(function () use(&$e, &$root, &$ref, &$method, &$q, &$u) {
                        $j = null;
                        $element = null;
                        $refNodes = null;
                        for ($j = 0; $j < count($e); $j++) {
                            $element = $root->querySelector('#' . $e[$j]);
                            $refNodes = $root->querySelectorAll($ref);
                            $this->assertTrueData($element[$method]($q, $refNodes), 'The element #' . $e[$j] . ' should match the selector.');
                        }
                        if ($u) {
                            for ($j = 0; $j < count($u); $j++) {
                                $element = $root->querySelector('#' . $u[$j]);
                                $refNodes = $root->querySelectorAll($ref);
                                $this->assertFalseData($element[$method]($q, $refNodes), 'The element #' . $u[$j] . ' should not match the selector.');
                            }
                        }
                    }, $type . ' Element.' . $method . ': ' . $n . ' (with refNodes NodeList): ' . $q);
                }
                if (!$ctx && !$ref) {
                    $this->assertTest(function () use(&$e, &$root, &$method, &$q, &$u) {
                        for ($j = 0; $j < count($e); $j++) {
                            $element = $root->querySelector('#' . $e[$j]);
                            $this->assertTrueData($element[$method]($q), 'The element #' . $e[$j] . ' should match the selector.');
                        }
                        if ($u) {
                            for ($j = 0; $j < count($u); $j++) {
                                $element = $root->querySelector('#' . $u[$j]);
                                $this->assertFalseData($element[$method]($q), 'The element #' . $u[$j] . ' should not match the selector.');
                            }
                        }
                    }, $type . ' Element.' . $method . ': ' . $n . ' (with no refNodes): ' . $q);
                }
            }
        }
    }
    public function init($e, $method)
    {
        /*
         * This test suite tests Selectors API methods in 4 different contexts:
         * 1. Document node
         * 2. In-document Element node
         * 3. Detached Element node (an element with no parent, not in the document)
         * 4. Document Fragment node
         *
         * For each context, the following tests are run:
         *
         * The interface check tests ensure that each type of node exposes the Selectors API methods.
         *
         * The matches() tests are run
         * All the selectors tested for both the valid and invalid selector tests are found in selectors.js.
         * See comments in that file for documentation of the format used.
         *
         * The level2-lib.js file contains all the common test functions for running each of the aforementioned tests
         */
        $docType = 'html';
        // Only run tests suitable for HTML
        // Prepare the nodes for testing
        $doc = $e->target->getOwnerDocument();
        // Document Node tests
        $element = $doc->getElementById('root');
        // In-document Element Node tests
        //Setup the namespace tests
        setupSpecialElements($doc, $element);
        $outOfScope = $element->cloneNode(true);
        // Append this to the body before running the in-document
        // Element tests, but after running the Document tests. This
        // tests that no elements that are not descendants of element
        // are selected.
        traverse($outOfScope, function ($elem) {
            // Annotate each element as being a clone; used for verifying
            $elem->setAttribute('data-clone', '');
            // that none of these elements ever match.
        });
        $detached = $element->cloneNode(true);
        // Detached Element Node tests
        $fragment = $doc->createDocumentFragment();
        // Fragment Node tests
        $fragment->appendChild($element->cloneNode(true));
        // Setup Tests
        $this->interfaceCheckMatches($method, 'Document', $doc);
        $this->interfaceCheckMatches($method, 'Detached Element', $detached);
        $this->interfaceCheckMatches($method, 'Fragment', $fragment);
        $this->interfaceCheckMatches($method, 'In-document Element', $element);
        $this->runSpecialMatchesTests($method, 'DIV Element', $element);
        $this->runSpecialMatchesTests($method, 'NULL Element', $this->doc->createElement('null'));
        $this->runSpecialMatchesTests($method, 'UNDEFINED Element', $this->doc->createElement(NULL));
        $this->runInvalidSelectorTestMatches($method, 'Document', $doc, $this->invalidSelectors);
        $this->runInvalidSelectorTestMatches($method, 'Detached Element', $detached, $this->invalidSelectors);
        $this->runInvalidSelectorTestMatches($method, 'Fragment', $fragment, $this->invalidSelectors);
        $this->runInvalidSelectorTestMatches($method, 'In-document Element', $element, $this->invalidSelectors);
        $this->runMatchesTest($method, 'In-document', $doc, $this->validSelectors, 'html');
        $this->runMatchesTest($method, 'Detached', $detached, $this->validSelectors, 'html');
        $this->runMatchesTest($method, 'Fragment', $fragment, $this->validSelectors, 'html');
        $this->runMatchesTest($method, 'In-document', $doc, $scopedSelectors, 'html');
    }
    public function testElementMatches()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-matches.html');
        $this->asyncTest(function () {
            $frame = $this->doc->createElement('iframe');
            $frame->onload = $this->step_func_done(function ($e) {
                return $this->init($e, 'matches');
            });
            $frame->src = '/dom/nodes/ParentNode-querySelector-All-content.html#target';
            $this->doc->body->appendChild($frame);
        });
    }
}

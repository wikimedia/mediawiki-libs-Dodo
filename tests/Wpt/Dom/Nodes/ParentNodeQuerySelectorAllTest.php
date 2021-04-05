<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-All.html.
class ParentNodeQuerySelectorAllTest extends WptTestHarness
{
    public function init($target)
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
         * The interface check tests ensure that each type of node exposes the Selectors API methods
         *
         * The special selector tests verify the result of passing special values for the selector parameter,
         * to ensure that the correct WebIDL processing is performed, such as stringification of null and
         * undefined and missing parameter. The universal selector is also tested here, rather than with the
         * rest of ordinary selectors for practical reasons.
         *
         * The static list verification tests ensure that the node lists returned by the method remain unchanged
         * due to subsequent document modication, and that a new list is generated each time the method is
         * invoked based on the current state of the document.
         *
         * The invalid selector tests ensure that SyntaxError is thrown for invalid forms of selectors
         *
         * The valid selector tests check the result from querying many different types of selectors, with a
         * list of expected elements. This checks that querySelector() always returns the first result from
         * querySelectorAll(), and that all matching elements are correctly returned in tree-order. The tests
         * can be limited by specifying the test types to run, using the testType variable. The constants for this
         * can be found in selectors.js.
         *
         * All the selectors tested for both the valid and invalid selector tests are found in selectors.js.
         * See comments in that file for documentation of the format used.
         *
         * The ParentNode-querySelector-All.js file contains all the common test functions for running each of the aforementioned tests
         */
        $testType = $TEST_QSA;
        $docType = 'html';
        // Only run tests suitable for HTML
        // Prepare the nodes for testing
        $doc = $target->getOwnerDocument();
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
        $empty = $this->doc->createElement('div');
        // Empty Node tests
        // Setup Tests
        interfaceCheck('Document', $doc);
        interfaceCheck('Detached Element', $detached);
        interfaceCheck('Fragment', $fragment);
        interfaceCheck('In-document Element', $element);
        runSpecialSelectorTests('Document', $doc);
        runSpecialSelectorTests('Detached Element', $detached);
        runSpecialSelectorTests('Fragment', $fragment);
        runSpecialSelectorTests('In-document Element', $element);
        verifyStaticList('Document', $doc, $doc);
        verifyStaticList('Detached Element', $doc, $detached);
        verifyStaticList('Fragment', $doc, $fragment);
        verifyStaticList('In-document Element', $doc, $element);
        runInvalidSelectorTest('Document', $doc, $this->invalidSelectors);
        runInvalidSelectorTest('Detached Element', $detached, $this->invalidSelectors);
        runInvalidSelectorTest('Fragment', $fragment, $this->invalidSelectors);
        runInvalidSelectorTest('In-document Element', $element, $this->invalidSelectors);
        runInvalidSelectorTest('Empty Element', $empty, $this->invalidSelectors);
        runValidSelectorTest('Document', $doc, $this->validSelectors, $testType, $docType);
        runValidSelectorTest('Detached Element', $detached, $this->validSelectors, $testType, $docType);
        runValidSelectorTest('Fragment', $fragment, $this->validSelectors, $testType, $docType);
        $doc->body->appendChild($outOfScope);
        // Append before in-document Element tests.
        // None of these elements should match
        runValidSelectorTest('In-document Element', $element, $this->validSelectors, $testType, $docType);
    }
    public function setupSpecialElements($doc, $parent)
    {
        // Setup null and undefined tests
        $parent->appendChild($doc->createElement('null'));
        $parent->appendChild($doc->createElement(NULL));
        // Setup namespace tests
        $anyNS = $doc->createElement('div');
        $noNS = $doc->createElement('div');
        $anyNS->id = 'any-namespace';
        $noNS->id = 'no-namespace';
        $divs = null;
        $div = [$doc->createElement('div'), $doc->createElementNS('http://www.w3.org/1999/xhtml', 'div'), $doc->createElementNS('', 'div'), $doc->createElementNS('http://www.example.org/ns', 'div')];
        $div[0]->id = 'any-namespace-div1';
        $div[1]->id = 'any-namespace-div2';
        $div[2]->setAttribute('id', 'any-namespace-div3');
        // Non-HTML elements can't use .id property
        $div[3]->setAttribute('id', 'any-namespace-div4');
        for ($i = 0; $i < count($div); $i++) {
            $anyNS->appendChild($div[$i]);
        }
        $div = [$doc->createElement('div'), $doc->createElementNS('http://www.w3.org/1999/xhtml', 'div'), $doc->createElementNS('', 'div'), $doc->createElementNS('http://www.example.org/ns', 'div')];
        $div[0]->id = 'no-namespace-div1';
        $div[1]->id = 'no-namespace-div2';
        $div[2]->setAttribute('id', 'no-namespace-div3');
        // Non-HTML elements can't use .id property
        $div[3]->setAttribute('id', 'no-namespace-div4');
        for ($i = 0; $i < count($div); $i++) {
            $noNS->appendChild($div[$i]);
        }
        $parent->appendChild($anyNS);
        $parent->appendChild($noNS);
        $span = $doc->getElementById('attr-presence-i1');
        $span->setAttributeNS('http://www.example.org/ns', 'title', '');
    }
    public function interfaceCheck($type, $obj)
    {
        $this->assertTest(function () use(&$obj, &$type) {
            $q = gettype($obj->querySelector) === 'function';
            $this->assertTrueData($q, $type . ' supports querySelector.');
        }, $type . ' supports querySelector');
        $this->assertTest(function () use(&$obj, &$type) {
            $qa = gettype($obj->querySelectorAll) === 'function';
            $this->assertTrueData($qa, $type . ' supports querySelectorAll.');
        }, $type . ' supports querySelectorAll');
        $this->assertTest(function () use(&$obj) {
            $list = $obj->querySelectorAll('div');
            if ($obj->ownerDocument) {
                // The object is not a Document
                $this->assertTrueData($list instanceof $obj->ownerDocument->defaultView->NodeList, 'The result should be an instance of a NodeList');
            } else {
                // The object is a Document
                $this->assertTrueData($list instanceof $obj->defaultView->NodeList, 'The result should be an instance of a NodeList');
            }
        }, $type . '.querySelectorAll returns NodeList instance');
    }
    public function verifyStaticList($type, $doc, $root)
    {
        $pre = null;
        $post = null;
        $preLength = null;
        $this->assertTest(function () use(&$root, &$doc) {
            $pre = $root->querySelectorAll('div');
            $preLength = count($pre);
            $div = $doc->createElement('div');
            ($root->body || $root)->appendChild($div);
            $this->assertEqualsData(count($pre), $preLength, 'The length of the NodeList should not change.');
        }, $type . ': static NodeList');
        $this->assertTest(function () use(&$root, &$preLength) {
            (function () use(&$root, &$preLength) {
                $post = $root->querySelectorAll('div');
                return $this->assertEqualsData(count($post), $preLength + 1, 'The length of the new NodeList should be 1 more than the previous list.');
            })();
        }, $type . ': new NodeList');
    }
    public function runSpecialSelectorTests($type, $root)
    {
        $global = ($root->ownerDocument || $root)->defaultView;
        $this->assertTest(function () {
            // 1
            $this->assertEqualsData(count($root->querySelectorAll(null)), 1, "This should find one element with the tag name 'NULL'.");
        }, $type . '.querySelectorAll null');
        $this->assertTest(function () {
            // 2
            $this->assertEqualsData(count($root->querySelectorAll(null)), 1, "This should find one element with the tag name 'UNDEFINED'.");
        }, $type . '.querySelectorAll undefined');
        $this->assertTest(function () use(&$global, &$root) {
            // 3
            $this->assertThrowsJsData($global::TypeError, function () use(&$root) {
                $root->querySelectorAll();
            }, 'This should throw a TypeError.');
        }, $type . '.querySelectorAll no parameter');
        $this->assertTest(function () use(&$root) {
            // 4
            $elm = $root->querySelector(null);
            $this->assertNotEqualsData($elm, null, 'This should find an element.');
            $this->assertEqualsData(strtoupper($elm->tagName), 'NULL', "The tag name should be 'NULL'.");
        }, $type . '.querySelector null');
        $this->assertTest(function () use(&$root) {
            // 5
            $elm = $root->querySelector(null);
            $this->assertNotEqualsData($elm, null, 'This should find an element.');
            $this->assertEqualsData(strtoupper($elm->tagName), 'UNDEFINED', "The tag name should be 'UNDEFINED'.");
        }, $type . '.querySelector undefined');
        $this->assertTest(function () use(&$global, &$root) {
            // 6
            $this->assertThrowsJsData($global::TypeError, function () use(&$root) {
                $root->querySelector();
            }, 'This should throw a TypeError.');
        }, $type . '.querySelector no parameter');
        $this->assertTest(function () use(&$root) {
            // 7
            $result = $root->querySelectorAll('*');
            $i = 0;
            traverse($root, function ($elem) use(&$root, &$result, &$i) {
                if ($elem !== $root) {
                    $this->assertEqualsData($elem, $result[$i], 'The result in index ' . $i . ' should be in tree order.');
                    $i++;
                }
            });
        }, $type . '.querySelectorAll tree order');
    }
    public function runValidSelectorTest($type, $root, $selectors, $testType, $docType)
    {
        $nodeType = '';
        switch ($root->nodeType) {
            case Node::DOCUMENT_NODE:
                $nodeType = 'document';
                break;
            case Node::ELEMENT_NODE:
                $nodeType = $root->parentNode ? 'element' : 'detached';
                break;
            case Node::DOCUMENT_FRAGMENT_NODE:
                $nodeType = 'fragment';
                break;
            default:
                $this->assertUnreachedData();
                $nodeType = 'unknown';
        }
        for ($i = 0; $i < count($selectors); $i++) {
            $s = $selectors[$i];
            $n = $s['name'];
            $q = $s['selector'];
            $e = $s['expect'];
            if ((!$s['exclude'] || array_search($nodeType, $s['exclude']) === -1 && array_search($docType, $s['exclude']) === -1) && $s['testType'] & $testType) {
                $foundall = null;
                $found = null;
                $this->assertTest(function () use(&$root, &$q, &$e) {
                    $foundall = $root->querySelectorAll($q);
                    $this->assertNotEqualsData($foundall, null, 'The method should not return null.');
                    $this->assertEqualsData(count($foundall), count($e), 'The method should return the expected number of matches.');
                    for ($i = 0; $i < count($e); $i++) {
                        $this->assertNotEqualsData($foundall[$i], null, 'The item in index ' . $i . ' should not be null.');
                        $this->assertEqualsData($foundall[$i]->getAttribute('id'), $e[$i], 'The item in index ' . $i . ' should have the expected ID.');
                        $this->assertFalseData($foundall[$i]->hasAttribute('data-clone'), 'This should not be a cloned element.');
                    }
                }, $type . '.querySelectorAll: ' . $n . ': ' . $q);
                $this->assertTest(function () use(&$root, &$q, &$e, &$foundall) {
                    $found = $root->querySelector($q);
                    if (count($e) > 0) {
                        $this->assertNotEqualsData($found, null, 'The method should return a match.');
                        $this->assertEqualsData($found->getAttribute('id'), $e[0], 'The method should return the first match.');
                        $this->assertEqualsData($found, $foundall[0], 'The result should match the first item from querySelectorAll.');
                        $this->assertFalseData($found->hasAttribute('data-clone'), 'This should not be annotated as a cloned element.');
                    } else {
                        $this->assertEqualsData($found, null, 'The method should not match anything.');
                    }
                }, $type . '.querySelector: ' . $n . ': ' . $q);
            }
        }
    }
    public function windowFor($root)
    {
        return $root->defaultView || $root->ownerDocument->defaultView;
    }
    public function runInvalidSelectorTest($type, $root, $selectors)
    {
        for ($i = 0; $i < count($selectors); $i++) {
            $s = $selectors[$i];
            $n = $s['name'];
            $q = $s['selector'];
            $this->assertTest(function () use(&$root, &$q) {
                $this->assertThrowsDomData('SyntaxError', windowFor($root)::DOMException, function () use(&$root, &$q) {
                    $root->querySelector($q);
                });
            }, $type . '.querySelector: ' . $n . ': ' . $q);
            $this->assertTest(function () use(&$root, &$q) {
                $this->assertThrowsDomData('SyntaxError', windowFor($root)::DOMException, function () use(&$root, &$q) {
                    $root->querySelectorAll($q);
                });
            }, $type . '.querySelectorAll: ' . $n . ': ' . $q);
        }
    }
    public function traverse($elem, $fn)
    {
        if ($elem->nodeType === $elem::ELEMENT_NODE) {
            $fn($elem);
        }
        $elem = $elem->firstChild;
        while ($elem) {
            traverse($elem, $fn);
            $elem = $elem->nextSibling;
        }
    }
    public function getNodeType($node)
    {
        switch ($node->nodeType) {
            case Node::DOCUMENT_NODE:
                return 'document';
            case Node::ELEMENT_NODE:
                return $node->parentNode ? 'element' : 'detached';
            case Node::DOCUMENT_FRAGMENT_NODE:
                return 'fragment';
            default:
                $this->assertUnreachedData();
                return 'unknown';
        }
    }
    public function testParentNodeQuerySelectorAll()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-All.html';
        $this->asyncTest(function () {
            $frame = $this->doc->createElement('iframe');
            $self = $this;
            $frame->onload = function () use(&$self, &$frame) {
                // :target doesn't work before a page rendering on some browsers.  We run
                // tests after an animation frame because it may be later than the first
                // page rendering.
                requestAnimationFrame($self->step_func_done($init->bind($self, $frame)));
            };
            $frame->src = 'ParentNode-querySelector-All-content.html#target';
            $this->doc->body->appendChild($frame);
        });
        // Bit-mapped flags to indicate which tests the selector is suitable for
        $TEST_QSA = 0x1;
        // querySelector() and querySelectorAll() tests
        $TEST_FIND = 0x4;
        // find() and findAll() tests, may be unsuitable for querySelector[All]
        $TEST_MATCH = 0x10;
        // matches() tests
        /*
         * All of these invalid selectors should result in a SyntaxError being thrown by the APIs.
         *
         *   name:     A descriptive name of the selector being tested
         *   selector: The selector to test
         */
        $this->invalidSelectors = [['name' => 'Empty String', 'selector' => ''], ['name' => 'Invalid character', 'selector' => '['], ['name' => 'Invalid character', 'selector' => ']'], ['name' => 'Invalid character', 'selector' => '('], ['name' => 'Invalid character', 'selector' => ')'], ['name' => 'Invalid character', 'selector' => '{'], ['name' => 'Invalid character', 'selector' => '}'], ['name' => 'Invalid character', 'selector' => '<'], ['name' => 'Invalid character', 'selector' => '>'], ['name' => 'Invalid ID', 'selector' => '#'], ['name' => 'Invalid group of selectors', 'selector' => 'div,'], ['name' => 'Invalid class', 'selector' => '.'], ['name' => 'Invalid class', 'selector' => '.5cm'], ['name' => 'Invalid class', 'selector' => '..test'], ['name' => 'Invalid class', 'selector' => '.foo..quux'], ['name' => 'Invalid class', 'selector' => '.bar.'], ['name' => 'Invalid combinator', 'selector' => 'div & address, p'], ['name' => 'Invalid combinator', 'selector' => 'div ++ address, p'], ['name' => 'Invalid combinator', 'selector' => 'div ~~ address, p'], ['name' => 'Invalid [att=value] selector', 'selector' => '[*=test]'], ['name' => 'Invalid [att=value] selector', 'selector' => '[*|*=test]'], ['name' => 'Invalid [att=value] selector', 'selector' => '[class= space unquoted ]'], ['name' => 'Unknown pseudo-class', 'selector' => 'div:example'], ['name' => 'Unknown pseudo-class', 'selector' => ':example'], ['name' => 'Unknown pseudo-class', 'selector' => 'div:linkexample'], ['name' => 'Unknown pseudo-element', 'selector' => 'div::example'], ['name' => 'Unknown pseudo-element', 'selector' => '::example'], ['name' => 'Invalid pseudo-element', 'selector' => ':::before'], ['name' => 'Invalid pseudo-element', 'selector' => ':: before'], ['name' => 'Undeclared namespace', 'selector' => 'ns|div'], ['name' => 'Undeclared namespace', 'selector' => ':not(ns|div)'], ['name' => 'Invalid namespace', 'selector' => '^|div'], ['name' => 'Invalid namespace', 'selector' => '$|div'], ['name' => 'Relative selector', 'selector' => '>*']];
        /*
         * All of these should be valid selectors, expected to match zero or more elements in the document.
         * None should throw any errors.
         *
         *   name:     A descriptive name of the selector being tested
         *   selector: The selector to test
         *   expect:   A list of IDs of the elements expected to be matched. List must be given in tree order.
         *   exclude:  An array of contexts to exclude from testing. The valid values are:
         *             ["document", "element", "fragment", "detached", "html", "xhtml"]
         *             The "html" and "xhtml" values represent the type of document being queried. These are useful
         *             for tests that are affected by differences between HTML and XML, such as case sensitivity.
         *   level:    An integer indicating the CSS or Selectors level in which the selector being tested was introduced.
         *   testType: A bit-mapped flag indicating the type of test.
         *
         * Note: Interactive pseudo-classes (:active :hover and :focus) have not been tested in this test suite.
         */
        $this->validSelectors = [
            // Type Selector
            ['name' => 'Type selector, matching html element', 'selector' => 'html', 'expect' => ['html'], 'exclude' => ['element', 'fragment', 'detached'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Type selector, matching html element', 'selector' => 'html', 'expect' => [], 'exclude' => ['document'], 'level' => 1, 'testType' => $TEST_QSA],
            ['name' => 'Type selector, matching body element', 'selector' => 'body', 'expect' => ['body'], 'exclude' => ['element', 'fragment', 'detached'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Type selector, matching body element', 'selector' => 'body', 'expect' => [], 'exclude' => ['document'], 'level' => 1, 'testType' => $TEST_QSA],
            // Universal Selector
            ['name' => 'Universal selector, matching all elements', 'selector' => '*', 'expect' => ['universal', 'universal-p1', 'universal-code1', 'universal-hr1', 'universal-pre1', 'universal-span1', 'universal-p2', 'universal-a1', 'universal-address1', 'universal-code2', 'universal-a2'], 'level' => 2, 'testType' => $TEST_MATCH],
            ['name' => 'Universal selector, matching all children of element with specified ID', 'selector' => '#universal>*', 'expect' => ['universal-p1', 'universal-hr1', 'universal-pre1', 'universal-p2', 'universal-address1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Universal selector, matching all grandchildren of element with specified ID', 'selector' => '#universal>*>*', 'expect' => ['universal-code1', 'universal-span1', 'universal-a1', 'universal-code2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Universal selector, matching all children of empty element with specified ID', 'selector' => '#empty>*', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Universal selector, matching all descendants of element with specified ID', 'selector' => '#universal *', 'expect' => ['universal-p1', 'universal-code1', 'universal-hr1', 'universal-pre1', 'universal-span1', 'universal-p2', 'universal-a1', 'universal-address1', 'universal-code2', 'universal-a2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            // Attribute Selectors
            // - presence                  [att]
            ['name' => 'Attribute presence selector, matching align attribute with value', 'selector' => '.attr-presence-div1[align]', 'expect' => ['attr-presence-div1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute presence selector, matching align attribute with empty value', 'selector' => '.attr-presence-div2[align]', 'expect' => ['attr-presence-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute presence selector, matching title attribute, case insensitivity', 'selector' => '#attr-presence [*|TiTlE]', 'expect' => ['attr-presence-a1', 'attr-presence-span1', 'attr-presence-i1'], 'exclude' => ['xhtml'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute presence selector, not matching title attribute, case sensitivity', 'selector' => '#attr-presence [*|TiTlE]', 'expect' => [], 'exclude' => ['html'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute presence selector, matching custom data-* attribute', 'selector' => '[data-attr-presence]', 'expect' => ['attr-presence-pre1', 'attr-presence-blockquote1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute presence selector, not matching attribute with similar name', 'selector' => '.attr-presence-div3[align], .attr-presence-div4[align]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Attribute presence selector, matching attribute with non-ASCII characters', 'selector' => "ul[data-中文]", 'expect' => ['attr-presence-ul1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute presence selector, not matching default option without selected attribute', 'selector' => '#attr-presence-select1 option[selected]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Attribute presence selector, matching option with selected attribute', 'selector' => '#attr-presence-select2 option[selected]', 'expect' => ['attr-presence-select2-option4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute presence selector, matching multiple options with selected attributes', 'selector' => '#attr-presence-select3 option[selected]', 'expect' => ['attr-presence-select3-option2', 'attr-presence-select3-option3'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - value                     [att=val]
            ['name' => 'Attribute value selector, matching align attribute with value', 'selector' => '#attr-value [align="center"]', 'expect' => ['attr-value-div1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute value selector, matching align attribute with value, unclosed bracket', 'selector' => '#attr-value [align="center"', 'expect' => ['attr-value-div1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute value selector, matching align attribute with empty value', 'selector' => '#attr-value [align=""]', 'expect' => ['attr-value-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute value selector, not matching align attribute with partial value', 'selector' => '#attr-value [align="c"]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Attribute value selector, not matching align attribute with incorrect value', 'selector' => '#attr-value [align="centera"]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Attribute value selector, matching custom data-* attribute with unicode escaped value', 'selector' => '[data-attr-value="\\e9"]', 'expect' => ['attr-value-div3'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute value selector, matching custom data-* attribute with escaped character', 'selector' => '[data-attr-value_foo="\\e9"]', 'expect' => ['attr-value-div4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute value selector with single-quoted value, matching multiple inputs with type attributes', 'selector' => "#attr-value input[type='hidden'],#attr-value input[type='radio']", 'expect' => ['attr-value-input3', 'attr-value-input4', 'attr-value-input6', 'attr-value-input8', 'attr-value-input9'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute value selector with double-quoted value, matching multiple inputs with type attributes', 'selector' => "#attr-value input[type=\"hidden\"],#attr-value input[type='radio']", 'expect' => ['attr-value-input3', 'attr-value-input4', 'attr-value-input6', 'attr-value-input8', 'attr-value-input9'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute value selector with unquoted value, matching multiple inputs with type attributes', 'selector' => '#attr-value input[type=hidden],#attr-value input[type=radio]', 'expect' => ['attr-value-input3', 'attr-value-input4', 'attr-value-input6', 'attr-value-input8', 'attr-value-input9'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute value selector, matching attribute with value using non-ASCII characters', 'selector' => "[data-attr-value=中文]", 'expect' => ['attr-value-div5'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - whitespace-separated list [att~=val]
            ['name' => 'Attribute whitespace-separated list selector, matching class attribute with value', 'selector' => '#attr-whitespace [class~="div1"]', 'expect' => ['attr-whitespace-div1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector, not matching class attribute with empty value', 'selector' => '#attr-whitespace [class~=""]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Attribute whitespace-separated list selector, not matching class attribute with partial value', 'selector' => '[data-attr-whitespace~="div"]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Attribute whitespace-separated list selector, matching custom data-* attribute with unicode escaped value', 'selector' => '[data-attr-whitespace~="\\0000e9"]', 'expect' => ['attr-whitespace-div4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector, matching custom data-* attribute with escaped character', 'selector' => '[data-attr-whitespace_foo~="\\e9"]', 'expect' => ['attr-whitespace-div5'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector with single-quoted value, matching multiple links with rel attributes', 'selector' => "#attr-whitespace a[rel~='bookmark'],  #attr-whitespace a[rel~='nofollow']", 'expect' => ['attr-whitespace-a1', 'attr-whitespace-a2', 'attr-whitespace-a3', 'attr-whitespace-a5', 'attr-whitespace-a7'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector with double-quoted value, matching multiple links with rel attributes', 'selector' => "#attr-whitespace a[rel~=\"bookmark\"],#attr-whitespace a[rel~='nofollow']", 'expect' => ['attr-whitespace-a1', 'attr-whitespace-a2', 'attr-whitespace-a3', 'attr-whitespace-a5', 'attr-whitespace-a7'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector with unquoted value, matching multiple links with rel attributes', 'selector' => '#attr-whitespace a[rel~=bookmark],    #attr-whitespace a[rel~=nofollow]', 'expect' => ['attr-whitespace-a1', 'attr-whitespace-a2', 'attr-whitespace-a3', 'attr-whitespace-a5', 'attr-whitespace-a7'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector with double-quoted value, not matching value with space', 'selector' => '#attr-whitespace a[rel~="book mark"]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Attribute whitespace-separated list selector, matching title attribute with value using non-ASCII characters', 'selector' => "#attr-whitespace [title~=中文]", 'expect' => ['attr-whitespace-p1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - hyphen-separated list     [att|=val]
            ['name' => 'Attribute hyphen-separated list selector, not matching unspecified lang attribute', 'selector' => '#attr-hyphen-div1[lang|="en"]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Attribute hyphen-separated list selector, matching lang attribute with exact value', 'selector' => '#attr-hyphen-div2[lang|="fr"]', 'expect' => ['attr-hyphen-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute hyphen-separated list selector, matching lang attribute with partial value', 'selector' => '#attr-hyphen-div3[lang|="en"]', 'expect' => ['attr-hyphen-div3'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute hyphen-separated list selector, not matching incorrect value', 'selector' => '#attr-hyphen-div4[lang|="es-AR"]', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            // - substring begins-with     [att^=val] (Level 3)
            ['name' => 'Attribute begins with selector, matching href attributes beginning with specified substring', 'selector' => '#attr-begins a[href^="http://www"]', 'expect' => ['attr-begins-a1', 'attr-begins-a3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute begins with selector, matching lang attributes beginning with specified substring, ', 'selector' => '#attr-begins [lang^="en-"]', 'expect' => ['attr-begins-div2', 'attr-begins-div4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute begins with selector, not matching class attribute with empty value', 'selector' => '#attr-begins [class^=""]', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'Attribute begins with selector, not matching class attribute not beginning with specified substring', 'selector' => '#attr-begins [class^=apple]', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'Attribute begins with selector with single-quoted value, matching class attribute beginning with specified substring', 'selector' => "#attr-begins [class^=' apple']", 'expect' => ['attr-begins-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute begins with selector with double-quoted value, matching class attribute beginning with specified substring', 'selector' => '#attr-begins [class^=" apple"]', 'expect' => ['attr-begins-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute begins with selector with unquoted value, not matching class attribute not beginning with specified substring', 'selector' => '#attr-begins [class^= apple]', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            // - substring ends-with       [att$=val] (Level 3)
            ['name' => 'Attribute ends with selector, matching href attributes ending with specified substring', 'selector' => '#attr-ends a[href$=".org"]', 'expect' => ['attr-ends-a1', 'attr-ends-a3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute ends with selector, matching lang attributes ending with specified substring, ', 'selector' => '#attr-ends [lang$="-CH"]', 'expect' => ['attr-ends-div2', 'attr-ends-div4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute ends with selector, not matching class attribute with empty value', 'selector' => '#attr-ends [class$=""]', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'Attribute ends with selector, not matching class attribute not ending with specified substring', 'selector' => '#attr-ends [class$=apple]', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'Attribute ends with selector with single-quoted value, matching class attribute ending with specified substring', 'selector' => "#attr-ends [class\$='apple ']", 'expect' => ['attr-ends-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute ends with selector with double-quoted value, matching class attribute ending with specified substring', 'selector' => '#attr-ends [class$="apple "]', 'expect' => ['attr-ends-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute ends with selector with unquoted value, not matching class attribute not ending with specified substring', 'selector' => '#attr-ends [class$=apple ]', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            // - substring contains        [att*=val] (Level 3)
            ['name' => 'Attribute contains selector, matching href attributes beginning with specified substring', 'selector' => '#attr-contains a[href*="http://www"]', 'expect' => ['attr-contains-a1', 'attr-contains-a3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector, matching href attributes ending with specified substring', 'selector' => '#attr-contains a[href*=".org"]', 'expect' => ['attr-contains-a1', 'attr-contains-a2'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector, matching href attributes containing specified substring', 'selector' => '#attr-contains a[href*=".example."]', 'expect' => ['attr-contains-a1', 'attr-contains-a3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector, matching lang attributes beginning with specified substring, ', 'selector' => '#attr-contains [lang*="en-"]', 'expect' => ['attr-contains-div2', 'attr-contains-div6'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector, matching lang attributes ending with specified substring, ', 'selector' => '#attr-contains [lang*="-CH"]', 'expect' => ['attr-contains-div3', 'attr-contains-div5'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector, not matching class attribute with empty value', 'selector' => '#attr-contains [class*=""]', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'Attribute contains selector with single-quoted value, matching class attribute beginning with specified substring', 'selector' => "#attr-contains [class*=' apple']", 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector with single-quoted value, matching class attribute ending with specified substring', 'selector' => "#attr-contains [class*='orange ']", 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector with single-quoted value, matching class attribute containing specified substring', 'selector' => "#attr-contains [class*='ple banana ora']", 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector with double-quoted value, matching class attribute beginning with specified substring', 'selector' => '#attr-contains [class*=" apple"]', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector with double-quoted value, matching class attribute ending with specified substring', 'selector' => '#attr-contains [class*="orange "]', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector with double-quoted value, matching class attribute containing specified substring', 'selector' => '#attr-contains [class*="ple banana ora"]', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector with unquoted value, matching class attribute beginning with specified substring', 'selector' => '#attr-contains [class*= apple]', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector with unquoted value, matching class attribute ending with specified substring', 'selector' => '#attr-contains [class*=orange ]', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Attribute contains selector with unquoted value, matching class attribute containing specified substring', 'selector' => '#attr-contains [class*= banana ]', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // Pseudo-classes
            // - :root                 (Level 3)
            ['name' => ':root pseudo-class selector, matching document root element', 'selector' => ':root', 'expect' => ['html'], 'exclude' => ['element', 'fragment', 'detached'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':root pseudo-class selector, not matching document root element', 'selector' => ':root', 'expect' => [], 'exclude' => ['document'], 'level' => 3, 'testType' => $TEST_QSA],
            // - :nth-child(n)         (Level 3)
            // XXX write descriptions
            ['name' => ':nth-child selector, matching the third child element', 'selector' => '#pseudo-nth-table1 :nth-child(3)', 'expect' => ['pseudo-nth-td3', 'pseudo-nth-td9', 'pseudo-nth-tr3', 'pseudo-nth-td15'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-child selector, matching every third child element', 'selector' => '#pseudo-nth li:nth-child(3n)', 'expect' => ['pseudo-nth-li3', 'pseudo-nth-li6', 'pseudo-nth-li9', 'pseudo-nth-li12'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-child selector, matching every second child element, starting from the fourth', 'selector' => '#pseudo-nth li:nth-child(2n+4)', 'expect' => ['pseudo-nth-li4', 'pseudo-nth-li6', 'pseudo-nth-li8', 'pseudo-nth-li10', 'pseudo-nth-li12'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-child selector, matching every fourth child element, starting from the third', 'selector' => '#pseudo-nth-p1 :nth-child(4n-1)', 'expect' => ['pseudo-nth-em2', 'pseudo-nth-span3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :nth-last-child       (Level 3)
            ['name' => ':nth-last-child selector, matching the third last child element', 'selector' => '#pseudo-nth-table1 :nth-last-child(3)', 'expect' => ['pseudo-nth-tr1', 'pseudo-nth-td4', 'pseudo-nth-td10', 'pseudo-nth-td16'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-last-child selector, matching every third child element from the end', 'selector' => '#pseudo-nth li:nth-last-child(3n)', 'expect' => ['pseudo-nth-li1', 'pseudo-nth-li4', 'pseudo-nth-li7', 'pseudo-nth-li10'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-last-child selector, matching every second child element from the end, starting from the fourth last', 'selector' => '#pseudo-nth li:nth-last-child(2n+4)', 'expect' => ['pseudo-nth-li1', 'pseudo-nth-li3', 'pseudo-nth-li5', 'pseudo-nth-li7', 'pseudo-nth-li9'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-last-child selector, matching every fourth element from the end, starting from the third last', 'selector' => '#pseudo-nth-p1 :nth-last-child(4n-1)', 'expect' => ['pseudo-nth-span2', 'pseudo-nth-span4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :nth-of-type(n)       (Level 3)
            ['name' => ':nth-of-type selector, matching the third em element', 'selector' => '#pseudo-nth-p1 em:nth-of-type(3)', 'expect' => ['pseudo-nth-em3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-of-type selector, matching every second element of their type', 'selector' => '#pseudo-nth-p1 :nth-of-type(2n)', 'expect' => ['pseudo-nth-em2', 'pseudo-nth-span2', 'pseudo-nth-span4', 'pseudo-nth-strong2', 'pseudo-nth-em4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-of-type selector, matching every second elemetn of their type, starting from the first', 'selector' => '#pseudo-nth-p1 span:nth-of-type(2n-1)', 'expect' => ['pseudo-nth-span1', 'pseudo-nth-span3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :nth-last-of-type(n)  (Level 3)
            ['name' => ':nth-last-of-type selector, matching the third last em element', 'selector' => '#pseudo-nth-p1 em:nth-last-of-type(3)', 'expect' => ['pseudo-nth-em2'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-last-of-type selector, matching every second last element of their type', 'selector' => '#pseudo-nth-p1 :nth-last-of-type(2n)', 'expect' => ['pseudo-nth-span1', 'pseudo-nth-em1', 'pseudo-nth-strong1', 'pseudo-nth-em3', 'pseudo-nth-span3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':nth-last-of-type selector, matching every second last element of their type, starting from the last', 'selector' => '#pseudo-nth-p1 span:nth-last-of-type(2n-1)', 'expect' => ['pseudo-nth-span2', 'pseudo-nth-span4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :first-of-type        (Level 3)
            ['name' => ':first-of-type selector, matching the first em element', 'selector' => '#pseudo-nth-p1 em:first-of-type', 'expect' => ['pseudo-nth-em1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':first-of-type selector, matching the first of every type of element', 'selector' => '#pseudo-nth-p1 :first-of-type', 'expect' => ['pseudo-nth-span1', 'pseudo-nth-em1', 'pseudo-nth-strong1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':first-of-type selector, matching the first td element in each table row', 'selector' => '#pseudo-nth-table1 tr :first-of-type', 'expect' => ['pseudo-nth-td1', 'pseudo-nth-td7', 'pseudo-nth-td13'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :last-of-type         (Level 3)
            ['name' => ':last-of-type selector, matching the last em elemnet', 'selector' => '#pseudo-nth-p1 em:last-of-type', 'expect' => ['pseudo-nth-em4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':last-of-type selector, matching the last of every type of element', 'selector' => '#pseudo-nth-p1 :last-of-type', 'expect' => ['pseudo-nth-span4', 'pseudo-nth-strong2', 'pseudo-nth-em4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':last-of-type selector, matching the last td element in each table row', 'selector' => '#pseudo-nth-table1 tr :last-of-type', 'expect' => ['pseudo-nth-td6', 'pseudo-nth-td12', 'pseudo-nth-td18'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :first-child
            ['name' => ':first-child pseudo-class selector, matching first child div element', 'selector' => '#pseudo-first-child div:first-child', 'expect' => ['pseudo-first-child-div1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ":first-child pseudo-class selector, doesn't match non-first-child elements", 'selector' => '.pseudo-first-child-div2:first-child, .pseudo-first-child-div3:first-child', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => ':first-child pseudo-class selector, matching first-child of multiple elements', 'selector' => '#pseudo-first-child span:first-child', 'expect' => ['pseudo-first-child-span1', 'pseudo-first-child-span3', 'pseudo-first-child-span5'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :last-child           (Level 3)
            ['name' => ':last-child pseudo-class selector, matching last child div element', 'selector' => '#pseudo-last-child div:last-child', 'expect' => ['pseudo-last-child-div3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ":last-child pseudo-class selector, doesn't match non-last-child elements", 'selector' => '.pseudo-last-child-div1:last-child, .pseudo-last-child-div2:first-child', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => ':last-child pseudo-class selector, matching first-child of multiple elements', 'selector' => '#pseudo-last-child span:last-child', 'expect' => ['pseudo-last-child-span2', 'pseudo-last-child-span4', 'pseudo-last-child-span6'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :only-child           (Level 3)
            ['name' => ':pseudo-only-child pseudo-class selector, matching all only-child elements', 'selector' => '#pseudo-only :only-child', 'expect' => ['pseudo-only-span1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':pseudo-only-child pseudo-class selector, matching only-child em elements', 'selector' => '#pseudo-only em:only-child', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            // - :only-of-type         (Level 3)
            ['name' => ':pseudo-only-of-type pseudo-class selector, matching all elements with no siblings of the same type', 'selector' => '#pseudo-only :only-of-type', 'expect' => ['pseudo-only-span1', 'pseudo-only-em1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':pseudo-only-of-type pseudo-class selector, matching em elements with no siblings of the same type', 'selector' => '#pseudo-only em:only-of-type', 'expect' => ['pseudo-only-em1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :empty                (Level 3)
            ['name' => ':empty pseudo-class selector, matching empty p elements', 'selector' => '#pseudo-empty p:empty', 'expect' => ['pseudo-empty-p1', 'pseudo-empty-p2'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':empty pseudo-class selector, matching all empty elements', 'selector' => '#pseudo-empty :empty', 'expect' => ['pseudo-empty-p1', 'pseudo-empty-p2', 'pseudo-empty-span1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :link and :visited
            // Implementations may treat all visited links as unvisited, so these cannot be tested separately.
            // The only guarantee is that ":link,:visited" matches the set of all visited and unvisited links and that they are individually mutually exclusive sets.
            ['name' => ':link and :visited pseudo-class selectors, matching a and area elements with href attributes', 'selector' => '#pseudo-link :link, #pseudo-link :visited', 'expect' => ['pseudo-link-a1', 'pseudo-link-a2', 'pseudo-link-area1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':link and :visited pseudo-class selectors, matching no elements', 'selector' => '#head :link, #head :visited', 'expect' => [], 'exclude' => ['element', 'fragment', 'detached'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':link and :visited pseudo-class selectors, not matching link elements with href attributes', 'selector' => '#head :link, #head :visited', 'expect' => [], 'exclude' => ['document'], 'level' => 1, 'testType' => $TEST_QSA],
            ['name' => ':link and :visited pseudo-class selectors, chained, mutually exclusive pseudo-classes match nothing', 'selector' => ':link:visited', 'expect' => [], 'exclude' => ['document'], 'level' => 1, 'testType' => $TEST_QSA],
            // - :target               (Level 3)
            ['name' => ':target pseudo-class selector, matching the element referenced by the URL fragment identifier', 'selector' => ':target', 'expect' => [], 'exclude' => ['document', 'element'], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => ':target pseudo-class selector, matching the element referenced by the URL fragment identifier', 'selector' => ':target', 'expect' => ['target'], 'exclude' => ['fragment', 'detached'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :lang()
            ['name' => ':lang pseudo-class selector, matching inherited language', 'selector' => '#pseudo-lang-div1:lang(en)', 'expect' => ['pseudo-lang-div1'], 'exclude' => ['detached', 'fragment'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':lang pseudo-class selector, not matching element with no inherited language', 'selector' => '#pseudo-lang-div1:lang(en)', 'expect' => [], 'exclude' => ['document', 'element'], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => ':lang pseudo-class selector, matching specified language with exact value', 'selector' => '#pseudo-lang-div2:lang(fr)', 'expect' => ['pseudo-lang-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':lang pseudo-class selector, matching specified language with partial value', 'selector' => '#pseudo-lang-div3:lang(en)', 'expect' => ['pseudo-lang-div3'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':lang pseudo-class selector, not matching incorrect language', 'selector' => '#pseudo-lang-div4:lang(es-AR)', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            // - :enabled              (Level 3)
            ['name' => ':enabled pseudo-class selector, matching all enabled form controls', 'selector' => '#pseudo-ui :enabled', 'expect' => ['pseudo-ui-input1', 'pseudo-ui-input2', 'pseudo-ui-input3', 'pseudo-ui-input4', 'pseudo-ui-input5', 'pseudo-ui-input6', 'pseudo-ui-input7', 'pseudo-ui-input8', 'pseudo-ui-input9', 'pseudo-ui-textarea1', 'pseudo-ui-button1'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':enabled pseudo-class selector, not matching link elements', 'selector' => '#pseudo-link :enabled', 'expect' => [], 'unexpected' => ['pseudo-link-a1', 'pseudo-link-a2', 'pseudo-link-a3', 'pseudo-link-map1', 'pseudo-link-area1', 'pseudo-link-area2'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :disabled             (Level 3)
            ['name' => ':disabled pseudo-class selector, matching all disabled form controls', 'selector' => '#pseudo-ui :disabled', 'expect' => ['pseudo-ui-input10', 'pseudo-ui-input11', 'pseudo-ui-input12', 'pseudo-ui-input13', 'pseudo-ui-input14', 'pseudo-ui-input15', 'pseudo-ui-input16', 'pseudo-ui-input17', 'pseudo-ui-input18', 'pseudo-ui-textarea2', 'pseudo-ui-button2'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':disabled pseudo-class selector, not matching link elements', 'selector' => '#pseudo-link :disabled', 'expect' => [], 'unexpected' => ['pseudo-link-a1', 'pseudo-link-a2', 'pseudo-link-a3', 'pseudo-link-map1', 'pseudo-link-area1', 'pseudo-link-area2'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :checked              (Level 3)
            ['name' => ':checked pseudo-class selector, matching checked radio buttons and checkboxes', 'selector' => '#pseudo-ui :checked', 'expect' => ['pseudo-ui-input4', 'pseudo-ui-input6', 'pseudo-ui-input13', 'pseudo-ui-input15'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :not(s)               (Level 3)
            ['name' => ':not pseudo-class selector, matching ', 'selector' => '#not>:not(div)', 'expect' => ['not-p1', 'not-p2', 'not-p3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':not pseudo-class selector, matching ', 'selector' => '#not * :not(:first-child)', 'expect' => ['not-em1', 'not-em2', 'not-em3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => ':not pseudo-class selector, matching nothing', 'selector' => ':not(*)', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => ':not pseudo-class selector, matching nothing', 'selector' => ':not(*|*)', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => ':not pseudo-class selector argument surrounded by spaces, matching ', 'selector' => '#not>:not( div )', 'expect' => ['not-p1', 'not-p2', 'not-p3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // Pseudo-elements
            // - ::first-line
            ['name' => ':first-line pseudo-element (one-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element:first-line', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => '::first-line pseudo-element (two-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element::first-line', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            // - ::first-letter
            ['name' => ':first-letter pseudo-element (one-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element:first-letter', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => '::first-letter pseudo-element (two-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element::first-letter', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            // - ::before
            ['name' => ':before pseudo-element (one-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element:before', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => '::before pseudo-element (two-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element::before', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            // - ::after
            ['name' => ':after pseudo-element (one-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element:after', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => '::after pseudo-element (two-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element::after', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            // Class Selectors
            ['name' => 'Class selector, matching element with specified class', 'selector' => '.class-p', 'expect' => ['class-p1', 'class-p2', 'class-p3'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Class selector, chained, matching only elements with all specified classes', 'selector' => '#class .apple.orange.banana', 'expect' => ['class-div1', 'class-div2', 'class-p4', 'class-div3', 'class-p6', 'class-div4'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Class Selector, chained, with type selector', 'selector' => 'div.apple.banana.orange', 'expect' => ['class-div1', 'class-div2', 'class-div3', 'class-div4'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Class selector, matching element with class value using non-ASCII characters (1)', 'selector' => ".台北Táiběi", 'expect' => ['class-span1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Class selector, matching multiple elements with class value using non-ASCII characters', 'selector' => ".台北", 'expect' => ['class-span1', 'class-span2'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Class selector, chained, matching element with multiple class values using non-ASCII characters (1)', 'selector' => ".台北Táiběi.台北", 'expect' => ['class-span1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Class selector, matching element with class with escaped character', 'selector' => '.foo\\:bar', 'expect' => ['class-span3'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Class selector, matching element with class with escaped character', 'selector' => '.test\\.foo\\[5\\]bar', 'expect' => ['class-span4'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            // ID Selectors
            ['name' => 'ID selector, matching element with specified id', 'selector' => '#id #id-div1', 'expect' => ['id-div1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'ID selector, chained, matching element with specified id', 'selector' => '#id-div1, #id-div1', 'expect' => ['id-div1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'ID selector, chained, matching element with specified id', 'selector' => '#id-div1, #id-div2', 'expect' => ['id-div1', 'id-div2'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'ID Selector, chained, with type selector', 'selector' => 'div#id-div1, div#id-div2', 'expect' => ['id-div1', 'id-div2'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'ID selector, not matching non-existent descendant', 'selector' => '#id #none', 'expect' => [], 'level' => 1, 'testType' => $TEST_QSA],
            ['name' => 'ID selector, not matching non-existent ancestor', 'selector' => '#none #id-div1', 'expect' => [], 'level' => 1, 'testType' => $TEST_QSA],
            ['name' => 'ID selector, matching multiple elements with duplicate id', 'selector' => '#id-li-duplicate', 'expect' => ['id-li-duplicate', 'id-li-duplicate', 'id-li-duplicate', 'id-li-duplicate'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'ID selector, matching id value using non-ASCII characters (1)', 'selector' => "#台北Táiběi", 'expect' => ["台北Táiběi"], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'ID selector, matching id value using non-ASCII characters (2)', 'selector' => "#台北", 'expect' => ["台北"], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'ID selector, matching id values using non-ASCII characters (1)', 'selector' => "#台北Táiběi, #台北", 'expect' => ["台北Táiběi", "台北"], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            // XXX runMatchesTest() in level2-lib.js can't handle this because obtaining the expected nodes requires escaping characters when generating the selector from 'expect' values
            ['name' => 'ID selector, matching element with id with escaped character', 'selector' => '#\\#foo\\:bar', 'expect' => ['#foo:bar'], 'level' => 1, 'testType' => $TEST_QSA],
            ['name' => 'ID selector, matching element with id with escaped character', 'selector' => '#test\\.foo\\[5\\]bar', 'expect' => ['test.foo[5]bar'], 'level' => 1, 'testType' => $TEST_QSA],
            // Namespaces
            // XXX runMatchesTest() in level2-lib.js can't handle these because non-HTML elements don't have a recognised id
            ['name' => 'Namespace selector, matching element with any namespace', 'selector' => '#any-namespace *|div', 'expect' => ['any-namespace-div1', 'any-namespace-div2', 'any-namespace-div3', 'any-namespace-div4'], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'Namespace selector, matching div elements in no namespace only', 'selector' => '#no-namespace |div', 'expect' => ['no-namespace-div3'], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'Namespace selector, matching any elements in no namespace only', 'selector' => '#no-namespace |*', 'expect' => ['no-namespace-div3'], 'level' => 3, 'testType' => $TEST_QSA],
            // Combinators
            // - Descendant combinator ' '
            ['name' => 'Descendant combinator, matching element that is a descendant of an element with id', 'selector' => '#descendant div', 'expect' => ['descendant-div1', 'descendant-div2', 'descendant-div3', 'descendant-div4'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with id that is a descendant of an element', 'selector' => 'body #descendant-div1', 'expect' => ['descendant-div1'], 'exclude' => ['detached', 'fragment'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with id that is a descendant of an element', 'selector' => 'div #descendant-div1', 'expect' => ['descendant-div1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with id that is a descendant of an element with id', 'selector' => '#descendant #descendant-div2', 'expect' => ['descendant-div2'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with class that is a descendant of an element with id', 'selector' => '#descendant .descendant-div2', 'expect' => ['descendant-div2'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with class that is a descendant of an element with class', 'selector' => '.descendant-div1 .descendant-div3', 'expect' => ['descendant-div3'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Descendant combinator, not matching element with id that is not a descendant of an element with id', 'selector' => '#descendant-div1 #descendant-div4', 'expect' => [], 'level' => 1, 'testType' => $TEST_QSA],
            ['name' => 'Descendant combinator, whitespace characters', 'selector' => "#descendant\t\r\n#descendant-div2", 'expect' => ['descendant-div2'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            /* The future of this combinator is uncertain, see
             * https://github.com/w3c/csswg-drafts/issues/641
             * These tests are commented out until a final decision is made on whether to
             * keep the feature in the spec.
             */
            // // - Descendant combinator '>>'
            // {name: "Descendant combinator '>>', matching element that is a descendant of an element with id",                 selector: "#descendant>>div",                   expect: ["descendant-div1", "descendant-div2", "descendant-div3", "descendant-div4"], level: 1, testType: TEST_QSA | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with id that is a descendant of an element",                 selector: "body>>#descendant-div1",             expect: ["descendant-div1"], exclude: ["detached", "fragment"], level: 1, testType: TEST_QSA | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with id that is a descendant of an element",                 selector: "div>>#descendant-div1",              expect: ["descendant-div1"],                                    level: 1, testType: TEST_QSA | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with id that is a descendant of an element with id",         selector: "#descendant>>#descendant-div2",      expect: ["descendant-div2"],                                    level: 1, testType: TEST_QSA | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with class that is a descendant of an element with id",      selector: "#descendant>>.descendant-div2",      expect: ["descendant-div2"],                                    level: 1, testType: TEST_QSA | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with class that is a descendant of an element with class",   selector: ".descendant-div1>>.descendant-div3", expect: ["descendant-div3"],                                    level: 1, testType: TEST_QSA | TEST_MATCH},
            // {name: "Descendant combinator '>>', not matching element with id that is not a descendant of an element with id", selector: "#descendant-div1>>#descendant-div4", expect: [] /*no matches*/,                                      level: 1, testType: TEST_QSA},
            // - Child combinator '>'
            ['name' => 'Child combinator, matching element that is a child of an element with id', 'selector' => '#child>div', 'expect' => ['child-div1', 'child-div4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Child combinator, matching element with id that is a child of an element', 'selector' => 'div>#child-div1', 'expect' => ['child-div1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Child combinator, matching element with id that is a child of an element with id', 'selector' => '#child>#child-div1', 'expect' => ['child-div1'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Child combinator, matching element with id that is a child of an element with class', 'selector' => '#child-div1>.child-div2', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Child combinator, matching element with class that is a child of an element with class', 'selector' => '.child-div1>.child-div2', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Child combinator, not matching element with id that is not a child of an element with id', 'selector' => '#child>#child-div3', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Child combinator, not matching element with id that is not a child of an element with class', 'selector' => '#child-div1>.child-div3', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Child combinator, not matching element with class that is not a child of an element with class', 'selector' => '.child-div1>.child-div3', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Child combinator, surrounded by whitespace', 'selector' => "#child-div1\t\r\n>\t\r\n#child-div2", 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Child combinator, whitespace after', 'selector' => "#child-div1>\t\r\n#child-div2", 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Child combinator, whitespace before', 'selector' => "#child-div1\t\r\n>#child-div2", 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Child combinator, no whitespace', 'selector' => '#child-div1>#child-div2', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - Adjacent sibling combinator '+'
            ['name' => 'Adjacent sibling combinator, matching element that is an adjacent sibling of an element with id', 'selector' => '#adjacent-div2+div', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching element with id that is an adjacent sibling of an element', 'selector' => 'div+#adjacent-div4', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching element with id that is an adjacent sibling of an element with id', 'selector' => '#adjacent-div2+#adjacent-div4', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching element with class that is an adjacent sibling of an element with id', 'selector' => '#adjacent-div2+.adjacent-div4', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching element with class that is an adjacent sibling of an element with class', 'selector' => '.adjacent-div2+.adjacent-div4', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching p element that is an adjacent sibling of a div element', 'selector' => '#adjacent div+p', 'expect' => ['adjacent-p2'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, not matching element with id that is not an adjacent sibling of an element with id', 'selector' => '#adjacent-div2+#adjacent-p2, #adjacent-div2+#adjacent-div1', 'expect' => [], 'level' => 2, 'testType' => $TEST_QSA],
            ['name' => 'Adjacent sibling combinator, surrounded by whitespace', 'selector' => "#adjacent-p2\t\r\n+\t\r\n#adjacent-p3", 'expect' => ['adjacent-p3'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, whitespace after', 'selector' => "#adjacent-p2+\t\r\n#adjacent-p3", 'expect' => ['adjacent-p3'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, whitespace before', 'selector' => "#adjacent-p2\t\r\n+#adjacent-p3", 'expect' => ['adjacent-p3'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, no whitespace', 'selector' => '#adjacent-p2+#adjacent-p3', 'expect' => ['adjacent-p3'], 'level' => 2, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - General sibling combinator ~ (Level 3)
            ['name' => 'General sibling combinator, matching element that is a sibling of an element with id', 'selector' => '#sibling-div2~div', 'expect' => ['sibling-div4', 'sibling-div6'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'General sibling combinator, matching element with id that is a sibling of an element', 'selector' => 'div~#sibling-div4', 'expect' => ['sibling-div4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'General sibling combinator, matching element with id that is a sibling of an element with id', 'selector' => '#sibling-div2~#sibling-div4', 'expect' => ['sibling-div4'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'General sibling combinator, matching element with class that is a sibling of an element with id', 'selector' => '#sibling-div2~.sibling-div', 'expect' => ['sibling-div4', 'sibling-div6'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'General sibling combinator, matching p element that is a sibling of a div element', 'selector' => '#sibling div~p', 'expect' => ['sibling-p2', 'sibling-p3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'General sibling combinator, not matching element with id that is not a sibling after a p element', 'selector' => '#sibling>p~div', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'General sibling combinator, not matching element with id that is not a sibling after an element with id', 'selector' => '#sibling-div2~#sibling-div3, #sibling-div2~#sibling-div1', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'General sibling combinator, surrounded by whitespace', 'selector' => "#sibling-p2\t\r\n~\t\r\n#sibling-p3", 'expect' => ['sibling-p3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'General sibling combinator, whitespace after', 'selector' => "#sibling-p2~\t\r\n#sibling-p3", 'expect' => ['sibling-p3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'General sibling combinator, whitespace before', 'selector' => "#sibling-p2\t\r\n~#sibling-p3", 'expect' => ['sibling-p3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'General sibling combinator, no whitespace', 'selector' => '#sibling-p2~#sibling-p3', 'expect' => ['sibling-p3'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // Group of selectors (comma)
            ['name' => 'Syntax, group of selectors separator, surrounded by whitespace', 'selector' => "#group em\t\r \n,\t\r \n#group strong", 'expect' => ['group-em1', 'group-strong1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Syntax, group of selectors separator, whitespace after', 'selector' => "#group em,\t\r\n#group strong", 'expect' => ['group-em1', 'group-strong1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Syntax, group of selectors separator, whitespace before', 'selector' => "#group em\t\r\n,#group strong", 'expect' => ['group-em1', 'group-strong1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            ['name' => 'Syntax, group of selectors separator, no whitespace', 'selector' => '#group em,#group strong', 'expect' => ['group-em1', 'group-strong1'], 'level' => 1, 'testType' => $TEST_QSA | $TEST_MATCH],
            // ::slotted (shouldn't match anything, but is a valid selector)
            ['name' => 'Slotted selector', 'selector' => '::slotted(foo)', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
            ['name' => 'Slotted selector (no matching closing paren)', 'selector' => '::slotted(foo', 'expect' => [], 'level' => 3, 'testType' => $TEST_QSA],
        ];
        /*
         * These selectors are intended to be used with the find(), findAll() and matches() methods.  Expected results
         * should be determined under the assumption that :scope will be prepended to the selector where appropriate,
         * in accordance with the specification.
         *
         * All of these should be valid relative selectors, expected to match zero or more elements in the document.
         * None should throw any errors.
         *
         *   name:      A descriptive name of the selector being tested
         *
         *   selector:  The selector to test
         *
         *   ctx:       A selector to obtain the context object to use for tests invoking context.find(),
         *              and to use as a single reference node for tests invoking document.find().
         *              Note: context = root.querySelector(ctx);
         *
         *   ref:       A selector to obtain the reference nodes to be used for the selector.
         *              Note: If root is the document or an in-document element:
         *                      refNodes = document.querySelectorAll(ref);
         *                    Otherwise, if root is a fragment or detached element:
         *                      refNodes = root.querySelectorAll(ref);
         *
         *   expect:    A list of IDs of the elements expected to be matched. List must be given in tree order.
         *
         *   unexpected: A list of IDs of some elements that are not expected to match the given selector.
         *               This is used to verify that unexpected.matches(selector, refNode) does not match.
         *
         *   exclude:   An array of contexts to exclude from testing. The valid values are:
         *              ["document", "element", "fragment", "detached", "html", "xhtml"]
         *              The "html" and "xhtml" values represent the type of document being queried. These are useful
         *              for tests that are affected by differences between HTML and XML, such as case sensitivity.
         *
         *   level:     An integer indicating the CSS or Selectors level in which the selector being tested was introduced.
         *
         *   testType:  A bit-mapped flag indicating the type of test.
         *
         * The test function for these tests accepts a specified root node, on which the methods will be invoked during the tests.
         *
         * Based on whether either 'context' or 'refNodes', or both, are specified the tests will execute the following methods:
         *
         * Where testType is TEST_FIND:
         *
         * context.findAll(selector, refNodes)
         * context.findAll(selector)        // Only if refNodes is not specified
         * root.findAll(selector, context)  // Only if refNodes is not specified
         * root.findAll(selector, refNodes) // Only if context is not specified
         * root.findAll(selector)           // Only if neither context nor refNodes is specified
         *
         * Where testType is TEST_QSA
         *
         * context.querySelectorAll(selector)
         * root.querySelectorAll(selector)  // Only if neither context nor refNodes is specified
         *
         * Equivalent tests will be run for .find() as well.
         * Note: Do not specify a testType of TEST_QSA where either implied :scope or explicit refNodes
         * are required.
         *
         * Where testType is TEST_MATCH:
         * For each expected result given, within the specified root:
         *
         * expect.matches(selector, context)    // Only where refNodes is not specified
         * expect.matches(selector, refNodes)
         * expect.matches(selector)             // Only if neither context nor refNodes is specified
         *
         * The tests involving refNodes for both find(), findAll() and matches() will each be run by passing the
         * collection as a NodeList, an Array and, if there is only a single element, an Element node.
         *
         * Note: Interactive pseudo-classes (:active :hover and :focus) have not been tested in this test suite.
         */
        $scopedSelectors = [
            //{name: "", selector: "", ctx: "", ref: "", expect: [], level: 1, testType: TEST_FIND | TEST_MATCH},
            // Attribute Selectors
            // - presence                  [att]
            ['name' => 'Attribute presence selector, matching align attribute with value', 'selector' => '.attr-presence-div1[align]', 'ctx' => '#attr-presence', 'expect' => ['attr-presence-div1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute presence selector, matching align attribute with empty value', 'selector' => '.attr-presence-div2[align]', 'ctx' => '#attr-presence', 'expect' => ['attr-presence-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute presence selector, matching title attribute, case insensitivity', 'selector' => '[TiTlE]', 'ctx' => '#attr-presence', 'expect' => ['attr-presence-a1', 'attr-presence-span1'], 'exclude' => ['xhtml'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute presence selector, not matching title attribute, case sensitivity', 'selector' => '[TiTlE]', 'ctx' => '#attr-presence', 'expect' => [], 'exclude' => ['html'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute presence selector, matching custom data-* attribute', 'selector' => '[data-attr-presence]', 'ctx' => '#attr-presence', 'expect' => ['attr-presence-pre1', 'attr-presence-blockquote1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute presence selector, not matching attribute with similar name', 'selector' => '.attr-presence-div3[align], .attr-presence-div4[align]', 'ctx' => '#attr-presence', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Attribute presence selector, matching attribute with non-ASCII characters', 'selector' => "ul[data-中文]", 'ctx' => '#attr-presence', 'expect' => ['attr-presence-ul1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute presence selector, not matching default option without selected attribute', 'selector' => '#attr-presence-select1 option[selected]', 'ctx' => '#attr-presence', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Attribute presence selector, matching option with selected attribute', 'selector' => '#attr-presence-select2 option[selected]', 'ctx' => '#attr-presence', 'expect' => ['attr-presence-select2-option4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute presence selector, matching multiple options with selected attributes', 'selector' => '#attr-presence-select3 option[selected]', 'ctx' => '#attr-presence', 'expect' => ['attr-presence-select3-option2', 'attr-presence-select3-option3'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - value                     [att=val]
            ['name' => 'Attribute value selector, matching align attribute with value', 'selector' => '[align="center"]', 'ctx' => '#attr-value', 'expect' => ['attr-value-div1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute value selector, matching align attribute with empty value', 'selector' => '[align=""]', 'ctx' => '#attr-value', 'expect' => ['attr-value-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute value selector, not matching align attribute with partial value', 'selector' => '[align="c"]', 'ctx' => '#attr-value', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Attribute value selector, not matching align attribute with incorrect value', 'selector' => '[align="centera"]', 'ctx' => '#attr-value', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Attribute value selector, matching custom data-* attribute with unicode escaped value', 'selector' => '[data-attr-value="\\e9"]', 'ctx' => '#attr-value', 'expect' => ['attr-value-div3'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute value selector, matching custom data-* attribute with escaped character', 'selector' => '[data-attr-value_foo="\\e9"]', 'ctx' => '#attr-value', 'expect' => ['attr-value-div4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute value selector with single-quoted value, matching multiple inputs with type attributes', 'selector' => "input[type='hidden'],#attr-value input[type='radio']", 'ctx' => '#attr-value', 'expect' => ['attr-value-input3', 'attr-value-input4', 'attr-value-input6', 'attr-value-input8', 'attr-value-input9'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute value selector with double-quoted value, matching multiple inputs with type attributes', 'selector' => "input[type=\"hidden\"],#attr-value input[type='radio']", 'ctx' => '#attr-value', 'expect' => ['attr-value-input3', 'attr-value-input4', 'attr-value-input6', 'attr-value-input8', 'attr-value-input9'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute value selector with unquoted value, matching multiple inputs with type attributes', 'selector' => 'input[type=hidden],#attr-value input[type=radio]', 'ctx' => '#attr-value', 'expect' => ['attr-value-input3', 'attr-value-input4', 'attr-value-input6', 'attr-value-input8', 'attr-value-input9'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute value selector, matching attribute with value using non-ASCII characters', 'selector' => "[data-attr-value=中文]", 'ctx' => '#attr-value', 'expect' => ['attr-value-div5'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - whitespace-separated list [att~=val]
            ['name' => 'Attribute whitespace-separated list selector, matching class attribute with value', 'selector' => '[class~="div1"]', 'ctx' => '#attr-whitespace', 'expect' => ['attr-whitespace-div1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector, not matching class attribute with empty value', 'selector' => '[class~=""]', 'ctx' => '#attr-whitespace', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Attribute whitespace-separated list selector, not matching class attribute with partial value', 'selector' => '[data-attr-whitespace~="div"]', 'ctx' => '#attr-whitespace', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Attribute whitespace-separated list selector, matching custom data-* attribute with unicode escaped value', 'selector' => '[data-attr-whitespace~="\\0000e9"]', 'ctx' => '#attr-whitespace', 'expect' => ['attr-whitespace-div4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector, matching custom data-* attribute with escaped character', 'selector' => '[data-attr-whitespace_foo~="\\e9"]', 'ctx' => '#attr-whitespace', 'expect' => ['attr-whitespace-div5'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector with single-quoted value, matching multiple links with rel attributes', 'selector' => "a[rel~='bookmark'],  #attr-whitespace a[rel~='nofollow']", 'ctx' => '#attr-whitespace', 'expect' => ['attr-whitespace-a1', 'attr-whitespace-a2', 'attr-whitespace-a3', 'attr-whitespace-a5', 'attr-whitespace-a7'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector with double-quoted value, matching multiple links with rel attributes', 'selector' => "a[rel~=\"bookmark\"],#attr-whitespace a[rel~='nofollow']", 'ctx' => '#attr-whitespace', 'expect' => ['attr-whitespace-a1', 'attr-whitespace-a2', 'attr-whitespace-a3', 'attr-whitespace-a5', 'attr-whitespace-a7'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector with unquoted value, matching multiple links with rel attributes', 'selector' => 'a[rel~=bookmark],    #attr-whitespace a[rel~=nofollow]', 'ctx' => '#attr-whitespace', 'expect' => ['attr-whitespace-a1', 'attr-whitespace-a2', 'attr-whitespace-a3', 'attr-whitespace-a5', 'attr-whitespace-a7'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute whitespace-separated list selector with double-quoted value, not matching value with space', 'selector' => 'a[rel~="book mark"]', 'ctx' => '#attr-whitespace', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Attribute whitespace-separated list selector, matching title attribute with value using non-ASCII characters', 'selector' => "[title~=中文]", 'ctx' => '#attr-whitespace', 'expect' => ['attr-whitespace-p1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - hyphen-separated list     [att|=val]
            ['name' => 'Attribute hyphen-separated list selector, not matching unspecified lang attribute', 'selector' => '#attr-hyphen-div1[lang|="en"]', 'ctx' => '#attr-hyphen', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Attribute hyphen-separated list selector, matching lang attribute with exact value', 'selector' => '#attr-hyphen-div2[lang|="fr"]', 'ctx' => '#attr-hyphen', 'expect' => ['attr-hyphen-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute hyphen-separated list selector, matching lang attribute with partial value', 'selector' => '#attr-hyphen-div3[lang|="en"]', 'ctx' => '#attr-hyphen', 'expect' => ['attr-hyphen-div3'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute hyphen-separated list selector, not matching incorrect value', 'selector' => '#attr-hyphen-div4[lang|="es-AR"]', 'ctx' => '#attr-hyphen', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            // - substring begins-with     [att^=val] (Level 3)
            ['name' => 'Attribute begins with selector, matching href attributes beginning with specified substring', 'selector' => 'a[href^="http://www"]', 'ctx' => '#attr-begins', 'expect' => ['attr-begins-a1', 'attr-begins-a3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute begins with selector, matching lang attributes beginning with specified substring, ', 'selector' => '[lang^="en-"]', 'ctx' => '#attr-begins', 'expect' => ['attr-begins-div2', 'attr-begins-div4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute begins with selector, not matching class attribute with empty value', 'selector' => '[class^=""]', 'ctx' => '#attr-begins', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'Attribute begins with selector, not matching class attribute not beginning with specified substring', 'selector' => '[class^=apple]', 'ctx' => '#attr-begins', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'Attribute begins with selector with single-quoted value, matching class attribute beginning with specified substring', 'selector' => "[class^=' apple']", 'ctx' => '#attr-begins', 'expect' => ['attr-begins-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute begins with selector with double-quoted value, matching class attribute beginning with specified substring', 'selector' => '[class^=" apple"]', 'ctx' => '#attr-begins', 'expect' => ['attr-begins-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute begins with selector with unquoted value, not matching class attribute not beginning with specified substring', 'selector' => '[class^= apple]', 'ctx' => '#attr-begins', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            // - substring ends-with       [att$=val] (Level 3)
            ['name' => 'Attribute ends with selector, matching href attributes ending with specified substring', 'selector' => 'a[href$=".org"]', 'ctx' => '#attr-ends', 'expect' => ['attr-ends-a1', 'attr-ends-a3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute ends with selector, matching lang attributes ending with specified substring, ', 'selector' => '[lang$="-CH"]', 'ctx' => '#attr-ends', 'expect' => ['attr-ends-div2', 'attr-ends-div4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute ends with selector, not matching class attribute with empty value', 'selector' => '[class$=""]', 'ctx' => '#attr-ends', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'Attribute ends with selector, not matching class attribute not ending with specified substring', 'selector' => '[class$=apple]', 'ctx' => '#attr-ends', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'Attribute ends with selector with single-quoted value, matching class attribute ending with specified substring', 'selector' => "[class\$='apple ']", 'ctx' => '#attr-ends', 'expect' => ['attr-ends-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute ends with selector with double-quoted value, matching class attribute ending with specified substring', 'selector' => '[class$="apple "]', 'ctx' => '#attr-ends', 'expect' => ['attr-ends-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute ends with selector with unquoted value, not matching class attribute not ending with specified substring', 'selector' => '[class$=apple ]', 'ctx' => '#attr-ends', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            // - substring contains        [att*=val] (Level 3)
            ['name' => 'Attribute contains selector, matching href attributes beginning with specified substring', 'selector' => 'a[href*="http://www"]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-a1', 'attr-contains-a3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector, matching href attributes ending with specified substring', 'selector' => 'a[href*=".org"]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-a1', 'attr-contains-a2'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector, matching href attributes containing specified substring', 'selector' => 'a[href*=".example."]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-a1', 'attr-contains-a3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector, matching lang attributes beginning with specified substring, ', 'selector' => '[lang*="en-"]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-div2', 'attr-contains-div6'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector, matching lang attributes ending with specified substring, ', 'selector' => '[lang*="-CH"]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-div3', 'attr-contains-div5'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector, not matching class attribute with empty value', 'selector' => '[class*=""]', 'ctx' => '#attr-contains', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'Attribute contains selector with single-quoted value, matching class attribute beginning with specified substring', 'selector' => "[class*=' apple']", 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector with single-quoted value, matching class attribute ending with specified substring', 'selector' => "[class*='orange ']", 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector with single-quoted value, matching class attribute containing specified substring', 'selector' => "[class*='ple banana ora']", 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector with double-quoted value, matching class attribute beginning with specified substring', 'selector' => '[class*=" apple"]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector with double-quoted value, matching class attribute ending with specified substring', 'selector' => '[class*="orange "]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector with double-quoted value, matching class attribute containing specified substring', 'selector' => '[class*="ple banana ora"]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector with unquoted value, matching class attribute beginning with specified substring', 'selector' => '[class*= apple]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector with unquoted value, matching class attribute ending with specified substring', 'selector' => '[class*=orange ]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Attribute contains selector with unquoted value, matching class attribute containing specified substring', 'selector' => '[class*= banana ]', 'ctx' => '#attr-contains', 'expect' => ['attr-contains-p1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // Pseudo-classes
            // - :root                 (Level 3)
            ['name' => ':root pseudo-class selector, matching document root element', 'selector' => ':root', 'expect' => ['html'], 'exclude' => ['element', 'fragment', 'detached'], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => ':root pseudo-class selector, not matching document root element', 'selector' => ':root', 'expect' => [], 'exclude' => ['document'], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => ':root pseudo-class selector, not matching document root element', 'selector' => ':root', 'ctx' => '#html', 'expect' => [], 'exclude' => ['fragment', 'detached'], 'level' => 3, 'testType' => $TEST_FIND],
            // - :nth-child(n)         (Level 3)
            ['name' => ':nth-child selector, matching the third child element', 'selector' => ':nth-child(3)', 'ctx' => '#pseudo-nth-table1', 'expect' => ['pseudo-nth-td3', 'pseudo-nth-td9', 'pseudo-nth-tr3', 'pseudo-nth-td15'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-child selector, matching every third child element', 'selector' => 'li:nth-child(3n)', 'ctx' => '#pseudo-nth', 'expect' => ['pseudo-nth-li3', 'pseudo-nth-li6', 'pseudo-nth-li9', 'pseudo-nth-li12'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-child selector, matching every second child element, starting from the fourth', 'selector' => 'li:nth-child(2n+4)', 'ctx' => '#pseudo-nth', 'expect' => ['pseudo-nth-li4', 'pseudo-nth-li6', 'pseudo-nth-li8', 'pseudo-nth-li10', 'pseudo-nth-li12'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-child selector, matching every second child element, starting from the fourth, with whitespace', 'selector' => "li:nth-child(2n \t\r\n+ \t\r\n4)", 'ctx' => '#pseudo-nth', 'expect' => ['pseudo-nth-li4', 'pseudo-nth-li6', 'pseudo-nth-li8', 'pseudo-nth-li10', 'pseudo-nth-li12'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-child selector, matching every fourth child element, starting from the third', 'selector' => ':nth-child(4n-1)', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-em2', 'pseudo-nth-span3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-child selector, matching every fourth child element, starting from the third, with whitespace', 'selector' => ":nth-child(4n \t\r\n- \t\r\n1)", 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-em2', 'pseudo-nth-span3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-child selector used twice, matching ', 'selector' => ':nth-child(1) :nth-child(1)', 'ctx' => '#pseudo-nth', 'expect' => ['pseudo-nth-table1', 'pseudo-nth-tr1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :nth-last-child       (Level 3)
            ['name' => ':nth-last-child selector, matching the third last child element', 'selector' => ':nth-last-child(3)', 'ctx' => '#pseudo-nth-table1', 'expect' => ['pseudo-nth-tr1', 'pseudo-nth-td4', 'pseudo-nth-td10', 'pseudo-nth-td16'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-last-child selector, matching every third child element from the end', 'selector' => 'li:nth-last-child(3n)', 'ctx' => 'pseudo-nth', 'expect' => ['pseudo-nth-li1', 'pseudo-nth-li4', 'pseudo-nth-li7', 'pseudo-nth-li10'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-last-child selector, matching every second child element from the end, starting from the fourth last', 'selector' => 'li:nth-last-child(2n+4)', 'ctx' => 'pseudo-nth', 'expect' => ['pseudo-nth-li1', 'pseudo-nth-li3', 'pseudo-nth-li5', 'pseudo-nth-li7', 'pseudo-nth-li9'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-last-child selector, matching every fourth element from the end, starting from the third last', 'selector' => ':nth-last-child(4n-1)', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-span2', 'pseudo-nth-span4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :nth-of-type(n)       (Level 3)
            ['name' => ':nth-of-type selector, matching the third em element', 'selector' => 'em:nth-of-type(3)', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-em3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-of-type selector, matching every second element of their type', 'selector' => ':nth-of-type(2n)', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-em2', 'pseudo-nth-span2', 'pseudo-nth-span4', 'pseudo-nth-strong2', 'pseudo-nth-em4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-of-type selector, matching every second elemetn of their type, starting from the first', 'selector' => 'span:nth-of-type(2n-1)', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-span1', 'pseudo-nth-span3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :nth-last-of-type(n)  (Level 3)
            ['name' => ':nth-last-of-type selector, matching the third last em element', 'selector' => 'em:nth-last-of-type(3)', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-em2'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-last-of-type selector, matching every second last element of their type', 'selector' => ':nth-last-of-type(2n)', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-span1', 'pseudo-nth-em1', 'pseudo-nth-strong1', 'pseudo-nth-em3', 'pseudo-nth-span3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':nth-last-of-type selector, matching every second last element of their type, starting from the last', 'selector' => 'span:nth-last-of-type(2n-1)', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-span2', 'pseudo-nth-span4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :first-of-type        (Level 3)
            ['name' => ':first-of-type selector, matching the first em element', 'selector' => 'em:first-of-type', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-em1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':first-of-type selector, matching the first of every type of element', 'selector' => ':first-of-type', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-span1', 'pseudo-nth-em1', 'pseudo-nth-strong1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':first-of-type selector, matching the first td element in each table row', 'selector' => 'tr :first-of-type', 'ctx' => '#pseudo-nth-table1', 'expect' => ['pseudo-nth-td1', 'pseudo-nth-td7', 'pseudo-nth-td13'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :last-of-type         (Level 3)
            ['name' => ':last-of-type selector, matching the last em elemnet', 'selector' => 'em:last-of-type', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-em4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':last-of-type selector, matching the last of every type of element', 'selector' => ':last-of-type', 'ctx' => '#pseudo-nth-p1', 'expect' => ['pseudo-nth-span4', 'pseudo-nth-strong2', 'pseudo-nth-em4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':last-of-type selector, matching the last td element in each table row', 'selector' => 'tr :last-of-type', 'ctx' => '#pseudo-nth-table1', 'expect' => ['pseudo-nth-td6', 'pseudo-nth-td12', 'pseudo-nth-td18'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :first-child
            ['name' => ':first-child pseudo-class selector, matching first child div element', 'selector' => 'div:first-child', 'ctx' => '#pseudo-first-child', 'expect' => ['pseudo-first-child-div1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ":first-child pseudo-class selector, doesn't match non-first-child elements", 'selector' => '.pseudo-first-child-div2:first-child, .pseudo-first-child-div3:first-child', 'ctx' => '#pseudo-first-child', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => ':first-child pseudo-class selector, matching first-child of multiple elements', 'selector' => 'span:first-child', 'ctx' => '#pseudo-first-child', 'expect' => ['pseudo-first-child-span1', 'pseudo-first-child-span3', 'pseudo-first-child-span5'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :last-child           (Level 3)
            ['name' => ':last-child pseudo-class selector, matching last child div element', 'selector' => 'div:last-child', 'ctx' => '#pseudo-last-child', 'expect' => ['pseudo-last-child-div3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ":last-child pseudo-class selector, doesn't match non-last-child elements", 'selector' => '.pseudo-last-child-div1:last-child, .pseudo-last-child-div2:first-child', 'ctx' => '#pseudo-last-child', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => ':last-child pseudo-class selector, matching first-child of multiple elements', 'selector' => 'span:last-child', 'ctx' => '#pseudo-last-child', 'expect' => ['pseudo-last-child-span2', 'pseudo-last-child-span4', 'pseudo-last-child-span6'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :only-child           (Level 3)
            ['name' => ':pseudo-only-child pseudo-class selector, matching all only-child elements', 'selector' => ':only-child', 'ctx' => '#pseudo-only', 'expect' => ['pseudo-only-span1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':pseudo-only-child pseudo-class selector, matching only-child em elements', 'selector' => 'em:only-child', 'ctx' => '#pseudo-only', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            // - :only-of-type         (Level 3)
            ['name' => ':pseudo-only-of-type pseudo-class selector, matching all elements with no siblings of the same type', 'selector' => ' :only-of-type', 'ctx' => '#pseudo-only', 'expect' => ['pseudo-only-span1', 'pseudo-only-em1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':pseudo-only-of-type pseudo-class selector, matching em elements with no siblings of the same type', 'selector' => ' em:only-of-type', 'ctx' => '#pseudo-only', 'expect' => ['pseudo-only-em1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :empty                (Level 3)
            ['name' => ':empty pseudo-class selector, matching empty p elements', 'selector' => 'p:empty', 'ctx' => '#pseudo-empty', 'expect' => ['pseudo-empty-p1', 'pseudo-empty-p2'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':empty pseudo-class selector, matching all empty elements', 'selector' => ':empty', 'ctx' => '#pseudo-empty', 'expect' => ['pseudo-empty-p1', 'pseudo-empty-p2', 'pseudo-empty-span1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :link and :visited
            // Implementations may treat all visited links as unvisited, so these cannot be tested separately.
            // The only guarantee is that ":link,:visited" matches the set of all visited and unvisited links and that they are individually mutually exclusive sets.
            ['name' => ':link and :visited pseudo-class selectors, matching a and area elements with href attributes', 'selector' => ' :link, #pseudo-link :visited', 'ctx' => '#pseudo-link', 'expect' => ['pseudo-link-a1', 'pseudo-link-a2', 'pseudo-link-area1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':link and :visited pseudo-class selectors, matching no elements', 'selector' => ' :link, #head :visited', 'ctx' => '#head', 'expect' => [], 'exclude' => ['element', 'fragment', 'detached'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':link and :visited pseudo-class selectors, not matching link elements with href attributes', 'selector' => ' :link, #head :visited', 'ctx' => '#head', 'expect' => [], 'exclude' => ['document'], 'level' => 1, 'testType' => $TEST_FIND],
            ['name' => ':link and :visited pseudo-class selectors, chained, mutually exclusive pseudo-classes match nothing', 'selector' => ':link:visited', 'ctx' => '#html', 'expect' => [], 'exclude' => ['document'], 'level' => 1, 'testType' => $TEST_FIND],
            // XXX Figure out context or refNodes for this
            // - :target               (Level 3)
            ['name' => ':target pseudo-class selector, matching the element referenced by the URL fragment identifier', 'selector' => ':target', 'ctx' => '', 'expect' => [], 'exclude' => ['document', 'element'], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => ':target pseudo-class selector, matching the element referenced by the URL fragment identifier', 'selector' => ':target', 'ctx' => '', 'expect' => ['target'], 'exclude' => ['fragment', 'detached'], 'level' => 3, 'testType' => $TEST_FIND],
            // XXX Fix ctx in tests below
            // - :lang()
            ['name' => ':lang pseudo-class selector, matching inherited language (1)', 'selector' => '#pseudo-lang-div1:lang(en)', 'ctx' => '', 'expect' => ['pseudo-lang-div1'], 'exclude' => ['detached', 'fragment'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':lang pseudo-class selector, not matching element with no inherited language', 'selector' => '#pseudo-lang-div1:lang(en)', 'ctx' => '', 'expect' => [], 'exclude' => ['document', 'element'], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => ':lang pseudo-class selector, matching specified language with exact value (1)', 'selector' => '#pseudo-lang-div2:lang(fr)', 'ctx' => '', 'expect' => ['pseudo-lang-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':lang pseudo-class selector, matching specified language with partial value (1)', 'selector' => '#pseudo-lang-div3:lang(en)', 'ctx' => '', 'expect' => ['pseudo-lang-div3'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':lang pseudo-class selector, not matching incorrect language', 'selector' => '#pseudo-lang-div4:lang(es-AR)', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            // - :enabled              (Level 3)
            ['name' => ':enabled pseudo-class selector, matching all enabled form controls (1)', 'selector' => '#pseudo-ui :enabled', 'ctx' => '', 'expect' => ['pseudo-ui-input1', 'pseudo-ui-input2', 'pseudo-ui-input3', 'pseudo-ui-input4', 'pseudo-ui-input5', 'pseudo-ui-input6', 'pseudo-ui-input7', 'pseudo-ui-input8', 'pseudo-ui-input9', 'pseudo-ui-textarea1', 'pseudo-ui-button1'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':enabled pseudo-class selector, not matching link elements (1)', 'selector' => '#pseudo-link :enabled', 'ctx' => '', 'expect' => [], 'unexpected' => ['pseudo-link-a1', 'pseudo-link-a2', 'pseudo-link-a3', 'pseudo-link-map1', 'pseudo-link-area1', 'pseudo-link-area2'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :disabled             (Level 3)
            ['name' => ':disabled pseudo-class selector, matching all disabled form controls (1)', 'selector' => '#pseudo-ui :disabled', 'ctx' => '', 'expect' => ['pseudo-ui-input10', 'pseudo-ui-input11', 'pseudo-ui-input12', 'pseudo-ui-input13', 'pseudo-ui-input14', 'pseudo-ui-input15', 'pseudo-ui-input16', 'pseudo-ui-input17', 'pseudo-ui-input18', 'pseudo-ui-textarea2', 'pseudo-ui-button2'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':disabled pseudo-class selector, not matching link elements (1)', 'selector' => '#pseudo-link :disabled', 'ctx' => '', 'expect' => [], 'unexpected' => ['pseudo-link-a1', 'pseudo-link-a2', 'pseudo-link-a3', 'pseudo-link-map1', 'pseudo-link-area1', 'pseudo-link-area2'], 'level' => 3, 'testType' => $TEST_QSA | $TEST_MATCH],
            // - :checked              (Level 3)
            ['name' => ':checked pseudo-class selector, matching checked radio buttons and checkboxes (1)', 'selector' => '#pseudo-ui :checked', 'ctx' => '', 'expect' => ['pseudo-ui-input4', 'pseudo-ui-input6', 'pseudo-ui-input13', 'pseudo-ui-input15'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - :not(s)               (Level 3)
            ['name' => ':not pseudo-class selector, matching (1)', 'selector' => '#not>:not(div)', 'ctx' => '', 'expect' => ['not-p1', 'not-p2', 'not-p3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':not pseudo-class selector, matching (1)', 'selector' => '#not * :not(:first-child)', 'ctx' => '', 'expect' => ['not-em1', 'not-em2', 'not-em3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => ':not pseudo-class selector, matching nothing', 'selector' => ':not(*)', 'ctx' => '', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => ':not pseudo-class selector, matching nothing', 'selector' => ':not(*|*)', 'ctx' => '', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            // Pseudo-elements
            // - ::first-line
            ['name' => ':first-line pseudo-element (one-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element:first-line', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => '::first-line pseudo-element (two-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element::first-line', 'ctx' => '', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            // - ::first-letter
            ['name' => ':first-letter pseudo-element (one-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element:first-letter', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => '::first-letter pseudo-element (two-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element::first-letter', 'ctx' => '', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            // - ::before
            ['name' => ':before pseudo-element (one-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element:before', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => '::before pseudo-element (two-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element::before', 'ctx' => '', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            // - ::after
            ['name' => ':after pseudo-element (one-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element:after', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => '::after pseudo-element (two-colon syntax) selector, not matching any elements', 'selector' => '#pseudo-element::after', 'ctx' => '', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            // Class Selectors
            ['name' => 'Class selector, matching element with specified class (1)', 'selector' => '.class-p', 'ctx' => '', 'expect' => ['class-p1', 'class-p2', 'class-p3'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Class selector, chained, matching only elements with all specified classes (1)', 'selector' => '#class .apple.orange.banana', 'ctx' => '', 'expect' => ['class-div1', 'class-div2', 'class-p4', 'class-div3', 'class-p6', 'class-div4'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Class Selector, chained, with type selector (1)', 'selector' => 'div.apple.banana.orange', 'ctx' => '', 'expect' => ['class-div1', 'class-div2', 'class-div3', 'class-div4'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Class selector, matching element with class value using non-ASCII characters (2)', 'selector' => ".台北Táiběi", 'ctx' => '', 'expect' => ['class-span1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Class selector, matching multiple elements with class value using non-ASCII characters (1)', 'selector' => ".台北", 'ctx' => '', 'expect' => ['class-span1', 'class-span2'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Class selector, chained, matching element with multiple class values using non-ASCII characters (2)', 'selector' => ".台北Táiběi.台北", 'ctx' => '', 'expect' => ['class-span1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Class selector, matching element with class with escaped character (1)', 'selector' => '.foo\\:bar', 'ctx' => '', 'expect' => ['class-span3'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Class selector, matching element with class with escaped character (1)', 'selector' => '.test\\.foo\\[5\\]bar', 'ctx' => '', 'expect' => ['class-span4'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            // ID Selectors
            ['name' => 'ID selector, matching element with specified id (1)', 'selector' => '#id #id-div1', 'ctx' => '', 'expect' => ['id-div1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'ID selector, chained, matching element with specified id (1)', 'selector' => '#id-div1, #id-div1', 'ctx' => '', 'expect' => ['id-div1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'ID selector, chained, matching element with specified id (1)', 'selector' => '#id-div1, #id-div2', 'ctx' => '', 'expect' => ['id-div1', 'id-div2'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'ID Selector, chained, with type selector (1)', 'selector' => 'div#id-div1, div#id-div2', 'ctx' => '', 'expect' => ['id-div1', 'id-div2'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'ID selector, not matching non-existent descendant', 'selector' => '#id #none', 'ctx' => '', 'expect' => [], 'level' => 1, 'testType' => $TEST_FIND],
            ['name' => 'ID selector, not matching non-existent ancestor', 'selector' => '#none #id-div1', 'ctx' => '', 'expect' => [], 'level' => 1, 'testType' => $TEST_FIND],
            ['name' => 'ID selector, matching multiple elements with duplicate id (1)', 'selector' => '#id-li-duplicate', 'ctx' => '', 'expect' => ['id-li-duplicate', 'id-li-duplicate', 'id-li-duplicate', 'id-li-duplicate'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'ID selector, matching id value using non-ASCII characters (3)', 'selector' => "#台北Táiběi", 'ctx' => '', 'expect' => ["台北Táiběi"], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'ID selector, matching id value using non-ASCII characters (4)', 'selector' => "#台北", 'ctx' => '', 'expect' => ["台北"], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'ID selector, matching id values using non-ASCII characters (2)', 'selector' => "#台北Táiběi, #台北", 'ctx' => '', 'expect' => ["台北Táiběi", "台北"], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            // XXX runMatchesTest() in level2-lib.js can't handle this because obtaining the expected nodes requires escaping characters when generating the selector from 'expect' values
            ['name' => 'ID selector, matching element with id with escaped character', 'selector' => '#\\#foo\\:bar', 'ctx' => '', 'expect' => ['#foo:bar'], 'level' => 1, 'testType' => $TEST_FIND],
            ['name' => 'ID selector, matching element with id with escaped character', 'selector' => '#test\\.foo\\[5\\]bar', 'ctx' => '', 'expect' => ['test.foo[5]bar'], 'level' => 1, 'testType' => $TEST_FIND],
            // Namespaces
            // XXX runMatchesTest() in level2-lib.js can't handle these because non-HTML elements don't have a recognised id
            ['name' => 'Namespace selector, matching element with any namespace', 'selector' => '#any-namespace *|div', 'ctx' => '', 'expect' => ['any-namespace-div1', 'any-namespace-div2', 'any-namespace-div3', 'any-namespace-div4'], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'Namespace selector, matching div elements in no namespace only', 'selector' => '#no-namespace |div', 'ctx' => '', 'expect' => ['no-namespace-div3'], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'Namespace selector, matching any elements in no namespace only', 'selector' => '#no-namespace |*', 'ctx' => '', 'expect' => ['no-namespace-div3'], 'level' => 3, 'testType' => $TEST_FIND],
            // Combinators
            // - Descendant combinator ' '
            ['name' => 'Descendant combinator, matching element that is a descendant of an element with id (1)', 'selector' => '#descendant div', 'ctx' => '', 'expect' => ['descendant-div1', 'descendant-div2', 'descendant-div3', 'descendant-div4'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with id that is a descendant of an element (1)', 'selector' => 'body #descendant-div1', 'ctx' => '', 'expect' => ['descendant-div1'], 'exclude' => ['detached', 'fragment'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with id that is a descendant of an element (1)', 'selector' => 'div #descendant-div1', 'ctx' => '', 'expect' => ['descendant-div1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with id that is a descendant of an element with id (1)', 'selector' => '#descendant #descendant-div2', 'ctx' => '', 'expect' => ['descendant-div2'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with class that is a descendant of an element with id (1)', 'selector' => '#descendant .descendant-div2', 'ctx' => '', 'expect' => ['descendant-div2'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Descendant combinator, matching element with class that is a descendant of an element with class (1)', 'selector' => '.descendant-div1 .descendant-div3', 'ctx' => '', 'expect' => ['descendant-div3'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Descendant combinator, not matching element with id that is not a descendant of an element with id', 'selector' => '#descendant-div1 #descendant-div4', 'ctx' => '', 'expect' => [], 'level' => 1, 'testType' => $TEST_FIND],
            ['name' => 'Descendant combinator, whitespace characters (1)', 'selector' => "#descendant\t\r\n#descendant-div2", 'ctx' => '', 'expect' => ['descendant-div2'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            // // - Descendant combinator '>>'
            // {name: "Descendant combinator '>>', matching element that is a descendant of an element with id (1)",                 selector: "#descendant>>div",                   ctx: "", expect: ["descendant-div1", "descendant-div2", "descendant-div3", "descendant-div4"], level: 1, testType: TEST_FIND | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with id that is a descendant of an element (1)",                 selector: "body>>#descendant-div1",             ctx: "", expect: ["descendant-div1"], exclude: ["detached", "fragment"], level: 1, testType: TEST_FIND | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with id that is a descendant of an element (1)",                 selector: "div>>#descendant-div1",              ctx: "", expect: ["descendant-div1"],                                    level: 1, testType: TEST_FIND | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with id that is a descendant of an element with id (1)",         selector: "#descendant>>#descendant-div2",      ctx: "", expect: ["descendant-div2"],                                    level: 1, testType: TEST_FIND | TEST_MATCH},
            // {name: "Descendant combinator '>>', matching element with class that is a descendant of an element with id (1)",      selector: "#descendant>>.descendant-div2",      ctx: "", expect: ["descendant-div2"],                                    level: 1, testType: TEST_FIND | TEST_MATCH},
            // {name: "Descendant combinator, '>>', matching element with class that is a descendant of an element with class (1)",   selector: ".descendant-div1>>.descendant-div3", ctx: "", expect: ["descendant-div3"],                                    level: 1, testType: TEST_FIND | TEST_MATCH},
            // {name: "Descendant combinator '>>', not matching element with id that is not a descendant of an element with id", selector: "#descendant-div1>>#descendant-div4", ctx: "", expect: [] /*no matches*/,                                      level: 1, testType: TEST_FIND},
            // - Child combinator '>'
            ['name' => 'Child combinator, matching element that is a child of an element with id (1)', 'selector' => '#child>div', 'ctx' => '', 'expect' => ['child-div1', 'child-div4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Child combinator, matching element with id that is a child of an element (1)', 'selector' => 'div>#child-div1', 'ctx' => '', 'expect' => ['child-div1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Child combinator, matching element with id that is a child of an element with id (1)', 'selector' => '#child>#child-div1', 'ctx' => '', 'expect' => ['child-div1'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Child combinator, matching element with id that is a child of an element with class (1)', 'selector' => '#child-div1>.child-div2', 'ctx' => '', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Child combinator, matching element with class that is a child of an element with class (1)', 'selector' => '.child-div1>.child-div2', 'ctx' => '', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Child combinator, not matching element with id that is not a child of an element with id', 'selector' => '#child>#child-div3', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Child combinator, not matching element with id that is not a child of an element with class', 'selector' => '#child-div1>.child-div3', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Child combinator, not matching element with class that is not a child of an element with class', 'selector' => '.child-div1>.child-div3', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Child combinator, surrounded by whitespace (1)', 'selector' => "#child-div1\t\r\n>\t\r\n#child-div2", 'ctx' => '', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Child combinator, whitespace after (1)', 'selector' => "#child-div1>\t\r\n#child-div2", 'ctx' => '', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Child combinator, whitespace before (1)', 'selector' => "#child-div1\t\r\n>#child-div2", 'ctx' => '', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Child combinator, no whitespace (1)', 'selector' => '#child-div1>#child-div2', 'ctx' => '', 'expect' => ['child-div2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - Adjacent sibling combinator '+'
            ['name' => 'Adjacent sibling combinator, matching element that is an adjacent sibling of an element with id (1)', 'selector' => '#adjacent-div2+div', 'ctx' => '', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching element with id that is an adjacent sibling of an element (1)', 'selector' => 'div+#adjacent-div4', 'ctx' => '', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching element with id that is an adjacent sibling of an element with id (1)', 'selector' => '#adjacent-div2+#adjacent-div4', 'ctx' => '', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching element with class that is an adjacent sibling of an element with id (1)', 'selector' => '#adjacent-div2+.adjacent-div4', 'ctx' => '', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching element with class that is an adjacent sibling of an element with class (1)', 'selector' => '.adjacent-div2+.adjacent-div4', 'ctx' => '', 'expect' => ['adjacent-div4'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, matching p element that is an adjacent sibling of a div element (1)', 'selector' => '#adjacent div+p', 'ctx' => '', 'expect' => ['adjacent-p2'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, not matching element with id that is not an adjacent sibling of an element with id', 'selector' => '#adjacent-div2+#adjacent-p2, #adjacent-div2+#adjacent-div1', 'ctx' => '', 'expect' => [], 'level' => 2, 'testType' => $TEST_FIND],
            ['name' => 'Adjacent sibling combinator, surrounded by whitespace (1)', 'selector' => "#adjacent-p2\t\r\n+\t\r\n#adjacent-p3", 'ctx' => '', 'expect' => ['adjacent-p3'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, whitespace after (1)', 'selector' => "#adjacent-p2+\t\r\n#adjacent-p3", 'ctx' => '', 'expect' => ['adjacent-p3'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, whitespace before (1)', 'selector' => "#adjacent-p2\t\r\n+#adjacent-p3", 'ctx' => '', 'expect' => ['adjacent-p3'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Adjacent sibling combinator, no whitespace (1)', 'selector' => '#adjacent-p2+#adjacent-p3', 'ctx' => '', 'expect' => ['adjacent-p3'], 'level' => 2, 'testType' => $TEST_FIND | $TEST_MATCH],
            // - General sibling combinator ~ (Level 3)
            ['name' => 'General sibling combinator, matching element that is a sibling of an element with id (1)', 'selector' => '#sibling-div2~div', 'ctx' => '', 'expect' => ['sibling-div4', 'sibling-div6'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'General sibling combinator, matching element with id that is a sibling of an element (1)', 'selector' => 'div~#sibling-div4', 'ctx' => '', 'expect' => ['sibling-div4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'General sibling combinator, matching element with id that is a sibling of an element with id (1)', 'selector' => '#sibling-div2~#sibling-div4', 'ctx' => '', 'expect' => ['sibling-div4'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'General sibling combinator, matching element with class that is a sibling of an element with id (1)', 'selector' => '#sibling-div2~.sibling-div', 'ctx' => '', 'expect' => ['sibling-div4', 'sibling-div6'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'General sibling combinator, matching p element that is a sibling of a div element (1)', 'selector' => '#sibling div~p', 'ctx' => '', 'expect' => ['sibling-p2', 'sibling-p3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'General sibling combinator, not matching element with id that is not a sibling after a p element (1)', 'selector' => '#sibling>p~div', 'ctx' => '', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'General sibling combinator, not matching element with id that is not a sibling after an element with id', 'selector' => '#sibling-div2~#sibling-div3, #sibling-div2~#sibling-div1', 'ctx' => '', 'expect' => [], 'level' => 3, 'testType' => $TEST_FIND],
            ['name' => 'General sibling combinator, surrounded by whitespace (1)', 'selector' => "#sibling-p2\t\r\n~\t\r\n#sibling-p3", 'ctx' => '', 'expect' => ['sibling-p3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'General sibling combinator, whitespace after (1)', 'selector' => "#sibling-p2~\t\r\n#sibling-p3", 'ctx' => '', 'expect' => ['sibling-p3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'General sibling combinator, whitespace before (1)', 'selector' => "#sibling-p2\t\r\n~#sibling-p3", 'ctx' => '', 'expect' => ['sibling-p3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'General sibling combinator, no whitespace (1)', 'selector' => '#sibling-p2~#sibling-p3', 'ctx' => '', 'expect' => ['sibling-p3'], 'level' => 3, 'testType' => $TEST_FIND | $TEST_MATCH],
            // Group of selectors (comma)
            ['name' => 'Syntax, group of selectors separator, surrounded by whitespace (1)', 'selector' => "#group em\t\r \n,\t\r \n#group strong", 'ctx' => '', 'expect' => ['group-em1', 'group-strong1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Syntax, group of selectors separator, whitespace after (1)', 'selector' => "#group em,\t\r\n#group strong", 'ctx' => '', 'expect' => ['group-em1', 'group-strong1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Syntax, group of selectors separator, whitespace before (1)', 'selector' => "#group em\t\r\n,#group strong", 'ctx' => '', 'expect' => ['group-em1', 'group-strong1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
            ['name' => 'Syntax, group of selectors separator, no whitespace (1)', 'selector' => '#group em,#group strong', 'ctx' => '', 'expect' => ['group-em1', 'group-strong1'], 'level' => 1, 'testType' => $TEST_FIND | $TEST_MATCH],
        ];
    }
}

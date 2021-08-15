<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-getElementsByTagNameNS.html.
class DocumentGetElementsByTagNameNSTest extends WPTTestHarness
{
    public function helperTestGetElementsByTagNameNS($context, $element)
    {
        $this->assertTest(function () use(&$context) {
            $this->wptAssertFalse($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'html') instanceof NodeList, 'NodeList');
            $this->wptAssertTrue($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'html') instanceof HTMLCollection, 'HTMLCollection');
            $firstCollection = $context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'html');
            $secondCollection = $context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'html');
            $this->wptAssertTrue($firstCollection !== $secondCollection || $firstCollection === $secondCollection, 'Caching is allowed.');
        });
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'body'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $actual = $context->getElementsByTagNameNS('*', 'body');
            $expected = [];
            $get_elements = function ($node) use(&$expected, &$get_elements) {
                for ($i = 0; $i < count($node->childNodes); $i++) {
                    $child = $node->childNodes[$i];
                    if ($child->nodeType === $child::ELEMENT_NODE) {
                        if ($child->localName == 'body') {
                            $expected[] = $child;
                        }
                        $get_elements($child);
                    }
                }
            };
            $get_elements($context);
            $this->wptAssertArrayEquals($actual, $expected);
        }, "getElementsByTagNameNS('*', 'body')");
        $this->assertTest(function () use(&$context, &$element) {
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('', '*'), []);
            $t = $element->appendChild($this->doc->createElementNS('', 'body'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('', '*'), [$t]);
        }, 'Empty string namespace');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'body'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', 'body'), [$t]);
        }, 'body element in test namespace, no prefix');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'test:body'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', 'body'), [$t]);
        }, 'body element in test namespace, prefix');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'BODY'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', 'BODY'), [$t]);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', 'body'), []);
        }, 'BODY element in test namespace, no prefix');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('http://www.w3.org/1999/xhtml', 'abc'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'abc'), [$t]);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'ABC'), []);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', 'ABC'), []);
        }, 'abc element in html namespace');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('http://www.w3.org/1999/xhtml', 'ABC'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'abc'), []);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'ABC'), [$t]);
        }, 'ABC element in html namespace');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('http://www.w3.org/1999/xhtml', "AÇ"));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', "AÇ"), [$t]);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', "aÇ"), []);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', "aç"), []);
        }, "AÇ, case sensitivity");
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'test:BODY'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', 'BODY'), [$t]);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('test', 'body'), []);
        }, 'BODY element in test namespace, prefix');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'test:test'));
            $this->add_cleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $actual = $context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', '*');
            $expected = [];
            $get_elements = function ($node) use(&$t, &$expected, &$get_elements) {
                for ($i = 0; $i < count($node->childNodes); $i++) {
                    $child = $node->childNodes[$i];
                    if ($child->nodeType === $child::ELEMENT_NODE) {
                        if ($child !== $t) {
                            $expected[] = $child;
                        }
                        $get_elements($child);
                    }
                }
            };
            $get_elements($context);
            $this->wptAssertArrayEquals($actual, $expected);
        }, "getElementsByTagNameNS('http://www.w3.org/1999/xhtml', '*')");
        $this->assertTest(function () use(&$context) {
            $actual = $context->getElementsByTagNameNS('*', '*');
            $expected = [];
            $get_elements = function ($node) use(&$expected, &$get_elements) {
                for ($i = 0; $i < count($node->childNodes); $i++) {
                    $child = $node->childNodes[$i];
                    if ($child->nodeType === $child::ELEMENT_NODE) {
                        $expected[] = $child;
                        $get_elements($child);
                    }
                }
            };
            $get_elements($context);
            $this->wptAssertArrayEquals($actual, $expected);
        }, "getElementsByTagNameNS('*', '*')");
        $this->assertTest(function () use(&$context) {
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS('**', '*'), []);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS(null, '0'), []);
            $this->wptAssertArrayEquals($context->getElementsByTagNameNS(null, 'div'), []);
        }, 'Empty lists');
        $this->assertTest(function () use(&$element, &$context) {
            $t1 = $element->appendChild($this->doc->createElementNS('test', 'abc'));
            $this->add_cleanup(function () use(&$element, &$t1) {
                $element->removeChild($t1);
            });
            $l = $context->getElementsByTagNameNS('test', 'abc');
            $this->wptAssertTrue($l instanceof HTMLCollection);
            $this->wptAssertEquals(count($l), 1);
            $t2 = $element->appendChild($this->doc->createElementNS('test', 'abc'));
            $this->wptAssertEquals(count($l), 2);
            $element->removeChild($t2);
            $this->wptAssertEquals(count($l), 1);
        }, 'getElementsByTagNameNS() should be a live collection');
    }
    public function testDocumentGetElementsByTagNameNS()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-getElementsByTagNameNS.html');
        $this->helperTestGetElementsByTagNameNS($this->doc, $this->doc->body);
    }
}

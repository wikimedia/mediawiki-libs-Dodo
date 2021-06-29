<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-getElementsByTagNameNS.html.
class DocumentGetElementsByTagNameNSTest extends WPTTestHarness
{
    public function testGetElementsByTagNameNS($context, $element)
    {
        $this->assertTest(function () use(&$context) {
            $this->assertFalseData($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'html') instanceof NodeList, 'NodeList');
            $this->assertTrueData($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'html') instanceof HTMLCollection, 'HTMLCollection');
            $firstCollection = $context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'html');
            $secondCollection = $context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'html');
            $this->assertTrueData($firstCollection !== $secondCollection || $firstCollection === $secondCollection, 'Caching is allowed.');
        });
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'body'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
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
            $this->assertArrayEqualsData($actual, $expected);
        }, "getElementsByTagNameNS('*', 'body')");
        $this->assertTest(function () use(&$context, &$element) {
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('', '*'), []);
            $t = $element->appendChild($this->doc->createElementNS('', 'body'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('', '*'), [$t]);
        }, 'Empty string namespace');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'body'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', 'body'), [$t]);
        }, 'body element in test namespace, no prefix');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'test:body'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', 'body'), [$t]);
        }, 'body element in test namespace, prefix');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'BODY'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', 'BODY'), [$t]);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', 'body'), []);
        }, 'BODY element in test namespace, no prefix');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('http://www.w3.org/1999/xhtml', 'abc'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'abc'), [$t]);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'ABC'), []);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', 'ABC'), []);
        }, 'abc element in html namespace');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('http://www.w3.org/1999/xhtml', 'ABC'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'abc'), []);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'ABC'), [$t]);
        }, 'ABC element in html namespace');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('http://www.w3.org/1999/xhtml', "AÇ"));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', "AÇ"), [$t]);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', "aÇ"), []);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', "aç"), []);
        }, "AÇ, case sensitivity");
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'test:BODY'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
                $element->removeChild($t);
            });
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', 'BODY'), [$t]);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('test', 'body'), []);
        }, 'BODY element in test namespace, prefix');
        $this->assertTest(function () use(&$element, &$context) {
            $t = $element->appendChild($this->doc->createElementNS('test', 'test:test'));
            $this->{$this}->addCleanup(function () use(&$element, &$t) {
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
            $this->assertArrayEqualsData($actual, $expected);
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
            $this->assertArrayEqualsData($actual, $expected);
        }, "getElementsByTagNameNS('*', '*')");
        $this->assertTest(function () use(&$context) {
            $this->assertArrayEqualsData($context->getElementsByTagNameNS('**', '*'), []);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS(null, '0'), []);
            $this->assertArrayEqualsData($context->getElementsByTagNameNS(null, 'div'), []);
        }, 'Empty lists');
        $this->assertTest(function () use(&$element, &$context) {
            $t1 = $element->appendChild($this->doc->createElementNS('test', 'abc'));
            $this->{$this}->addCleanup(function () use(&$element, &$t1) {
                $element->removeChild($t1);
            });
            $l = $context->getElementsByTagNameNS('test', 'abc');
            $this->assertTrueData($l instanceof HTMLCollection);
            $this->assertEqualsData(count($l), 1);
            $t2 = $element->appendChild($this->doc->createElementNS('test', 'abc'));
            $this->assertEqualsData(count($l), 2);
            $element->removeChild($t2);
            $this->assertEqualsData(count($l), 1);
        }, 'getElementsByTagNameNS() should be a live collection');
    }
    public function testDocumentGetElementsByTagNameNS()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-getElementsByTagNameNS.html');
        $this->testGetElementsByTagNameNS($this->doc, $this->getDocBody( $this->doc ));
    }
}

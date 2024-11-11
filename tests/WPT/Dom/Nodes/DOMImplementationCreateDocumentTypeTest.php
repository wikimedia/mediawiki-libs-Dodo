<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createDocumentType.html.
class DOMImplementationCreateDocumentTypeTest extends WPTTestHarness
{
    public function testDOMImplementationCreateDocumentType()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createDocumentType.html');
        $this->assertTest(function () {
            $tests = [['', '', '', 'INVALID_CHARACTER_ERR'], ['test:root', '1234', '', null], ['test:root', '1234', 'test', null], ['test:root', 'test', '', null], ['test:root', 'test', 'test', null], ['_:_', '', '', null], ['_:h0', '', '', null], ['_:test', '', '', null], ['_:_.', '', '', null], ['_:a-', '', '', null], ['l_:_', '', '', null], ['ns:_0', '', '', null], ['ns:a0', '', '', null], ['ns0:test', '', '', null], ['ns:EEE.', '', '', null], ['ns:_-', '', '', null], ['a.b:c', '', '', null], ['a-b:c.j', '', '', null], ['a-b:c', '', '', null], ['foo', '', '', null], ['1foo', '', '', 'INVALID_CHARACTER_ERR'], ['foo1', '', '', null], ['f1oo', '', '', null], ['@foo', '', '', 'INVALID_CHARACTER_ERR'], ['foo@', '', '', 'INVALID_CHARACTER_ERR'], ['f@oo', '', '', 'INVALID_CHARACTER_ERR'], ['edi:{', '', '', 'INVALID_CHARACTER_ERR'], ['edi:}', '', '', 'INVALID_CHARACTER_ERR'], ['edi:~', '', '', 'INVALID_CHARACTER_ERR'], ["edi:'", '', '', 'INVALID_CHARACTER_ERR'], ['edi:!', '', '', 'INVALID_CHARACTER_ERR'], ['edi:@', '', '', 'INVALID_CHARACTER_ERR'], ['edi:#', '', '', 'INVALID_CHARACTER_ERR'], ['edi:$', '', '', 'INVALID_CHARACTER_ERR'], ['edi:%', '', '', 'INVALID_CHARACTER_ERR'], ['edi:^', '', '', 'INVALID_CHARACTER_ERR'], ['edi:&', '', '', 'INVALID_CHARACTER_ERR'], ['edi:*', '', '', 'INVALID_CHARACTER_ERR'], ['edi:(', '', '', 'INVALID_CHARACTER_ERR'], ['edi:)', '', '', 'INVALID_CHARACTER_ERR'], ['edi:+', '', '', 'INVALID_CHARACTER_ERR'], ['edi:=', '', '', 'INVALID_CHARACTER_ERR'], ['edi:[', '', '', 'INVALID_CHARACTER_ERR'], ['edi:]', '', '', 'INVALID_CHARACTER_ERR'], ['edi:\\', '', '', 'INVALID_CHARACTER_ERR'], ['edi:/', '', '', 'INVALID_CHARACTER_ERR'], ['edi:;', '', '', 'INVALID_CHARACTER_ERR'], ['edi:`', '', '', 'INVALID_CHARACTER_ERR'], ['edi:<', '', '', 'INVALID_CHARACTER_ERR'], ['edi:>', '', '', 'INVALID_CHARACTER_ERR'], ['edi:,', '', '', 'INVALID_CHARACTER_ERR'], ['edi:a ', '', '', 'INVALID_CHARACTER_ERR'], ['edi:"', '', '', 'INVALID_CHARACTER_ERR'], ['{', '', '', 'INVALID_CHARACTER_ERR'], ['}', '', '', 'INVALID_CHARACTER_ERR'], ["'", '', '', 'INVALID_CHARACTER_ERR'], ['~', '', '', 'INVALID_CHARACTER_ERR'], ['`', '', '', 'INVALID_CHARACTER_ERR'], ['@', '', '', 'INVALID_CHARACTER_ERR'], ['#', '', '', 'INVALID_CHARACTER_ERR'], ['$', '', '', 'INVALID_CHARACTER_ERR'], ['%', '', '', 'INVALID_CHARACTER_ERR'], ['^', '', '', 'INVALID_CHARACTER_ERR'], ['&', '', '', 'INVALID_CHARACTER_ERR'], ['*', '', '', 'INVALID_CHARACTER_ERR'], ['(', '', '', 'INVALID_CHARACTER_ERR'], [')', '', '', 'INVALID_CHARACTER_ERR'], ['f:oo', '', '', null], [':foo', '', '', 'INVALID_CHARACTER_ERR'], ['foo:', '', '', 'INVALID_CHARACTER_ERR'], ['prefix::local', '', '', 'INVALID_CHARACTER_ERR'], ['foo', 'foo', '', null], ['foo', '', 'foo', null], ['foo', "f'oo", '', null], ['foo', '', "f'oo", null], ['foo', 'f"oo', '', null], ['foo', '', 'f"oo', null], ['foo', "f'o\"o", '', null], ['foo', '', "f'o\"o", null], ['foo', 'foo>', '', null], ['foo', '', 'foo>', null]];
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $doTest = function ($aDocument, $aQualifiedName, $aPublicId, $aSystemId) {
                $doctype = $aDocument->implementation->createDocumentType($aQualifiedName, $aPublicId, $aSystemId);
                $this->wptAssertEquals($doctype->name, $aQualifiedName, 'name');
                $this->wptAssertEquals($doctype->nodeName, $aQualifiedName, 'nodeName');
                $this->wptAssertEquals($doctype->publicId, $aPublicId, 'publicId');
                $this->wptAssertEquals($doctype->systemId, $aSystemId, 'systemId');
                $this->wptAssertEquals($doctype->ownerDocument, $aDocument, 'ownerDocument');
                $this->wptAssertEquals($doctype->nodeValue, null, 'nodeValue');
            };
            foreach ($tests as $t) {
                $qualifiedName = $t[0];
                $publicId = $t[1];
                $systemId = $t[2];
                $expected = $t[3];
                $this->assertTest(function () use (&$expected, &$qualifiedName, &$publicId, &$systemId, &$doTest, &$doc) {
                    if ($expected) {
                        $this->wptAssertThrowsDom($expected, function () use (&$qualifiedName, &$publicId, &$systemId) {
                            $this->doc->implementation->createDocumentType($qualifiedName, $publicId, $systemId);
                        });
                    } else {
                        $doTest($this->doc, $qualifiedName, $publicId, $systemId);
                        $doTest($doc, $qualifiedName, $publicId, $systemId);
                    }
                }, 'createDocumentType(' . $this->formatValue($qualifiedName) . ', ' . $this->formatValue($publicId) . ', ' . $this->formatValue($systemId) . ') should ' . ($expected ? 'throw ' . $expected : 'work'));
            }
        });
    }
}

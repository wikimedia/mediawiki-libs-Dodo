<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-hasFeature.html.
class DOMImplementationHasFeatureTest extends WPTTestHarness
{
    public function testDOMImplementationHasFeature()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-hasFeature.html');
        $this->assertTest(function () {
            $tests = [[], ['Core'], ['XML'], ['org.w3c.svg'], ['org.w3c.dom.svg'], ['http://www.w3.org/TR/SVG11/feature#Script'], ['Core', '1.0'], ['Core', '2.0'], ['Core', '3.0'], ['Core', '100.0'], ['XML', '1.0'], ['XML', '2.0'], ['XML', '3.0'], ['XML', '100.0'], ['Core', '1'], ['Core', '2'], ['Core', '3'], ['Core', '100'], ['XML', '1'], ['XML', '2'], ['XML', '3'], ['XML', '100'], ['Core', '1.1'], ['Core', '2.1'], ['Core', '3.1'], ['Core', '100.1'], ['XML', '1.1'], ['XML', '2.1'], ['XML', '3.1'], ['XML', '100.1'], ['Core', ''], ['XML', ''], ['core', ''], ['xml', ''], ['CoRe', ''], ['XmL', ''], [' Core', ''], [' XML', ''], ['Core ', ''], ['XML ', ''], ['Co re', ''], ['XM L', ''], ['aCore', ''], ['aXML', ''], ['Corea', ''], ['XMLa', ''], ['Coare', ''], ['XMaL', ''], ['Core', ' '], ['XML', ' '], ['Core', ' 1.0'], ['Core', ' 2.0'], ['Core', ' 3.0'], ['Core', ' 100.0'], ['XML', ' 1.0'], ['XML', ' 2.0'], ['XML', ' 3.0'], ['XML', ' 100.0'], ['Core', '1.0 '], ['Core', '2.0 '], ['Core', '3.0 '], ['Core', '100.0 '], ['XML', '1.0 '], ['XML', '2.0 '], ['XML', '3.0 '], ['XML', '100.0 '], ['Core', '1. 0'], ['Core', '2. 0'], ['Core', '3. 0'], ['Core', '100. 0'], ['XML', '1. 0'], ['XML', '2. 0'], ['XML', '3. 0'], ['XML', '100. 0'], ['Core', 'a1.0'], ['Core', 'a2.0'], ['Core', 'a3.0'], ['Core', 'a100.0'], ['XML', 'a1.0'], ['XML', 'a2.0'], ['XML', 'a3.0'], ['XML', 'a100.0'], ['Core', '1.0a'], ['Core', '2.0a'], ['Core', '3.0a'], ['Core', '100.0a'], ['XML', '1.0a'], ['XML', '2.0a'], ['XML', '3.0a'], ['XML', '100.0a'], ['Core', '1.a0'], ['Core', '2.a0'], ['Core', '3.a0'], ['Core', '100.a0'], ['XML', '1.a0'], ['XML', '2.a0'], ['XML', '3.a0'], ['XML', '100.a0'], ['Core', 1], ['Core', 2], ['Core', 3], ['Core', 100], ['XML', 1], ['XML', 2], ['XML', 3], ['XML', 100], ['Core', null], ['XML', null], ['core', null], ['xml', null], ['CoRe', null], ['XmL', null], [' Core', null], [' XML', null], ['Core ', null], ['XML ', null], ['Co re', null], ['XM L', null], ['aCore', null], ['aXML', null], ['Corea', null], ['XMLa', null], ['Coare', null], ['XMaL', null], ['Core', null], ['XML', null], ['This is filler text.', ''], [null, ''], [null, ''], ['org.w3c.svg', ''], ['org.w3c.svg', '1.0'], ['org.w3c.svg', '1.1'], ['org.w3c.dom.svg', ''], ['org.w3c.dom.svg', '1.0'], ['org.w3c.dom.svg', '1.1'], ['http://www.w3.org/TR/SVG11/feature#Script', '7.5']];
            foreach ($tests as $data) {
                $this->assertTest(function () {
                    $this->assertEqualsData(call_user_func_array([$this->doc->implementation, 'hasFeature'], $data), true);
                }, 'hasFeature(' . implode(', ', $this->arrayMap($data, $format_value)) . ')');
            }
        });
    }
}

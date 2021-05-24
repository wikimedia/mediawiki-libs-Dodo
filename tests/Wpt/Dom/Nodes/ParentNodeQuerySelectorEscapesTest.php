<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\IDLeDOM\Range;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-escapes.html.
class ParentNodeQuerySelectorEscapesTest extends WptTestHarness
{
    public function testMatched($id, $selector)
    {
        $this->assertTest(function () use(&$id, &$selector) {
            $container = $this->doc->createElement('div');
            $child = $this->doc->createElement('span');
            $child->id = $id;
            $container->appendChild($child);
            $this->assertEqualsData($container->querySelector($selector), $child);
        }, " should match with {json_encode( {$id} )}{json_encode( {$selector} )}");
    }
    public function testNeverMatched($id, $selector)
    {
        $this->assertTest(function () use(&$id, &$selector) {
            $container = $this->doc->createElement('div');
            $child = $this->doc->createElement('span');
            $child->id = $id;
            $container->appendChild($child);
            $this->assertEqualsData($container->querySelector($selector), null);
        }, " should never match with {json_encode( {$id} )}{json_encode( {$selector} )}");
    }
    public function testParentNodeQuerySelectorEscapes()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-escapes.html');
        // 4.3.7 from https://drafts.csswg.org/css-syntax/#consume-escaped-code-point
        $this->testMatched('nonescaped', '#nonescaped');
        // - escape hex digit
        $this->testMatched('0nextIsWhiteSpace', '#\\30 nextIsWhiteSpace');
        $this->testMatched('0nextIsNotHexLetters', '#\\30nextIsNotHexLetters');
        $this->testMatched('0connectHexMoreThan6Hex', '#\\000030connectHexMoreThan6Hex');
        $this->testMatched('0spaceMoreThan6Hex', '#\\000030 spaceMoreThan6Hex');
        // - hex digit special replacement
        // 1. zero points
        $this->testMatched("zero�", '#zero\\0');
        $this->testNeverMatched("zero\\u0000", '#zero\\0');
        $this->testMatched("zero�", '#zero\\000000');
        $this->testNeverMatched("zero\\u0000", '#zero\\000000');
        // 2. surrogate points
        $this->testMatched("�surrogateFirst", '#\\d83d surrogateFirst');
        $this->testNeverMatched("\\ud83dsurrogateFirst", '#\\d83d surrogateFirst');
        $this->testMatched("surrogateSecond�", '#surrogateSecond\\dd11');
        $this->testNeverMatched("surrogateSecond\\udd11", '#surrogateSecond\\dd11');
        $this->testMatched("surrogatePair��", '#surrogatePair\\d83d\\dd11');
        $this->testNeverMatched("surrogatePair🔑", '#surrogatePair\\d83d\\dd11');
        // 3. out of range points
        $this->testMatched("outOfRange�", '#outOfRange\\110000');
        $this->testMatched("outOfRange�", '#outOfRange\\110030');
        $this->testNeverMatched('outOfRange0', '#outOfRange\\110030');
        $this->testMatched("outOfRange�", '#outOfRange\\555555');
        $this->testMatched("outOfRange�", '#outOfRange\\ffffff');
        // - escape EOF
        $this->testNeverMatched('eof\\', '#eof\\');
        // - escape anythong else
        $this->testMatched('.comma', '#\\.comma');
        $this->testMatched('-minus', '#\\-minus');
        $this->testMatched('g', '#\\g');
        // non edge cases
        $this->testMatched('aBMPRegular', '#\\61 BMPRegular');
        $this->testMatched("🔑nonBMP", '#\\1f511 nonBMP');
        $this->testMatched('00continueEscapes', '#\\30\\30 continueEscapes');
        $this->testMatched('00continueEscapes', '#\\30 \\30 continueEscapes');
        $this->testMatched('continueEscapes00', '#continueEscapes\\30 \\30 ');
        $this->testMatched('continueEscapes00', '#continueEscapes\\30 \\30');
        $this->testMatched('continueEscapes00', '#continueEscapes\\30\\30 ');
        $this->testMatched('continueEscapes00', '#continueEscapes\\30\\30');
        // ident tests case from CSS tests of chromium source: https://goo.gl/3Cxdov
        $this->testMatched('hello', '#hel\\6Co');
        $this->testMatched('&B', '#\\26 B');
        $this->testMatched('hello', '#hel\\6C o');
        $this->testMatched('spaces', "#spac\\65\r\ns");
        $this->testMatched('spaces', "#sp\\61\tc\\65\fs");
        $this->testMatched("test힙", '#test\\D799');
        $this->testMatched("", '#\\E000');
        $this->testMatched('test', '#te\\s\\t');
        $this->testMatched("spaces in\tident", "#spaces\\ in\\\tident");
        $this->testMatched('.,:!', '#\\.\\,\\:\\!');
        $this->testMatched("null�", '#null\\0');
        $this->testMatched("null�", '#null\\0000');
        $this->testMatched("large�", '#large\\110000');
        $this->testMatched("large�", '#large\\23456a');
        $this->testMatched("surrogate�", '#surrogate\\D800');
        $this->testMatched("surrogate�", '#surrogate\\0DBAC');
        $this->testMatched("�surrogate", '#\\00DFFFsurrogate');
        $this->testMatched("􏿿", '#\\10fFfF');
        $this->testMatched("􏿿0", '#\\10fFfF0');
        $this->testMatched("􀀀00", '#\\10000000');
        $this->testMatched("eof�", '#eof\\');
        $this->testMatched('simple-ident', '#simple-ident');
        $this->testMatched('testing123', '#testing123');
        $this->testMatched('_underscore', '#_underscore');
        $this->testMatched('-text', '#-text');
        $this->testMatched('-m', '#-\\6d');
        $this->testMatched('--abc', '#--abc');
        $this->testMatched('--', '#--');
        $this->testMatched('--11', '#--11');
        $this->testMatched('---', '#---');
        $this->testMatched(" ", "# ");
        $this->testMatched(" ", "# ");
        $this->testMatched("ሴ", "#ሴ");
        $this->testMatched("𒍅", "#𒍅");
        $this->testMatched("�", "#\\u0000");
        $this->testMatched("ab�c", "#ab\\u0000c");
    }
}

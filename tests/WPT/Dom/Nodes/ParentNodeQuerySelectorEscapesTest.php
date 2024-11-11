<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-escapes.html.
class ParentNodeQuerySelectorEscapesTest extends WPTTestHarness
{
    public function helperTestMatched($id, $selector)
    {
        $this->assertTest(function () use (&$id, &$selector) {
            $container = $this->doc->createElement('div');
            $child = $this->doc->createElement('span');
            $child->id = $id;
            $container->appendChild($child);
            $this->wptAssertEquals($container->querySelector($selector), $child);
        }, " should match with {json_encode( {$id} )}{json_encode( {$selector} )}");
    }
    public function helperTestNeverMatched($id, $selector)
    {
        $this->assertTest(function () use (&$id, &$selector) {
            $container = $this->doc->createElement('div');
            $child = $this->doc->createElement('span');
            $child->id = $id;
            $container->appendChild($child);
            $this->wptAssertEquals($container->querySelector($selector), null);
        }, " should never match with {json_encode( {$id} )}{json_encode( {$selector} )}");
    }
    public function testParentNodeQuerySelectorEscapes()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelector-escapes.html');
        // 4.3.7 from https://drafts.csswg.org/css-syntax/#consume-escaped-code-point
        $this->helperTestMatched('nonescaped', '#nonescaped');
        // - escape hex digit
        $this->helperTestMatched('0nextIsWhiteSpace', '#\30 nextIsWhiteSpace');
        $this->helperTestMatched('0nextIsNotHexLetters', '#\30nextIsNotHexLetters');
        $this->helperTestMatched('0connectHexMoreThan6Hex', '#\000030connectHexMoreThan6Hex');
        $this->helperTestMatched('0spaceMoreThan6Hex', '#\000030 spaceMoreThan6Hex');
        // - hex digit special replacement
        // 1. zero points
        $this->helperTestMatched("zero�", '#zero\0');
        $this->helperTestNeverMatched("zero\\u0000", '#zero\0');
        $this->helperTestMatched("zero�", '#zero\000000');
        $this->helperTestNeverMatched("zero\\u0000", '#zero\000000');
        // 2. surrogate points
        $this->helperTestMatched("�surrogateFirst", '#\d83d surrogateFirst');
        $this->helperTestNeverMatched("\\ud83dsurrogateFirst", '#\d83d surrogateFirst');
        $this->helperTestMatched("surrogateSecond�", '#surrogateSecond\dd11');
        $this->helperTestNeverMatched("surrogateSecond\\udd11", '#surrogateSecond\dd11');
        $this->helperTestMatched("surrogatePair��", '#surrogatePair\d83d\dd11');
        $this->helperTestNeverMatched("surrogatePair🔑", '#surrogatePair\d83d\dd11');
        // 3. out of range points
        $this->helperTestMatched("outOfRange�", '#outOfRange\110000');
        $this->helperTestMatched("outOfRange�", '#outOfRange\110030');
        $this->helperTestNeverMatched('outOfRange0', '#outOfRange\110030');
        $this->helperTestMatched("outOfRange�", '#outOfRange\555555');
        $this->helperTestMatched("outOfRange�", '#outOfRange\ffffff');
        // - escape EOF
        $this->helperTestNeverMatched('eof\\', '#eof\\');
        // - escape anythong else
        $this->helperTestMatched('.comma', '#\.comma');
        $this->helperTestMatched('-minus', '#\-minus');
        $this->helperTestMatched('g', '#\g');
        // non edge cases
        $this->helperTestMatched('aBMPRegular', '#\61 BMPRegular');
        $this->helperTestMatched("🔑nonBMP", '#\1f511 nonBMP');
        $this->helperTestMatched('00continueEscapes', '#\30\30 continueEscapes');
        $this->helperTestMatched('00continueEscapes', '#\30 \30 continueEscapes');
        $this->helperTestMatched('continueEscapes00', '#continueEscapes\30 \30 ');
        $this->helperTestMatched('continueEscapes00', '#continueEscapes\30 \30');
        $this->helperTestMatched('continueEscapes00', '#continueEscapes\30\30 ');
        $this->helperTestMatched('continueEscapes00', '#continueEscapes\30\30');
        // ident tests case from CSS tests of chromium source: https://goo.gl/3Cxdov
        $this->helperTestMatched('hello', '#hel\6Co');
        $this->helperTestMatched('&B', '#\26 B');
        $this->helperTestMatched('hello', '#hel\6C o');
        $this->helperTestMatched('spaces', "#spac\\65\r\ns");
        $this->helperTestMatched('spaces', "#sp\\61\tc\\65\fs");
        $this->helperTestMatched("test힙", '#test\D799');
        $this->helperTestMatched("", '#\E000');
        $this->helperTestMatched('test', '#te\s\t');
        $this->helperTestMatched("spaces in\tident", "#spaces\\ in\\\tident");
        $this->helperTestMatched('.,:!', '#\.\,\:\!');
        $this->helperTestMatched("null�", '#null\0');
        $this->helperTestMatched("null�", '#null\0000');
        $this->helperTestMatched("large�", '#large\110000');
        $this->helperTestMatched("large�", '#large\23456a');
        $this->helperTestMatched("surrogate�", '#surrogate\D800');
        $this->helperTestMatched("surrogate�", '#surrogate\0DBAC');
        $this->helperTestMatched("�surrogate", '#\00DFFFsurrogate');
        $this->helperTestMatched("􏿿", '#\10fFfF');
        $this->helperTestMatched("􏿿0", '#\10fFfF0');
        $this->helperTestMatched("􀀀00", '#\10000000');
        $this->helperTestMatched("eof�", '#eof\\');
        $this->helperTestMatched('simple-ident', '#simple-ident');
        $this->helperTestMatched('testing123', '#testing123');
        $this->helperTestMatched('_underscore', '#_underscore');
        $this->helperTestMatched('-text', '#-text');
        $this->helperTestMatched('-m', '#-\6d');
        $this->helperTestMatched('--abc', '#--abc');
        $this->helperTestMatched('--', '#--');
        $this->helperTestMatched('--11', '#--11');
        $this->helperTestMatched('---', '#---');
        $this->helperTestMatched(" ", "# ");
        $this->helperTestMatched(" ", "# ");
        $this->helperTestMatched("ሴ", "#ሴ");
        $this->helperTestMatched("𒍅", "#𒍅");
        $this->helperTestMatched("�", "#\\u0000");
        $this->helperTestMatched("ab�c", "#ab\\u0000c");
    }
}

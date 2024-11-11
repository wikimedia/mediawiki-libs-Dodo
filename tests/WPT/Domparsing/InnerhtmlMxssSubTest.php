<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/innerhtml-mxss.sub.html.
class InnerhtmlMxssSubTest extends WPTTestHarness
{
    public function testInnerhtmlMxssSub()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/innerhtml-mxss.sub.html');
        $whitespaces = ['1680', '2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '200a', '2028', '205f', '3000'];
        for ($i = 0; $i < count($whitespaces); $i++) {
            $container = $this->doc->querySelector('a')->parentNode;
            $entity = "&#x;{$whitespaces[$i]}";
            $character = String::fromCharCode(intval($whitespaces[$i], 16));
            $url = encodeURIComponent($character);
            $container->innerHTML = "<a href=\"javascript:alert(1)\">Link</a>{$entity}";
            $a = $this->doc->querySelector('a');
            $this->assertTest(function ($_) use (&$container, &$character) {
                $this->wptAssertEquals($container->innerHTML, "<a href=\"javascript:alert(1)\">Link</a>{$character}");
            }, "innerHTML before setter: {$whitespaces[$i]}");
            $this->assertTest(function ($_) use (&$a, &$url) {
                $this->wptAssertEquals($a->href, "http://{{host}}:{{ports[http][0]}}/domparsing/javascript:alert(1){$url}");
            }, "href before setter: {$whitespaces[$i]}");
            $a->parentNode->innerHTML += 'foo';
            $a = $this->doc->querySelector('a');
            $this->assertTest(function ($_) use (&$container, &$character) {
                $this->wptAssertEquals($container->innerHTML, "<a href=\"javascript:alert(1)\">Link</a>foo{$character}");
            }, "innerHTML after setter: {$whitespaces[$i]}");
            $this->assertTest(function ($_) use (&$a, &$url) {
                $this->wptAssertEquals($a->href, "http://{{host}}:{{ports[http][0]}}/domparsing/javascript:alert(1){$url}");
            }, "href after setter: {$whitespaces[$i]}");
        }
    }
}

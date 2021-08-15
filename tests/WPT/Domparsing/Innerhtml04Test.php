<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/innerhtml-04.html.
class Innerhtml04Test extends WPTTestHarness
{
    public function helperTestIsChild($p, $c)
    {
        $this->wptAssertEquals($p->firstChild, $c);
        $this->wptAssertEquals($c->parentNode, $p);
    }
    public function testInnerhtml04()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/innerhtml-04.html');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $b = $p->appendChild($this->doc->createElement('b'));
            $t = $b->appendChild($this->doc->createTextNode('foo'));
            $this->helperTestIsChild($p, $b);
            $this->helperTestIsChild($b, $t);
            $this->wptAssertEquals($t->data, 'foo');
            $p->innerHTML = '';
            $this->helperTestIsChild($b, $t);
            $this->wptAssertEquals($t->data, 'foo');
        }, 'innerHTML should leave the removed children alone.');
    }
}

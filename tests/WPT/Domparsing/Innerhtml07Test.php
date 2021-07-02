<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/innerhtml-07.html.
class Innerhtml07Test extends WPTTestHarness
{
    public function testInnerhtml07()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/innerhtml-07.html');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $p->innerHTML = null;
            $this->wptAssertEquals($p->innerHTML, '');
            $this->wptAssertEquals($p->textContent, '');
        }, 'innerHTML and string conversion: null.');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $p->innerHTML = null;
            $this->wptAssertEquals($p->innerHTML, NULL);
            $this->wptAssertEquals($p->textContent, NULL);
        }, 'innerHTML and string conversion: undefined.');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $p->innerHTML = 42;
            $this->wptAssertEquals($p->innerHTML, '42');
            $this->wptAssertEquals($p->textContent, '42');
        }, 'innerHTML and string conversion: number.');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $p->innerHTML = ['toString' => function () {
                return 'pass';
            }, 'valueOf' => function () {
                return 'fail';
            }];
            $this->wptAssertEquals($p->innerHTML, 'pass');
            $this->wptAssertEquals($p->textContent, 'pass');
        }, 'innerHTML and string conversion: toString.');
        $this->assertTest(function () {
            $p = $this->doc->createElement('p');
            $p->innerHTML = ['toString' => null, 'valueOf' => function () {
                return 'pass';
            }];
            $this->wptAssertEquals($p->innerHTML, 'pass');
            $this->wptAssertEquals($p->textContent, 'pass');
        }, 'innerHTML and string conversion: valueOf.');
    }
}

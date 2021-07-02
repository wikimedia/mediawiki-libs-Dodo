<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/outerhtml-02.html.
class Outerhtml02Test extends WPTTestHarness
{
    public function testOuterhtml02()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/outerhtml-02.html');
        $this->assertTest(function () {
            $div = $this->doc->createElement('div');
            $p = $div->appendChild($this->doc->createElement('p'));
            $p->outerHTML = null;
            $this->wptAssertEquals($div->innerHTML, '');
            $this->wptAssertEquals($div->textContent, '');
        }, 'outerHTML and string conversion: null.');
        $this->assertTest(function () {
            $div = $this->doc->createElement('div');
            $p = $div->appendChild($this->doc->createElement('p'));
            $p->outerHTML = null;
            $this->wptAssertEquals($div->innerHTML, NULL);
            $this->wptAssertEquals($div->textContent, NULL);
        }, 'outerHTML and string conversion: undefined.');
        $this->assertTest(function () {
            $div = $this->doc->createElement('div');
            $p = $div->appendChild($this->doc->createElement('p'));
            $p->outerHTML = 42;
            $this->wptAssertEquals($div->innerHTML, '42');
            $this->wptAssertEquals($div->textContent, '42');
        }, 'outerHTML and string conversion: number.');
        $this->assertTest(function () {
            $div = $this->doc->createElement('div');
            $p = $div->appendChild($this->doc->createElement('p'));
            $p->outerHTML = ['toString' => function () {
                return 'pass';
            }, 'valueOf' => function () {
                return 'fail';
            }];
            $this->wptAssertEquals($div->innerHTML, 'pass');
            $this->wptAssertEquals($div->textContent, 'pass');
        }, 'outerHTML and string conversion: toString.');
        $this->assertTest(function () {
            $div = $this->doc->createElement('div');
            $p = $div->appendChild($this->doc->createElement('p'));
            $p->outerHTML = ['toString' => null, 'valueOf' => function () {
                return 'pass';
            }];
            $this->wptAssertEquals($div->innerHTML, 'pass');
            $this->wptAssertEquals($div->textContent, 'pass');
        }, 'outerHTML and string conversion: valueOf.');
    }
}

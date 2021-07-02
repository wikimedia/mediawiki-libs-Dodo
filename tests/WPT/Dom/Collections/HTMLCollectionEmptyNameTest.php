<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Collections;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-empty-name.html.
class HTMLCollectionEmptyNameTest extends WPTTestHarness
{
    public function testHTMLCollectionEmptyName()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-empty-name.html');
        $this->assertTest(function () {
            $c = $this->doc->getElementsByTagName('*');
            $this->wptAssertFalse(isset($c['']), 'Empty string should not be in the collection.');
            $this->wptAssertEquals($c[''], null, 'Named getter should return undefined for empty string.');
            $this->wptAssertEquals($c->namedItem(''), null, 'namedItem should return null for empty string.');
        }, 'Empty string as a name for Document.getElementsByTagName');
        $this->assertTest(function () {
            $div = $this->doc->getElementById('test');
            $c = $div->getElementsByTagName('*');
            $this->wptAssertFalse(isset($c['']), 'Empty string should not be in the collection.');
            $this->wptAssertEquals($c[''], null, 'Named getter should return undefined for empty string.');
            $this->wptAssertEquals($c->namedItem(''), null, 'namedItem should return null for empty string.');
        }, 'Empty string as a name for Element.getElementsByTagName');
        $this->assertTest(function () {
            $c = $this->doc->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'a');
            $this->wptAssertFalse(isset($c['']), 'Empty string should not be in the collection.');
            $this->wptAssertEquals($c[''], null, 'Named getter should return undefined for empty string.');
            $this->wptAssertEquals($c->namedItem(''), null, 'namedItem should return null for empty string.');
        }, 'Empty string as a name for Document.getElementsByTagNameNS');
        $this->assertTest(function () {
            $div = $this->doc->getElementById('test');
            $c = $div->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'a');
            $this->wptAssertFalse(isset($c['']), 'Empty string should not be in the collection.');
            $this->wptAssertEquals($c[''], null, 'Named getter should return undefined for empty string.');
            $this->wptAssertEquals($c->namedItem(''), null, 'namedItem should return null for empty string.');
        }, 'Empty string as a name for Element.getElementsByTagNameNS');
        $this->assertTest(function () {
            $c = $this->doc->getElementsByClassName('a');
            $this->wptAssertFalse(isset($c['']), 'Empty string should not be in the collection.');
            $this->wptAssertEquals($c[''], null, 'Named getter should return undefined for empty string.');
            $this->wptAssertEquals($c->namedItem(''), null, 'namedItem should return null for empty string.');
        }, 'Empty string as a name for Document.getElementsByClassName');
        $this->assertTest(function () {
            $div = $this->doc->getElementById('test');
            $c = $div->getElementsByClassName('a');
            $this->wptAssertFalse(isset($c['']), 'Empty string should not be in the collection.');
            $this->wptAssertEquals($c[''], null, 'Named getter should return undefined for empty string.');
            $this->wptAssertEquals($c->namedItem(''), null, 'namedItem should return null for empty string.');
        }, 'Empty string as a name for Element.getElementsByClassName');
        $this->assertTest(function () {
            $div = $this->doc->getElementById('test');
            $c = $div->children;
            $this->wptAssertFalse(isset($c['']), 'Empty string should not be in the collection.');
            $this->wptAssertEquals($c[''], null, 'Named getter should return undefined for empty string.');
            $this->wptAssertEquals($c->namedItem(''), null, 'namedItem should return null for empty string.');
        }, 'Empty string as a name for Element.children');
    }
}

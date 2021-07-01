<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/aria-attribute-reflection.tentative.html.
class AriaAttributeReflectionTentativeTest extends WPTTestHarness
{
    public function testAriaAttributeReflectionTentative()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/aria-attribute-reflection.tentative.html');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('role');
            $this->wptAssertEquals($element->role, 'button');
            $element->role = 'checkbox';
            $this->wptAssertEquals($element->getAttribute('role'), 'checkbox');
        }, 'role attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('atomic');
            $this->wptAssertEquals($element->ariaAtomic, 'true');
            $element->ariaAtomic = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-atomic'), 'false');
        }, 'aria-atomic attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('autocomplete');
            $this->wptAssertEquals($element->ariaAutoComplete, 'list');
            $element->ariaAutoComplete = 'inline';
            $this->wptAssertEquals($element->getAttribute('aria-autocomplete'), 'inline');
        }, 'aria-autocomplete attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('busy');
            $this->wptAssertEquals($element->ariaBusy, 'true');
            $element->ariaBusy = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-busy'), 'false');
        }, 'aria-busy attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('checked');
            $this->wptAssertEquals($element->ariaChecked, 'mixed');
            $element->ariaChecked = 'true';
            $this->wptAssertEquals($element->getAttribute('aria-checked'), 'true');
        }, 'aria-checked attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('colcount');
            $this->wptAssertEquals($element->ariaColCount, '5');
            $element->ariaColCount = '6';
            $this->wptAssertEquals($element->getAttribute('aria-colcount'), '6');
        }, 'aria-colcount attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('colindex');
            $this->wptAssertEquals($element->ariaColIndex, '1');
            $element->ariaColIndex = '2';
            $this->wptAssertEquals($element->getAttribute('aria-colindex'), '2');
        }, 'aria-colindex attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('colspan');
            $this->wptAssertEquals($element->ariaColSpan, '2');
            $element->ariaColSpan = '3';
            $this->wptAssertEquals($element->getAttribute('aria-colspan'), '3');
        }, 'aria-colspan attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('current');
            $this->wptAssertEquals($element->ariaCurrent, 'page');
            $element->ariaCurrent = 'step';
            $this->wptAssertEquals($element->getAttribute('aria-current'), 'step');
        }, 'aria-current attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('description');
            $this->wptAssertEquals($element->ariaDescription, 'cold as ice');
            $element->ariaDescription = 'hot as fire';
            $this->wptAssertEquals($element->getAttribute('aria-description'), 'hot as fire');
        }, 'aria-description attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('disabled');
            $this->wptAssertEquals($element->ariaDisabled, 'true');
            $element->ariaDisabled = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-disabled'), 'false');
        }, 'aria-disabled attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('expanded');
            $this->wptAssertEquals($element->ariaExpanded, 'true');
            $element->ariaExpanded = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-expanded'), 'false');
        }, 'aria-expanded attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('haspopup');
            $this->wptAssertEquals($element->ariaHasPopup, 'menu');
            $element->ariaHasPopup = 'listbox';
            $this->wptAssertEquals($element->getAttribute('aria-haspopup'), 'listbox');
        }, 'aria-haspopup attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('hidden');
            $this->wptAssertEquals($element->ariaHidden, 'true');
            $element->ariaHidden = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-hidden'), 'false');
        }, 'aria-hidden attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('keyshortcuts');
            $this->wptAssertEquals($element->ariaKeyShortcuts, 'x');
            $element->ariaKeyShortcuts = 'y';
            $this->wptAssertEquals($element->getAttribute('aria-keyshortcuts'), 'y');
        }, 'aria-keyshortcuts attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('label');
            $this->wptAssertEquals($element->ariaLabel, 'x');
            $element->ariaLabel = 'y';
            $this->wptAssertEquals($element->getAttribute('aria-label'), 'y');
        }, 'aria-label attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('level');
            $this->wptAssertEquals($element->ariaLevel, '1');
            $element->ariaLevel = '2';
            $this->wptAssertEquals($element->getAttribute('aria-level'), '2');
        }, 'aria-level attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('live');
            $this->wptAssertEquals($element->ariaLive, 'polite');
            $element->ariaLive = 'assertive';
            $this->wptAssertEquals($element->getAttribute('aria-live'), 'assertive');
        }, 'aria-live attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('modal');
            $this->wptAssertEquals($element->ariaModal, 'true');
            $element->ariaModal = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-modal'), 'false');
        }, 'aria-modal attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('multiline');
            $this->wptAssertEquals($element->ariaMultiLine, 'true');
            $element->ariaMultiLine = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-multiline'), 'false');
        }, 'aria-multiline attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('multiselectable');
            $this->wptAssertEquals($element->ariaMultiSelectable, 'true');
            $element->ariaMultiSelectable = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-multiselectable'), 'false');
        }, 'aria-multiselectable attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('orientation');
            $this->wptAssertEquals($element->ariaOrientation, 'vertical');
            $element->ariaOrientation = 'horizontal';
            $this->wptAssertEquals($element->getAttribute('aria-orientation'), 'horizontal');
        }, 'aria-orientation attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('placeholder');
            $this->wptAssertEquals($element->ariaPlaceholder, 'x');
            $element->ariaPlaceholder = 'y';
            $this->wptAssertEquals($element->getAttribute('aria-placeholder'), 'y');
        }, 'aria-placeholder attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('posinset');
            $this->wptAssertEquals($element->ariaPosInSet, '10');
            $element->ariaPosInSet = '11';
            $this->wptAssertEquals($element->getAttribute('aria-posinset'), '11');
        }, 'aria-posinset attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('pressed');
            $this->wptAssertEquals($element->ariaPressed, 'true');
            $element->ariaPressed = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-pressed'), 'false');
        }, 'aria-pressed attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('readonly');
            $this->wptAssertEquals($element->ariaReadOnly, 'true');
            $element->ariaReadOnly = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-readonly'), 'false');
        }, 'aria-readonly attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('relevant');
            $this->wptAssertEquals($element->ariaRelevant, 'text');
            $element->ariaRelevant = 'removals';
            $this->wptAssertEquals($element->getAttribute('aria-relevant'), 'removals');
        }, 'aria-relevant attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('required');
            $this->wptAssertEquals($element->ariaRequired, 'true');
            $element->ariaRequired = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-required'), 'false');
        }, 'aria-required attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('roledescription');
            $this->wptAssertEquals($element->ariaRoleDescription, 'x');
            $element->ariaRoleDescription = 'y';
            $this->wptAssertEquals($element->getAttribute('aria-roledescription'), 'y');
        }, 'aria-roledescription attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('rowcount');
            $this->wptAssertEquals($element->ariaRowCount, '10');
            $element->ariaRowCount = '11';
            $this->wptAssertEquals($element->getAttribute('aria-rowcount'), '11');
        }, 'aria-rowcount attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('rowindex');
            $this->wptAssertEquals($element->ariaRowIndex, '1');
            $element->ariaRowIndex = '2';
            $this->wptAssertEquals($element->getAttribute('aria-rowindex'), '2');
        }, 'aria-rowindex attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('rowspan');
            $this->wptAssertEquals($element->ariaRowSpan, '2');
            $element->ariaRowSpan = '3';
            $this->wptAssertEquals($element->getAttribute('aria-rowspan'), '3');
        }, 'aria-rowspan attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('selected');
            $this->wptAssertEquals($element->ariaSelected, 'true');
            $element->ariaSelected = 'false';
            $this->wptAssertEquals($element->getAttribute('aria-selected'), 'false');
        }, 'aria-selected attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('setsize');
            $this->wptAssertEquals($element->ariaSetSize, '10');
            $element->ariaSetSize = '11';
            $this->wptAssertEquals($element->getAttribute('aria-setsize'), '11');
        }, 'aria-setsize attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('sort');
            $this->wptAssertEquals($element->ariaSort, 'descending');
            $element->ariaSort = 'ascending';
            $this->wptAssertEquals($element->getAttribute('aria-sort'), 'ascending');
        }, 'aria-sort attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('valuemax');
            $this->wptAssertEquals($element->ariaValueMax, '99');
            $element->ariaValueMax = '100';
            $this->wptAssertEquals($element->getAttribute('aria-valuemax'), '100');
        }, 'aria-valuemax attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('valuemin');
            $this->wptAssertEquals($element->ariaValueMin, '3');
            $element->ariaValueMin = '2';
            $this->wptAssertEquals($element->getAttribute('aria-valuemin'), '2');
        }, 'aria-valuemin attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('valuenow');
            $this->wptAssertEquals($element->ariaValueNow, '50');
            $element->ariaValueNow = '51';
            $this->wptAssertEquals($element->getAttribute('aria-valuenow'), '51');
        }, 'aria-valuenow attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('valuetext');
            $this->wptAssertEquals($element->ariaValueText, '50%');
            $element->ariaValueText = '51%';
            $this->wptAssertEquals($element->getAttribute('aria-valuetext'), '51%');
        }, 'aria-valuetext attribute reflects.');
    }
}

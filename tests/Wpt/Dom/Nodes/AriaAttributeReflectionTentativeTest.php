<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/aria-attribute-reflection.tentative.html.
class AriaAttributeReflectionTentativeTest extends WptTestHarness
{
    public function testAriaAttributeReflectionTentative()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/aria-attribute-reflection.tentative.html');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('role');
            $this->assertEqualsData($element->role, 'button');
            $element->role = 'checkbox';
            $this->assertEqualsData($element->getAttribute('role'), 'checkbox');
        }, 'role attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('atomic');
            $this->assertEqualsData($element->ariaAtomic, 'true');
            $element->ariaAtomic = 'false';
            $this->assertEqualsData($element->getAttribute('aria-atomic'), 'false');
        }, 'aria-atomic attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('autocomplete');
            $this->assertEqualsData($element->ariaAutoComplete, 'list');
            $element->ariaAutoComplete = 'inline';
            $this->assertEqualsData($element->getAttribute('aria-autocomplete'), 'inline');
        }, 'aria-autocomplete attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('busy');
            $this->assertEqualsData($element->ariaBusy, 'true');
            $element->ariaBusy = 'false';
            $this->assertEqualsData($element->getAttribute('aria-busy'), 'false');
        }, 'aria-busy attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('checked');
            $this->assertEqualsData($element->ariaChecked, 'mixed');
            $element->ariaChecked = 'true';
            $this->assertEqualsData($element->getAttribute('aria-checked'), 'true');
        }, 'aria-checked attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('colcount');
            $this->assertEqualsData($element->ariaColCount, '5');
            $element->ariaColCount = '6';
            $this->assertEqualsData($element->getAttribute('aria-colcount'), '6');
        }, 'aria-colcount attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('colindex');
            $this->assertEqualsData($element->ariaColIndex, '1');
            $element->ariaColIndex = '2';
            $this->assertEqualsData($element->getAttribute('aria-colindex'), '2');
        }, 'aria-colindex attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('colspan');
            $this->assertEqualsData($element->ariaColSpan, '2');
            $element->ariaColSpan = '3';
            $this->assertEqualsData($element->getAttribute('aria-colspan'), '3');
        }, 'aria-colspan attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('current');
            $this->assertEqualsData($element->ariaCurrent, 'page');
            $element->ariaCurrent = 'step';
            $this->assertEqualsData($element->getAttribute('aria-current'), 'step');
        }, 'aria-current attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('description');
            $this->assertEqualsData($element->ariaDescription, 'cold as ice');
            $element->ariaDescription = 'hot as fire';
            $this->assertEqualsData($element->getAttribute('aria-description'), 'hot as fire');
        }, 'aria-description attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('disabled');
            $this->assertEqualsData($element->ariaDisabled, 'true');
            $element->ariaDisabled = 'false';
            $this->assertEqualsData($element->getAttribute('aria-disabled'), 'false');
        }, 'aria-disabled attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('expanded');
            $this->assertEqualsData($element->ariaExpanded, 'true');
            $element->ariaExpanded = 'false';
            $this->assertEqualsData($element->getAttribute('aria-expanded'), 'false');
        }, 'aria-expanded attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('haspopup');
            $this->assertEqualsData($element->ariaHasPopup, 'menu');
            $element->ariaHasPopup = 'listbox';
            $this->assertEqualsData($element->getAttribute('aria-haspopup'), 'listbox');
        }, 'aria-haspopup attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('hidden');
            $this->assertEqualsData($element->ariaHidden, 'true');
            $element->ariaHidden = 'false';
            $this->assertEqualsData($element->getAttribute('aria-hidden'), 'false');
        }, 'aria-hidden attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('keyshortcuts');
            $this->assertEqualsData($element->ariaKeyShortcuts, 'x');
            $element->ariaKeyShortcuts = 'y';
            $this->assertEqualsData($element->getAttribute('aria-keyshortcuts'), 'y');
        }, 'aria-keyshortcuts attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('label');
            $this->assertEqualsData($element->ariaLabel, 'x');
            $element->ariaLabel = 'y';
            $this->assertEqualsData($element->getAttribute('aria-label'), 'y');
        }, 'aria-label attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('level');
            $this->assertEqualsData($element->ariaLevel, '1');
            $element->ariaLevel = '2';
            $this->assertEqualsData($element->getAttribute('aria-level'), '2');
        }, 'aria-level attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('live');
            $this->assertEqualsData($element->ariaLive, 'polite');
            $element->ariaLive = 'assertive';
            $this->assertEqualsData($element->getAttribute('aria-live'), 'assertive');
        }, 'aria-live attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('modal');
            $this->assertEqualsData($element->ariaModal, 'true');
            $element->ariaModal = 'false';
            $this->assertEqualsData($element->getAttribute('aria-modal'), 'false');
        }, 'aria-modal attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('multiline');
            $this->assertEqualsData($element->ariaMultiLine, 'true');
            $element->ariaMultiLine = 'false';
            $this->assertEqualsData($element->getAttribute('aria-multiline'), 'false');
        }, 'aria-multiline attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('multiselectable');
            $this->assertEqualsData($element->ariaMultiSelectable, 'true');
            $element->ariaMultiSelectable = 'false';
            $this->assertEqualsData($element->getAttribute('aria-multiselectable'), 'false');
        }, 'aria-multiselectable attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('orientation');
            $this->assertEqualsData($element->ariaOrientation, 'vertical');
            $element->ariaOrientation = 'horizontal';
            $this->assertEqualsData($element->getAttribute('aria-orientation'), 'horizontal');
        }, 'aria-orientation attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('placeholder');
            $this->assertEqualsData($element->ariaPlaceholder, 'x');
            $element->ariaPlaceholder = 'y';
            $this->assertEqualsData($element->getAttribute('aria-placeholder'), 'y');
        }, 'aria-placeholder attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('posinset');
            $this->assertEqualsData($element->ariaPosInSet, '10');
            $element->ariaPosInSet = '11';
            $this->assertEqualsData($element->getAttribute('aria-posinset'), '11');
        }, 'aria-posinset attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('pressed');
            $this->assertEqualsData($element->ariaPressed, 'true');
            $element->ariaPressed = 'false';
            $this->assertEqualsData($element->getAttribute('aria-pressed'), 'false');
        }, 'aria-pressed attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('readonly');
            $this->assertEqualsData($element->ariaReadOnly, 'true');
            $element->ariaReadOnly = 'false';
            $this->assertEqualsData($element->getAttribute('aria-readonly'), 'false');
        }, 'aria-readonly attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('relevant');
            $this->assertEqualsData($element->ariaRelevant, 'text');
            $element->ariaRelevant = 'removals';
            $this->assertEqualsData($element->getAttribute('aria-relevant'), 'removals');
        }, 'aria-relevant attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('required');
            $this->assertEqualsData($element->ariaRequired, 'true');
            $element->ariaRequired = 'false';
            $this->assertEqualsData($element->getAttribute('aria-required'), 'false');
        }, 'aria-required attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('roledescription');
            $this->assertEqualsData($element->ariaRoleDescription, 'x');
            $element->ariaRoleDescription = 'y';
            $this->assertEqualsData($element->getAttribute('aria-roledescription'), 'y');
        }, 'aria-roledescription attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('rowcount');
            $this->assertEqualsData($element->ariaRowCount, '10');
            $element->ariaRowCount = '11';
            $this->assertEqualsData($element->getAttribute('aria-rowcount'), '11');
        }, 'aria-rowcount attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('rowindex');
            $this->assertEqualsData($element->ariaRowIndex, '1');
            $element->ariaRowIndex = '2';
            $this->assertEqualsData($element->getAttribute('aria-rowindex'), '2');
        }, 'aria-rowindex attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('rowspan');
            $this->assertEqualsData($element->ariaRowSpan, '2');
            $element->ariaRowSpan = '3';
            $this->assertEqualsData($element->getAttribute('aria-rowspan'), '3');
        }, 'aria-rowspan attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('selected');
            $this->assertEqualsData($element->ariaSelected, 'true');
            $element->ariaSelected = 'false';
            $this->assertEqualsData($element->getAttribute('aria-selected'), 'false');
        }, 'aria-selected attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('setsize');
            $this->assertEqualsData($element->ariaSetSize, '10');
            $element->ariaSetSize = '11';
            $this->assertEqualsData($element->getAttribute('aria-setsize'), '11');
        }, 'aria-setsize attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('sort');
            $this->assertEqualsData($element->ariaSort, 'descending');
            $element->ariaSort = 'ascending';
            $this->assertEqualsData($element->getAttribute('aria-sort'), 'ascending');
        }, 'aria-sort attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('valuemax');
            $this->assertEqualsData($element->ariaValueMax, '99');
            $element->ariaValueMax = '100';
            $this->assertEqualsData($element->getAttribute('aria-valuemax'), '100');
        }, 'aria-valuemax attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('valuemin');
            $this->assertEqualsData($element->ariaValueMin, '3');
            $element->ariaValueMin = '2';
            $this->assertEqualsData($element->getAttribute('aria-valuemin'), '2');
        }, 'aria-valuemin attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('valuenow');
            $this->assertEqualsData($element->ariaValueNow, '50');
            $element->ariaValueNow = '51';
            $this->assertEqualsData($element->getAttribute('aria-valuenow'), '51');
        }, 'aria-valuenow attribute reflects.');
        $this->assertTest(function ($t) {
            $element = $this->doc->getElementById('valuetext');
            $this->assertEqualsData($element->ariaValueText, '50%');
            $element->ariaValueText = '51%';
            $this->assertEqualsData($element->getAttribute('aria-valuetext'), '51%');
        }, 'aria-valuetext attribute reflects.');
    }
}

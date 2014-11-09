﻿/*
 Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.themes.add('default', (function () {
    return{build:function (a, b) {
        var c = a.name, d = a.element, e = a.elementMode;
        if (!d || e == CKEDITOR.ELEMENT_MODE_NONE)return;
        if (e == CKEDITOR.ELEMENT_MODE_REPLACE)d.hide();
        var f = a.fire('themeSpace', {space:'top', html:''}).html, g = a.fire('themeSpace', {space:'contents', html:''}).html, h = a.fireOnce('themeSpace', {space:'bottom', html:''}).html, i = g && a.config.height, j = a.config.tabIndex || a.element.getAttribute('tabindex') || 0;
        if (!g)i = 'auto'; else if (!isNaN(i))i += 'px';
        var k = '', l = a.config.width;
        if (l) {
            if (!isNaN(l))l += 'px';
            k += 'width: ' + l + ';';
        }
        var m = CKEDITOR.dom.element.createFromHtml(['<span id="cke_', c, '" onmousedown="return false;" class="', a.skinClass, '" dir="', a.lang.dir, '" title="', CKEDITOR.env.gecko ? ' ' : '', '" lang="', a.langCode, '" tabindex="' + j + '"' + (k ? ' style="' + k + '"' : '') + '>' + '<span class="', CKEDITOR.env.cssClass, '"><span class="cke_wrapper cke_', a.lang.dir, '"><table class="cke_editor" border="0" cellspacing="0" cellpadding="0"><tbody><tr', f ? '' : ' style="display:none"', '><td id="cke_top_', c, '" class="cke_top">', f, '</td></tr><tr', g ? '' : ' style="display:none"', '><td id="cke_contents_', c, '" class="cke_contents" style="height:', i, '">', g, '</td></tr><tr', h ? '' : ' style="display:none"', '><td id="cke_bottom_', c, '" class="cke_bottom">', h, '</td></tr></tbody></table><style>.', a.skinClass, '{visibility:hidden;}</style></span></span></span>'].join(''));
        m.getChild([0, 0, 0, 0, 0]).unselectable();
        m.getChild([0, 0, 0, 0, 2]).unselectable();
        if (e == CKEDITOR.ELEMENT_MODE_REPLACE)m.insertAfter(d); else d.append(m);
        a.container = m;
        a.fireOnce('themeLoaded');
        a.fireOnce('uiReady');
    }, buildDialog:function (a) {
        var b = CKEDITOR.tools.getNextNumber(), c = CKEDITOR.dom.element.createFromHtml(['<div id="cke_' + a.name.replace('.', '\\.') + '_dialog" class="cke_skin_', a.skinName, '" dir="', a.lang.dir, '" lang="', a.langCode, '"><div class="cke_dialog', ' ' + CKEDITOR.env.cssClass, ' cke_', a.lang.dir, '" style="position:absolute"><div class="%body"><div id="%title#" class="%title"></div><div id="%close_button#" class="%close_button"><span>X</span></div><div id="%tabs#" class="%tabs"></div><div id="%contents#" class="%contents"></div><div id="%footer#" class="%footer"></div></div><div id="%tl#" class="%tl"></div><div id="%tc#" class="%tc"></div><div id="%tr#" class="%tr"></div><div id="%ml#" class="%ml"></div><div id="%mr#" class="%mr"></div><div id="%bl#" class="%bl"></div><div id="%bc#" class="%bc"></div><div id="%br#" class="%br"></div></div>', CKEDITOR.env.ie ? '' : '<style>.cke_dialog{visibility:hidden;}</style>', '</div>'].join('').replace(/#/g, '_' + b).replace(/%/g, 'cke_dialog_')), d = c.getChild([0, 0]);
        d.getChild(0).unselectable();
        d.getChild(1).unselectable();
        return{element:c, parts:{dialog:c.getChild(0), title:d.getChild(0), close:d.getChild(1), tabs:d.getChild(2), contents:d.getChild(3), footer:d.getChild(4)}};
    }, destroy:function (a) {
        var b = a.container;
        if (CKEDITOR.env.ie) {
            b.setStyle('display', 'none');
            var c = document.body.createTextRange();
            c.moveToElementText(b.$);
            try {
                c.select();
            } catch (d) {
            }
        }
        if (b)b.remove();
        if (a.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE) {
            a.element.show();
            delete a.element;
        }
    }};
})());
CKEDITOR.editor.prototype.getThemeSpace = function (a) {
    var b = 'cke_' + a, c = this._[b] || (this._[b] = CKEDITOR.document.getById(b + '_' + this.name));
    return c;
};
CKEDITOR.editor.prototype.resize = function (a, b, c, d) {
    var e = /^\d+$/;
    if (e.test(a))a += 'px';
    var f = CKEDITOR.document.getById('cke_contents_' + this.name), g = d ? f.getAscendant('table').getParent() : f.getAscendant('table').getParent().getParent().getParent();
    CKEDITOR.env.webkit && g.setStyle('display', 'none');
    g.setStyle('width', a);
    if (CKEDITOR.env.webkit) {
        g.$.offsetWidth;
        g.setStyle('display', '');
    }
    var h = c ? 0 : (g.$.offsetHeight || 0) - (f.$.clientHeight || 0);
    f.setStyle('height', Math.max(b - h, 0) + 'px');
    this.fire('resize');
};
CKEDITOR.editor.prototype.getResizable = function () {
    return this.container.getChild([0, 0]);
};

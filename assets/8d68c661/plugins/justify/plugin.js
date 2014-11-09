﻿/*
 Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

(function () {
    var a = /(-moz-|-webkit-|start|auto)/i;

    function b(e, f) {
        var g = f.block || f.blockLimit;
        if (!g || g.getName() == 'body')return CKEDITOR.TRISTATE_OFF;
        var h = g.getComputedStyle('text-align').replace(a, '');
        if (!h && this.isDefaultAlign || h == this.value)return CKEDITOR.TRISTATE_ON;
        return CKEDITOR.TRISTATE_OFF;
    }

    ;
    function c(e) {
        var f = e.editor.getCommand(this.name);
        f.state = b.call(this, e.editor, e.data.path);
        f.fire('state');
    }

    ;
    function d(e, f, g) {
        var j = this;
        j.name = f;
        j.value = g;
        var h = e.config.contentsLangDirection;
        j.isDefaultAlign = g == 'left' && h == 'ltr' || g == 'right' && h == 'rtl';
        var i = e.config.justifyClasses;
        if (i) {
            switch (g) {
                case 'left':
                    j.cssClassName = i[0];
                    break;
                case 'center':
                    j.cssClassName = i[1];
                    break;
                case 'right':
                    j.cssClassName = i[2];
                    break;
                case 'justify':
                    j.cssClassName = i[3];
                    break;
            }
            j.cssClassRegex = new RegExp('(?:^|\\s+)(?:' + i.join('|') + ')(?=$|\\s)');
        }
    }

    ;
    d.prototype = {exec:function (e) {
        var n = this;
        var f = e.getSelection();
        if (!f)return;
        var g = f.createBookmarks(), h = f.getRanges(), i = n.cssClassName, j, k;
        for (var l = h.length - 1; l >= 0; l--) {
            j = h[l].createIterator();
            while (k = j.getNextParagraph()) {
                k.removeAttribute('align');
                if (i) {
                    var m = k.$.className = CKEDITOR.tools.ltrim(k.$.className.replace(n.cssClassRegex, ''));
                    if (n.state == CKEDITOR.TRISTATE_OFF && !n.isDefaultAlign)k.addClass(i); else if (!m)k.removeAttribute('class');
                } else if (n.state == CKEDITOR.TRISTATE_OFF && !n.isDefaultAlign)k.setStyle('text-align', n.value); else k.removeStyle('text-align');
            }
        }
        e.focus();
        e.forceNextSelectionCheck();
        f.selectBookmarks(g);
    }};
    CKEDITOR.plugins.add('justify', {init:function (e) {
        var f = new d(e, 'justifyleft', 'left'), g = new d(e, 'justifycenter', 'center'), h = new d(e, 'justifyright', 'right'), i = new d(e, 'justifyblock', 'justify');
        e.addCommand('justifyleft', f);
        e.addCommand('justifycenter', g);
        e.addCommand('justifyright', h);
        e.addCommand('justifyblock', i);
        e.ui.addButton('JustifyLeft', {label:e.lang.justify.left, command:'justifyleft'});
        e.ui.addButton('JustifyCenter', {label:e.lang.justify.center, command:'justifycenter'});
        e.ui.addButton('JustifyRight', {label:e.lang.justify.right, command:'justifyright'});
        e.ui.addButton('JustifyBlock', {label:e.lang.justify.block, command:'justifyblock'});
        e.on('selectionChange', CKEDITOR.tools.bind(c, f));
        e.on('selectionChange', CKEDITOR.tools.bind(c, h));
        e.on('selectionChange', CKEDITOR.tools.bind(c, g));
        e.on('selectionChange', CKEDITOR.tools.bind(c, i));
    }, requires:['domiterator']});
})();
CKEDITOR.tools.extend(CKEDITOR.config, {justifyClasses:null});

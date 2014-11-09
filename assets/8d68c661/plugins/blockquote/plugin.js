﻿/*
 Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

(function () {
    function a(e, f) {
        var g = f.block || f.blockLimit;
        if (!g || g.getName() == 'body')return CKEDITOR.TRISTATE_OFF;
        if (g.getAscendant('blockquote', true))return CKEDITOR.TRISTATE_ON;
        return CKEDITOR.TRISTATE_OFF;
    }

    ;
    function b(e) {
        var f = e.editor, g = f.getCommand('blockquote');
        g.state = a(f, e.data.path);
        g.fire('state');
    }

    ;
    function c(e) {
        for (var f = 0, g = e.getChildCount(), h; f < g && (h = e.getChild(f)); f++)if (h.type == CKEDITOR.NODE_ELEMENT && h.isBlockBoundary())return false;
        return true;
    }

    ;
    var d = {exec:function (e) {
        var f = e.getCommand('blockquote').state, g = e.getSelection(), h = g && g.getRanges()[0];
        if (!h)return;
        var i = g.createBookmarks();
        if (CKEDITOR.env.ie) {
            var j = i[0].startNode, k = i[0].endNode, l;
            if (j && j.getParent().getName() == 'blockquote') {
                l = j;
                while (l = l.getNext())if (l.type == CKEDITOR.NODE_ELEMENT && l.isBlockBoundary()) {
                    j.move(l, true);
                    break;
                }
            }
            if (k && k.getParent().getName() == 'blockquote') {
                l = k;
                while (l = l.getPrevious())if (l.type == CKEDITOR.NODE_ELEMENT && l.isBlockBoundary()) {
                    k.move(l);
                    break;
                }
            }
        }
        var m = h.createIterator(), n;
        if (f == CKEDITOR.TRISTATE_OFF) {
            var o = [];
            while (n = m.getNextParagraph())o.push(n);
            if (o.length < 1) {
                var p = e.document.createElement(e.config.enterMode == CKEDITOR.ENTER_P ? 'p' : 'div'), q = i.shift();
                h.insertNode(p);
                p.append(new CKEDITOR.dom.text('﻿', e.document));
                h.moveToBookmark(q);
                h.selectNodeContents(p);
                h.collapse(true);
                q = h.createBookmark();
                o.push(p);
                i.unshift(q);
            }
            var r = o[0].getParent(), s = [];
            for (var t = 0; t < o.length; t++) {
                n = o[t];
                r = r.getCommonAncestor(n.getParent());
            }
            var u = {table:1, tbody:1, tr:1, ol:1, ul:1};
            while (u[r.getName()])r = r.getParent();
            var v = null;
            while (o.length > 0) {
                n = o.shift();
                while (!n.getParent().equals(r))n = n.getParent();
                if (!n.equals(v))s.push(n);
                v = n;
            }
            while (s.length > 0) {
                n = s.shift();
                if (n.getName() == 'blockquote') {
                    var w = new CKEDITOR.dom.documentFragment(e.document);
                    while (n.getFirst()) {
                        w.append(n.getFirst().remove());
                        o.push(w.getLast());
                    }
                    w.replace(n);
                } else o.push(n);
            }
            var x = e.document.createElement('blockquote');
            x.insertBefore(o[0]);
            while (o.length > 0) {
                n = o.shift();
                x.append(n);
            }
        } else if (f == CKEDITOR.TRISTATE_ON) {
            var y = [], z = {};
            while (n = m.getNextParagraph()) {
                var A = null, B = null;
                while (n.getParent()) {
                    if (n.getParent().getName() == 'blockquote') {
                        A = n.getParent();
                        B = n;
                        break;
                    }
                    n = n.getParent();
                }
                if (A && B && !B.getCustomData('blockquote_moveout')) {
                    y.push(B);
                    CKEDITOR.dom.element.setMarker(z, B, 'blockquote_moveout', true);
                }
            }
            CKEDITOR.dom.element.clearAllMarkers(z);
            var C = [], D = [];
            z = {};
            while (y.length > 0) {
                var E = y.shift();
                x = E.getParent();
                if (!E.getPrevious())E.remove().insertBefore(x); else if (!E.getNext())E.remove().insertAfter(x); else {
                    E.breakParent(E.getParent());
                    D.push(E.getNext());
                }
                if (!x.getCustomData('blockquote_processed')) {
                    D.push(x);
                    CKEDITOR.dom.element.setMarker(z, x, 'blockquote_processed', true);
                }
                C.push(E);
            }
            CKEDITOR.dom.element.clearAllMarkers(z);
            for (t = D.length - 1; t >= 0; t--) {
                x = D[t];
                if (c(x))x.remove();
            }
            if (e.config.enterMode == CKEDITOR.ENTER_BR) {
                var F = true;
                while (C.length) {
                    E = C.shift();
                    if (E.getName() == 'div') {
                        w = new CKEDITOR.dom.documentFragment(e.document);
                        var G = F && E.getPrevious() && !(E.getPrevious().type == CKEDITOR.NODE_ELEMENT && E.getPrevious().isBlockBoundary());
                        if (G)w.append(e.document.createElement('br'));
                        var H = E.getNext() && !(E.getNext().type == CKEDITOR.NODE_ELEMENT && E.getNext().isBlockBoundary());
                        while (E.getFirst())E.getFirst().remove().appendTo(w);
                        if (H)w.append(e.document.createElement('br'));
                        w.replace(E);
                        F = false;
                    }
                }
            }
        }
        g.selectBookmarks(i);
        e.focus();
    }};
    CKEDITOR.plugins.add('blockquote', {init:function (e) {
        e.addCommand('blockquote', d);
        e.ui.addButton('Blockquote', {label:e.lang.blockquote, command:'blockquote'});
        e.on('selectionChange', b);
    }, requires:['domiterator']});
})();

﻿/*
 Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

(function () {
    var a = {ol:1, ul:1}, b = /^[\n\r\t ]*$/;
    CKEDITOR.plugins.list = {listToArray:function (i, j, k, l, m) {
        if (!a[i.getName()])return[];
        if (!l)l = 0;
        if (!k)k = [];
        for (var n = 0, o = i.getChildCount(); n < o; n++) {
            var p = i.getChild(n);
            if (p.$.nodeName.toLowerCase() != 'li')continue;
            var q = {parent:i, indent:l, contents:[]};
            if (!m) {
                q.grandparent = i.getParent();
                if (q.grandparent && q.grandparent.$.nodeName.toLowerCase() == 'li')q.grandparent = q.grandparent.getParent();
            } else q.grandparent = m;
            if (j)CKEDITOR.dom.element.setMarker(j, p, 'listarray_index', k.length);
            k.push(q);
            for (var r = 0, s = p.getChildCount(); r < s; r++) {
                var t = p.getChild(r);
                if (t.type == CKEDITOR.NODE_ELEMENT && a[t.getName()])CKEDITOR.plugins.list.listToArray(t, j, k, l + 1, q.grandparent); else q.contents.push(t);
            }
        }
        return k;
    }, arrayToList:function (i, j, k, l) {
        if (!k)k = 0;
        if (!i || i.length < k + 1)return null;
        var m = i[k].parent.getDocument(), n = new CKEDITOR.dom.documentFragment(m), o = null, p = k, q = Math.max(i[k].indent, 0), r = null, s = l == CKEDITOR.ENTER_P ? 'p' : 'div';
        for (; ;) {
            var t = i[p];
            if (t.indent == q) {
                if (!o || i[p].parent.getName() != o.getName()) {
                    o = i[p].parent.clone(false, true);
                    n.append(o);
                }
                r = o.append(m.createElement('li'));
                for (var u = 0; u < t.contents.length; u++)r.append(t.contents[u].clone(true, true));
                p++;
            } else if (t.indent == Math.max(q, 0) + 1) {
                var v = CKEDITOR.plugins.list.arrayToList(i, null, p, l);
                r.append(v.listNode);
                p = v.nextIndex;
            } else if (t.indent == -1 && !k && t.grandparent) {
                r;
                if (a[t.grandparent.getName()])r = m.createElement('li'); else if (l != CKEDITOR.ENTER_BR && t.grandparent.getName() != 'td')r = m.createElement(s); else r = new CKEDITOR.dom.documentFragment(m);
                for (u = 0; u < t.contents.length; u++)r.append(t.contents[u].clone(true, true));
                if (r.type == CKEDITOR.NODE_DOCUMENT_FRAGMENT && p != i.length - 1) {
                    if (r.getLast() && r.getLast().type == CKEDITOR.NODE_ELEMENT && r.getLast().getAttribute('type') == '_moz')r.getLast().remove();
                    r.appendBogus();
                }
                if (r.type == CKEDITOR.NODE_ELEMENT && r.getName() == s && r.$.firstChild) {
                    r.trim();
                    var w = r.getFirst();
                    if (w.type == CKEDITOR.NODE_ELEMENT && w.isBlockBoundary()) {
                        var x = new CKEDITOR.dom.documentFragment(m);
                        r.moveChildren(x);
                        r = x;
                    }
                }
                var y = r.$.nodeName.toLowerCase();
                if (!CKEDITOR.env.ie && (y == 'div' || y == 'p'))r.appendBogus();
                n.append(r);
                o = null;
                p++;
            } else return null;
            if (i.length <= p || Math.max(i[p].indent, 0) < q)break;
        }
        if (j) {
            var z = n.getFirst();
            while (z) {
                if (z.type == CKEDITOR.NODE_ELEMENT)CKEDITOR.dom.element.clearMarkers(j, z);
                z = z.getNextSourceNode();
            }
        }
        return{listNode:n, nextIndex:p};
    }};
    function c(i, j) {
        i.getCommand(this.name).setState(j);
    }

    ;
    function d(i) {
        var j = i.data.path, k = j.blockLimit, l = j.elements, m;
        for (var n = 0; n < l.length && (m = l[n]) && (!m.equals(k));
             n++)if (a[l[n].getName()])return c.call(this, i.editor, this.type == l[n].getName() ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF);
        return c.call(this, i.editor, CKEDITOR.TRISTATE_OFF);
    }

    ;
    function e(i, j, k, l) {
        var m = CKEDITOR.plugins.list.listToArray(j.root, k), n = [];
        for (var o = 0; o < j.contents.length; o++) {
            var p = j.contents[o];
            p = p.getAscendant('li', true);
            if (!p || p.getCustomData('list_item_processed'))continue;
            n.push(p);
            CKEDITOR.dom.element.setMarker(k, p, 'list_item_processed', true);
        }
        var q = j.root.getDocument().createElement(this.type);
        for (o = 0; o < n.length; o++) {
            var r = n[o].getCustomData('listarray_index');
            m[r].parent = q;
        }
        var s = CKEDITOR.plugins.list.arrayToList(m, k, null, i.config.enterMode), t, u = s.listNode.getChildCount();
        for (o = 0; o < u && (t = s.listNode.getChild(o)); o++)if (t.getName() == this.type)l.push(t);
        s.listNode.replace(j.root);
    }

    ;
    function f(i, j, k) {
        var l = j.contents, m = j.root.getDocument(), n = [];
        if (l.length == 1 && l[0].equals(j.root)) {
            var o = m.createElement('div');
            l[0].moveChildren && l[0].moveChildren(o);
            l[0].append(o);
            l[0] = o;
        }
        var p = j.contents[0].getParent();
        for (var q = 0; q < l.length; q++)p = p.getCommonAncestor(l[q].getParent());
        for (q = 0; q < l.length; q++) {
            var r = l[q], s;
            while (s = r.getParent()) {
                if (s.equals(p)) {
                    n.push(r);
                    break;
                }
                r = s;
            }
        }
        if (n.length < 1)return;
        var t = n[n.length - 1].getNext(), u = m.createElement(this.type);
        k.push(u);
        while (n.length) {
            var v = n.shift(), w = m.createElement('li');
            v.moveChildren(w);
            v.remove();
            w.appendTo(u);
            if (!CKEDITOR.env.ie)w.appendBogus();
        }
        if (t)u.insertBefore(t); else u.appendTo(p);
    }

    ;
    function g(i, j, k) {
        var l = CKEDITOR.plugins.list.listToArray(j.root, k), m = [];
        for (var n = 0; n < j.contents.length; n++) {
            var o = j.contents[n];
            o = o.getAscendant('li', true);
            if (!o || o.getCustomData('list_item_processed'))continue;
            m.push(o);
            CKEDITOR.dom.element.setMarker(k, o, 'list_item_processed', true);
        }
        var p = null;
        for (n = 0; n < m.length; n++) {
            var q = m[n].getCustomData('listarray_index');
            l[q].indent = -1;
            p = q;
        }
        for (n = p + 1; n < l.length; n++)if (l[n].indent > l[n - 1].indent + 1) {
            var r = l[n - 1].indent + 1 - l[n].indent, s = l[n].indent;
            while (l[n] && l[n].indent >= s) {
                l[n].indent += r;
                n++;
            }
            n--;
        }
        var t = CKEDITOR.plugins.list.arrayToList(l, k, null, i.config.enterMode), u = t.listNode, v, w;

        function x(z) {
            if ((v = u[z ? 'getFirst' : 'getLast']()) && (!(v.is && v.isBlockBoundary()) && (w = j.root[z ? 'getPrevious' : 'getNext'](CKEDITOR.dom.walker.whitespaces(true))) && (!(w.is && w.isBlockBoundary({br:1})))))i.document.createElement('br')[z ? 'insertBefore' : 'insertAfter'](v);
        }

        ;
        x(true);
        x();
        var y = j.root.getParent();
        u.replace(j.root);
    }

    ;
    function h(i, j) {
        this.name = i;
        this.type = j;
    }

    ;
    h.prototype = {exec:function (i) {
        i.focus();
        var j = i.document, k = i.getSelection(), l = k && k.getRanges();
        if (!l || l.length < 1)return;
        if (this.state == CKEDITOR.TRISTATE_OFF) {
            var m = j.getBody();
            m.trim();
            if (!m.getFirst()) {
                var n = j.createElement(i.config.enterMode == CKEDITOR.ENTER_P ? 'p' : i.config.enterMode == CKEDITOR.ENTER_DIV ? 'div' : 'br');
                n.appendTo(m);
                l = [new CKEDITOR.dom.range(j)];
                if (n.is('br')) {
                    l[0].setStartBefore(n);
                    l[0].setEndAfter(n);
                } else l[0].selectNodeContents(n);
                k.selectRanges(l);
            } else {
                var o = l.length == 1 && l[0], p = o && o.getEnclosedNode();
                if (p && p.is && this.type == p.getName())c.call(this, i, CKEDITOR.TRISTATE_ON);
            }
        }
        var q = k.createBookmarks(true), r = [], s = {};
        while (l.length > 0) {
            o = l.shift();
            var t = o.getBoundaryNodes(), u = t.startNode, v = t.endNode;
            if (u.type == CKEDITOR.NODE_ELEMENT && u.getName() == 'td')o.setStartAt(t.startNode, CKEDITOR.POSITION_AFTER_START);
            if (v.type == CKEDITOR.NODE_ELEMENT && v.getName() == 'td')o.setEndAt(t.endNode, CKEDITOR.POSITION_BEFORE_END);
            var w = o.createIterator(), x;
            w.forceBrBreak = this.state == CKEDITOR.TRISTATE_OFF;
            while (x = w.getNextParagraph()) {
                var y = new CKEDITOR.dom.elementPath(x), z = null, A = false, B = y.blockLimit, C;
                for (var D = 0; D < y.elements.length && (C = y.elements[D]) && (!C.equals(B)); D++)if (a[C.getName()]) {
                    B.removeCustomData('list_group_object');
                    var E = C.getCustomData('list_group_object');
                    if (E)E.contents.push(x); else {
                        E = {root:C, contents:[x]};
                        r.push(E);
                        CKEDITOR.dom.element.setMarker(s, C, 'list_group_object', E);
                    }
                    A = true;
                    break;
                }
                if (A)continue;
                var F = B;
                if (F.getCustomData('list_group_object'))F.getCustomData('list_group_object').contents.push(x); else {
                    E = {root:F, contents:[x]};
                    CKEDITOR.dom.element.setMarker(s, F, 'list_group_object', E);
                    r.push(E);
                }
            }
        }
        var G = [];
        while (r.length > 0) {
            E = r.shift();
            if (this.state == CKEDITOR.TRISTATE_OFF) {
                if (a[E.root.getName()])e.call(this, i, E, s, G); else f.call(this, i, E, G);
            } else if (this.state == CKEDITOR.TRISTATE_ON && a[E.root.getName()])g.call(this, i, E, s);
        }
        for (D = 0; D < G.length; D++) {
            z = G[D];
            var H, I = this;
            (H = function (J) {
                var K = z[J ? 'getPrevious' : 'getNext'](CKEDITOR.dom.walker.whitespaces(true));
                if (K && K.getName && K.getName() == I.type) {
                    K.remove();
                    K.moveChildren(z, J ? true : false);
                }
            })();
            H(true);
        }
        CKEDITOR.dom.element.clearAllMarkers(s);
        k.selectBookmarks(q);
        i.focus();
    }};
    CKEDITOR.plugins.add('list', {init:function (i) {
        var j = new h('numberedlist', 'ol'), k = new h('bulletedlist', 'ul');
        i.addCommand('numberedlist', j);
        i.addCommand('bulletedlist', k);
        i.ui.addButton('NumberedList', {label:i.lang.numberedlist, command:'numberedlist'});
        i.ui.addButton('BulletedList', {label:i.lang.bulletedlist, command:'bulletedlist'});
        i.on('selectionChange', CKEDITOR.tools.bind(d, j));
        i.on('selectionChange', CKEDITOR.tools.bind(d, k));
    }, requires:['domiterator']});
})();

﻿/*
 Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

(function () {
    var a = {table:1, pre:1}, b = /\s*<(p|div|address|h\d|center)[^>]*>\s*(?:<br[^>]*>|&nbsp;|&#160;)\s*(:?<\/\1>)?\s*$/gi;

    function c(f) {
        var k = this;
        if (k.mode == 'wysiwyg') {
            k.focus();
            var g = k.getSelection(), h = f.data;
            if (k.dataProcessor)h = k.dataProcessor.toHtml(h);
            if (CKEDITOR.env.ie) {
                var i = g.isLocked;
                if (i)g.unlock();
                var j = g.getNative();
                if (j.type == 'Control')j.clear();
                j.createRange().pasteHTML(h);
                if (i)k.getSelection().lock();
            } else k.document.$.execCommand('inserthtml', false, h);
        }
    }

    ;
    function d(f) {
        if (this.mode == 'wysiwyg') {
            this.focus();
            this.fire('saveSnapshot');
            var g = f.data, h = g.getName(), i = CKEDITOR.dtd.$block[h], j = this.getSelection(), k = j.getRanges(), l = j.isLocked;
            if (l)j.unlock();
            var m, n, o, p;
            for (var q = k.length - 1; q >= 0; q--) {
                m = k[q];
                m.deleteContents();
                n = !q && g || g.clone(true);
                var r, s;
                if (this.config.enterMode != CKEDITOR.ENTER_BR && i)while ((r = m.getCommonAncestor(false, true)) && ((s = CKEDITOR.dtd[r.getName()]) && (!(s && s[h]))))m.splitBlock();
                m.insertNode(n);
                if (!o)o = n;
            }
            m.moveToPosition(o, CKEDITOR.POSITION_AFTER_END);
            var t = o.getNextSourceNode(true);
            if (t && t.type == CKEDITOR.NODE_ELEMENT)m.moveToElementEditStart(t);
            j.selectRanges([m]);
            if (l)this.getSelection().lock();
            CKEDITOR.tools.setTimeout(function () {
                this.fire('saveSnapshot');
            }, 0, this);
        }
    }

    ;
    function e(f) {
        var g = f.editor, h = f.data.path, i = h.blockLimit, j = f.data.selection, k = j.getRanges()[0], l = g.document.getBody(), m = g.config.enterMode;
        if (m != CKEDITOR.ENTER_BR && k.collapsed && i.getName() == 'body' && !h.block) {
            var n = j.createBookmarks(), o = k.fixBlock(true, g.config.enterMode == CKEDITOR.ENTER_DIV ? 'div' : 'p');
            if (CKEDITOR.env.ie) {
                var p = o.getElementsByTag('br'), q;
                for (var r = 0; r < p.count(); r++)if ((q = p.getItem(r)) && (q.hasAttribute('_cke_bogus')))q.remove();
            }
            j.selectBookmarks(n);
            var s = o.getChildren(), t = s.count(), u, v = CKEDITOR.dom.walker.whitespaces(true), w = o.getPrevious(v), x = o.getNext(v), y;
            if (w && w.getName && !(w.getName() in a))y = w; else if (x && x.getName && !(x.getName() in a))y = x;
            if ((!t || (u = s.getItem(0)) && (u.is && u.is('br'))) && (y && k.moveToElementEditStart(y))) {
                o.remove();
                k.select();
            }
        }
        var z = l.getLast(CKEDITOR.dom.walker.whitespaces(true));
        if (z && z.getName && z.getName() in a) {
            var A = g.document.createElement(CKEDITOR.env.ie && m != CKEDITOR.ENTER_BR ? '<br _cke_bogus="true" />' : 'br');
            l.append(A);
        }
    }

    ;
    CKEDITOR.plugins.add('wysiwygarea', {requires:['editingblock'], init:function (f) {
        var g = f.config.enterMode != CKEDITOR.ENTER_BR ? f.config.enterMode == CKEDITOR.ENTER_DIV ? 'div' : 'p' : false;
        f.on('editingBlockReady', function () {
            var h, i, j, k, l, m, n, o = CKEDITOR.env.isCustomDomain(), p = function () {
                if (j)j.remove();
                if (i)i.remove();
                m = 0;
                var s = 'void( ' + (CKEDITOR.env.gecko ? 'setTimeout' : '') + '( function(){' + 'document.open();' + (CKEDITOR.env.ie && o ? 'document.domain="' + document.domain + '";' : '') + 'document.write( window.parent[ "_cke_htmlToLoad_' + f.name + '" ] );' + 'document.close();' + 'window.parent[ "_cke_htmlToLoad_' + f.name + '" ] = null;' + '}' + (CKEDITOR.env.gecko ? ', 0 )' : ')()') + ' )';
                if (CKEDITOR.env.opera)s = 'void(0);';
                j = CKEDITOR.dom.element.createFromHtml('<iframe style="width:100%;height:100%" frameBorder="0" tabIndex="-1" allowTransparency="true" src="javascript:' + encodeURIComponent(s) + '"' + '></iframe>');
                var t = f.lang.editorTitle.replace('%1', f.name);
                if (CKEDITOR.env.gecko) {
                    j.on('load', function (u) {
                        u.removeListener();
                        r(j.$.contentWindow);
                    });
                    h.setAttributes({role:'region', title:t});
                    j.setAttributes({role:'region', title:' '});
                } else if (CKEDITOR.env.webkit) {
                    j.setAttribute('title', t);
                    j.setAttribute('name', t);
                } else if (CKEDITOR.env.ie) {
                    i = CKEDITOR.dom.element.createFromHtml('<fieldset style="height:100%' + (CKEDITOR.env.ie && CKEDITOR.env.quirks ? ';position:relative' : '') + '">' + '<legend style="display:block;width:0;height:0;overflow:hidden;' + (CKEDITOR.env.ie && CKEDITOR.env.quirks ? 'position:absolute' : '') + '">' + CKEDITOR.tools.htmlEncode(t) + '</legend>' + '</fieldset>', CKEDITOR.document);
                    j.appendTo(i);
                    i.appendTo(h);
                }
                if (!CKEDITOR.env.ie)h.append(j);
            }, q = '<script id="cke_actscrpt" type="text/javascript">window.onload = function(){window.parent.CKEDITOR._["contentDomReady' + f.name + '"]( window );' + '}' + '</script>', r = function (s) {
                if (m)return;
                m = 1;
                var t = s.document, u = t.body, v = t.getElementById('cke_actscrpt');
                v.parentNode.removeChild(v);
                delete CKEDITOR._['contentDomReady' + f.name];
                u.spellcheck = !f.config.disableNativeSpellChecker;
                if (CKEDITOR.env.ie) {
                    u.hideFocus = true;
                    u.disabled = true;
                    u.contentEditable = true;
                    u.removeAttribute('disabled');
                } else t.designMode = 'on';
                try {
                    t.execCommand('enableObjectResizing', false, !f.config.disableObjectResizing);
                } catch (B) {
                }
                try {
                    t.execCommand('enableInlineTableEditing', false, !f.config.disableNativeTableHandles);
                } catch (C) {
                }
                s = f.window = new CKEDITOR.dom.window(s);
                t = f.document = new CKEDITOR.dom.document(t);
                var w = t.getBody().getFirst();
                if (CKEDITOR.env.gecko && w && w.is && w.is('br') && w.hasAttribute('_moz_editor_bogus_node')) {
                    var x = t.$.createEvent('KeyEvents');
                    x.initKeyEvent('keypress', true, true, s.$, false, false, false, false, 0, 32);
                    t.$.dispatchEvent(x);
                    var y = t.getBody().getFirst();
                    if (f.config.enterMode == CKEDITOR.ENTER_BR)t.createElement('br', {attributes:{_moz_dirty:''}}).replace(y); else y.remove();
                }
                if (!(CKEDITOR.env.ie || CKEDITOR.env.opera))t.on('mousedown', function (D) {
                    var E = D.data.getTarget();
                    if (E.is('img', 'hr', 'input', 'textarea', 'select'))f.getSelection().selectElement(E);
                });
                if (CKEDITOR.env.webkit) {
                    t.on('click', function (D) {
                        if (D.data.getTarget().is('input', 'select'))D.data.preventDefault();
                    });
                    t.on('mouseup', function (D) {
                        if (D.data.getTarget().is('input', 'textarea'))D.data.preventDefault();
                    });
                }
                var z = CKEDITOR.env.ie || CKEDITOR.env.safari ? s : t;
                z.on('blur', function () {
                    f.focusManager.blur();
                });
                z.on('focus', function () {
                    f.focusManager.focus();
                });
                var A = f.keystrokeHandler;
                if (A)A.attach(t);
                if (f.contextMenu)f.contextMenu.addTarget(t);
                setTimeout(function () {
                    f.fire('contentDom');
                    if (n) {
                        f.mode = 'wysiwyg';
                        f.fire('mode');
                        n = false;
                    }
                    k = false;
                    if (l) {
                        f.focus();
                        l = false;
                    }
                    if (CKEDITOR.env.ie)setTimeout(function () {
                        if (f.document) {
                            var D = f.document.$.body;
                            D.runtimeStyle.marginBottom = '0px';
                            D.runtimeStyle.marginBottom = '';
                        }
                    }, 1000);
                }, 0);
            };
            f.addMode('wysiwyg', {load:function (s, t, u) {
                h = s;
                if (CKEDITOR.env.ie && CKEDITOR.env.quirks)s.setStyle('position', 'relative');
                f.mayBeDirty = true;
                n = true;
                if (u)this.loadSnapshotData(t); else this.loadData(t);
            }, loadData:function (s) {
                k = true;
                if (f.dataProcessor)s = f.dataProcessor.toHtml(s, g);
                s = f.config.docType + '<html dir="' + f.config.contentsLangDirection + '">' + '<head>' + '<link href="' + f.config.contentsCss + '" type="text/css" rel="stylesheet" _fcktemp="true"/>' + '<style type="text/css" _fcktemp="true">' + f._.styles.join('\n') + '</style>' + '</head>' + '<body>' + s + '</body>' + '</html>' + q;
                window['_cke_htmlToLoad_' + f.name] = s;
                CKEDITOR._['contentDomReady' + f.name] = r;
                p();
                if (CKEDITOR.env.opera) {
                    var t = j.$.contentWindow.document;
                    t.open();
                    t.write(s);
                    t.close();
                }
            }, getData:function () {
                var s = j.getFrameDocument().getBody().getHtml();
                if (f.dataProcessor)s = f.dataProcessor.toDataFormat(s, g);
                if (f.config.ignoreEmptyParagraph)s = s.replace(b, '');
                return s;
            }, getSnapshotData:function () {
                return j.getFrameDocument().getBody().getHtml();
            }, loadSnapshotData:function (s) {
                j.getFrameDocument().getBody().setHtml(s);
            }, unload:function (s) {
                f.window = f.document = j = h = l = null;
                f.fire('contentDomUnload');
            }, focus:function () {
                if (k)l = true; else if (f.window) {
                    f.window.focus();
                    f.selectionChange();
                }
            }});
            f.on('insertHtml', c, null, null, 20);
            f.on('insertElement', d, null, null, 20);
            f.on('selectionChange', e, null, null, 1);
        });
    }});
})();
CKEDITOR.config.disableObjectResizing = false;
CKEDITOR.config.disableNativeTableHandles = true;
CKEDITOR.config.disableNativeSpellChecker = true;
CKEDITOR.config.ignoreEmptyParagraph = true;

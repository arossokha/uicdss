﻿/*
 Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.dialog.add('cellProperties', function (a) {
    var b = a.lang.table, c = b.cell, d = a.lang.common, e = CKEDITOR.dialog.validate, f = /^(\d+(?:\.\d+)?)(px|%)$/, g = /^(\d+(?:\.\d+)?)px$/, h = CKEDITOR.tools.bind;

    function i() {
        return{type:'html', html:'&nbsp;'};
    }

    ;
    return{title:c.title, minWidth:480, minHeight:140, contents:[
        {id:'info', label:c.title, accessKey:'I', elements:[
            {type:'hbox', widths:['45%', '10%', '45%'], children:[
                {type:'vbox', padding:0, children:[
                    {type:'hbox', widths:['70%', '30%'], children:[
                        {type:'text', id:'width', label:b.width, widths:['71%', '29%'], labelLayout:'horizontal', validate:e.number(c.invalidWidth), setup:function (j) {
                            var k = f.exec(j.$.style.width);
                            if (k)this.setValue(k[1]);
                        }, commit:function (j) {
                            var k = this.getDialog().getValueOf('info', 'widthType');
                            if (this.getValue() !== '')j.$.style.width = this.getValue() + k; else j.$.style.width = '';
                        }, 'default':''},
                        {type:'select', id:'widthType', labelLayout:'horizontal', widths:['0%', '100%'], label:'', 'default':'px', items:[
                            [b.widthPx, 'px'],
                            [b.widthPc, '%']
                        ], setup:function (j) {
                            var k = f.exec(j.$.style.width);
                            if (k)this.setValue(k[2]);
                        }}
                    ]},
                    {type:'hbox', widths:['70%', '30%'], children:[
                        {type:'text', id:'height', label:b.height, 'default':'', widths:['71%', '29%'], labelLayout:'horizontal', validate:e.number(c.invalidHeight), setup:function (j) {
                            var k = g.exec(j.$.style.height);
                            if (k)this.setValue(k[1]);
                        }, commit:function (j) {
                            if (this.getValue() !== '')j.$.style.height = this.getValue() + 'px'; else j.$.style.height = '';
                        }},
                        {type:'html', html:b.widthPx}
                    ]},
                    i(),
                    {type:'select', id:'wordWrap', labelLayout:'horizontal', label:c.wordWrap, widths:['50%', '50%'], 'default':'yes', items:[
                        [c.yes, 'yes'],
                        [c.no, 'no']
                    ], commit:function (j) {
                        if (this.getValue() == 'no')j.setAttribute('noWrap', 'nowrap'); else j.removeAttribute('noWrap');
                    }},
                    i(),
                    {type:'select', id:'hAlign', labelLayout:'horizontal', label:c.hAlign, widths:['50%', '50%'], 'default':'', items:[
                        [d.notSet, ''],
                        [b.alignLeft, 'left'],
                        [b.alignCenter, 'center'],
                        [b.alignRight, 'right']
                    ], setup:function (j) {
                        this.setValue(j.getAttribute('align') || '');
                    }, commit:function (j) {
                        if (this.getValue())j.setAttribute('align', this.getValue()); else j.removeAttribute('align');
                    }},
                    {type:'select', id:'vAlign', labelLayout:'horizontal', label:c.vAlign, widths:['50%', '50%'], 'default':'', items:[
                        [d.notSet, ''],
                        [c.alignTop, 'top'],
                        [c.alignMiddle, 'middle'],
                        [c.alignBottom, 'bottom'],
                        [c.alignBaseline, 'baseline']
                    ], setup:function (j) {
                        this.setValue(j.getAttribute('vAlign') || '');
                    }, commit:function (j) {
                        if (this.getValue())j.setAttribute('vAlign', this.getValue()); else j.removeAttribute('vAlign');
                    }}
                ]},
                i(),
                {type:'vbox', padding:0, children:[
                    {type:'select', id:'cellType', label:c.cellType, labelLayout:'horizontal', widths:['50%', '50%'], 'default':'td', items:[
                        [c.data, 'td'],
                        [c.header, 'th']
                    ], setup:function (j) {
                        this.setValue(j.getName());
                    }, commit:function (j) {
                        j.renameNode(this.getValue());
                    }},
                    i(),
                    {type:'text', id:'rowSpan', label:c.rowSpan, labelLayout:'horizontal', widths:['50%', '50%'], 'default':'', validate:e.integer(c.invalidRowSpan), setup:function (j) {
                        this.setValue(j.getAttribute('rowSpan') || '');
                    }, commit:function (j) {
                        if (this.getValue())j.setAttribute('rowSpan', this.getValue()); else j.removeAttribute('rowSpan');
                    }},
                    {type:'text', id:'colSpan', label:c.colSpan, labelLayout:'horizontal', widths:['50%', '50%'], 'default':'', validate:e.integer(c.invalidColSpan), setup:function (j) {
                        this.setValue(j.getAttribute('colSpan') || '');
                    }, commit:function (j) {
                        if (this.getValue())j.setAttribute('colSpan', this.getValue()); else j.removeAttribute('colSpan');
                    }},
                    i(),
                    {type:'text', id:'bgColor', label:c.bgColor, labelLayout:'horizontal', widths:['50%', '50%'], 'default':'', setup:function (j) {
                        this.setValue(j.getAttribute('bgColor') || '');
                    }, commit:function (j) {
                        if (this.getValue())j.setAttribute('bgColor', this.getValue()); else j.removeAttribute('bgColor');
                    }},
                    {type:'text', id:'borderColor', label:c.borderColor, labelLayout:'horizontal', widths:['50%', '50%'], 'default':'', setup:function (j) {
                        this.setValue(j.getAttribute('borderColor') || '');
                    }, commit:function (j) {
                        if (this.getValue())j.setAttribute('borderColor', this.getValue()); else j.removeAttribute('borderColor');
                    }}
                ]}
            ]}
        ]}
    ], onShow:function () {
        var j = this;
        j.cells = CKEDITOR.plugins.tabletools.getSelectedCells(j._.editor.getSelection());
        j.setupContent(j.cells[0]);
    }, onOk:function () {
        var j = this.cells;
        for (var k = 0; k < j.length; k++)this.commitContent(j[k]);
    }};
});

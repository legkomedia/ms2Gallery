Ext.namespace('ms2Gallery.combo');

ms2Gallery.combo.Source = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		name: config.name || 'source-cmb',
		id: 'ms2gallery-resource-source',
		hiddenName: 'source-cmb',
		displayField: 'name',
		valueField: 'id',
		width: 300,
		listWidth: 300,
		fieldLabel: _('ms2gallery_' + config.name || 'source'),
		anchor: '99%',
		allowBlank: false
	});
	ms2Gallery.combo.Source.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.Source,MODx.combo.MediaSource);
Ext.reg('ms2gallery-combo-source',ms2Gallery.combo.Source);


ms2Gallery.combo.Tags = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		xtype: 'superboxselect',
		allowBlank: true,
		msgTarget: 'under',
		allowAddNewData: true,
		addNewDataOnBlur : true,
		pinList: false,
		resizable: true,
		name: 'tags',
		anchor: '100%',
		minChars: 2,
		//pageSize: 10,
		store:new Ext.data.JsonStore({
			root: 'results',
			autoLoad: false,
			autoSave: false,
			totalProperty: 'total',
			fields: ['tag'],
			url: ms2Gallery.config.connector_url,
			baseParams: {
				action: 'mgr/gallery/gettags'
			}
		}),
		mode: 'remote',
		displayField: 'tag',
		valueField: 'tag',
		triggerAction: 'all',
		extraItemCls: 'x-tag',
		expandBtnCls:'x-form-trigger',
		clearBtnCls: 'x-form-trigger',
		listeners: {
			newitem: function(bs, v) {
				bs.addNewItem({tag: v});
			}
		}
	});
	config.name += '[]';
	ms2Gallery.combo.Tags.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.Tags,Ext.ux.form.SuperBoxSelect);
Ext.reg('ms2gallery-combo-tags',ms2Gallery.combo.Tags);


ms2Gallery.combo.Search = function(config) {
	config = config || {};
	Ext.applyIf(config, {
		xtype: 'twintrigger',
		ctCls: 'x-field-search',
		allowBlank: true,
		msgTarget: 'under',
		emptyText: _('ms2gallery_file_search'),
		name: 'query',
		triggerAction: 'all',
		clearBtnCls: 'x-field-search-clear',
		searchBtnCls: 'x-field-search-go',
		onTrigger1Click: this._triggerSearch,
		onTrigger2Click: this._triggerClear,
	});
	ms2Gallery.combo.Search.superclass.constructor.call(this, config);
	this.on('render', function() {
		this.getEl().addKeyListener(Ext.EventObject.ENTER, function() {
			this._triggerSearch();
		}, this);
	});
	this.addEvents('clear', 'search');
};
Ext.extend(ms2Gallery.combo.Search, Ext.form.TwinTriggerField, {

	initComponent: function() {
		Ext.form.TwinTriggerField.superclass.initComponent.call(this);
		this.triggerConfig = {
			tag: 'span',
			cls: 'x-field-search-btns',
			cn: [
				{tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
				{tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
			]
		};
	},

	_triggerSearch: function() {
		this.fireEvent('search', this);
	},

	_triggerClear: function() {
		this.fireEvent('clear', this);
	},

});
Ext.reg('ms2gallery-field-search', ms2Gallery.combo.Search);
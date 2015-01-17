Ext.namespace('ms2Gallery.combo');

ms2Gallery.combo.Source = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		name: config.name || 'source-cmb'
		,id: 'ms2gallery-resource-source'
		,hiddenName: 'source-cmb'
		,displayField: 'name'
		,valueField: 'id'
		,width: 300
		,listWidth: 300
		,fieldLabel: _('ms2gallery_' + config.name || 'source')
		,anchor: '99%'
		,allowBlank: false
	});
	ms2Gallery.combo.Source.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.Source,MODx.combo.MediaSource);
Ext.reg('ms2gallery-combo-source',ms2Gallery.combo.Source);


ms2Gallery.combo.Tags = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		xtype: 'superboxselect'
		,allowBlank: true
		,msgTarget: 'under'
		,allowAddNewData: true
		,addNewDataOnBlur : true
		,pinList: false
		,resizable: true
		,name: 'tags'
		,anchor: '100%'
		,minChars: 2
		//,pageSize: 10
		,store:new Ext.data.JsonStore({
			root: 'results'
			,autoLoad: false
			,autoSave: false
			,totalProperty: 'total'
			,fields: ['tag']
			,url: ms2Gallery.config.connector_url
			,baseParams: {
				action: 'mgr/gallery/gettags'
			}
		})
		,mode: 'remote'
		,displayField: 'tag'
		,valueField: 'tag'
		,triggerAction: 'all'
		,extraItemCls: 'x-tag'
		,expandBtnCls: MODx.modx23 ? 'x-form-trigger' : 'x-superboxselect-btn-expand'
		,clearBtnCls: MODx.modx23 ? 'x-form-trigger' : 'x-superboxselect-btn-clear'
		,listeners: {
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
Ext.namespace('ms2Gallery.combo');

ms2Gallery.combo.User = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'user'
		,fieldLabel: _('ms2gallery_' + config.name || 'createdby')
		,hiddenName: config.name || 'createdby'
		,displayField: 'username'
		,valueField: 'id'
		,anchor: '99%'
		,fields: ['username','id']
		,pageSize: 20
		,url: MODx.config.connectors_url + 'security/user.php'
		,typeAhead: true
		,editable: true
		,action: 'getList'
		,allowBlank: true
		,baseParams: {
			action: 'getlist'
			,combo: 1
			,id: config.value
			//,limit: 0
		}
	});
	ms2Gallery.combo.User.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.User,MODx.combo.ComboBox);
Ext.reg('ms2gallery-combo-user',ms2Gallery.combo.User);


ms2Gallery.combo.Category = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'tickets-combo-section'
		,fieldLabel: _('ms2gallery_parent')
		,description: '<b>[[*parent]]</b><br />'+_('ms2gallery_parent_help')
		,fields: ['id','pagetitle','parents']
		,valueField: 'id'
		,displayField: 'pagetitle'
		,name: 'parent-cmb'
		,hiddenName: 'parent-cmp'
		,allowBlank: false
		,url: ms2Gallery.config.connector_url
		,baseParams: {
			action: 'mgr/category/getcats'
			,combo: 1
			,id: config.value
			//,limit: 0
		}
		,tpl: new Ext.XTemplate(''
			+'<tpl for="."><div class="ms2gallery-category-list-item">'
			+'<tpl if="parents">'
					+'<span class="parents">'
						+'<tpl for="parents">'
							+'<nobr><small>{pagetitle} / </small></nobr>'
						+'</tpl>'
					+'</span>'
			+'</tpl>'
			+'<span><small>({id})</small> <b>{pagetitle}</b></span>'
			+'</div></tpl>',{
			compiled: true
		})
		,itemSelector: 'div.ms2gallery-category-list-item'
		,pageSize: 20
		//,typeAhead: true
		,editable: true
	});
	ms2Gallery.combo.Category.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.Category,MODx.combo.ComboBox);
Ext.reg('ms2gallery-combo-category',ms2Gallery.combo.Category);


ms2Gallery.combo.DateTime = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		timePosition:'right'
		,allowBlank: true
		,hiddenFormat:'Y-m-d H:i:s'
		,dateFormat: MODx.config.manager_date_format
		,timeFormat: MODx.config.manager_time_format
		,dateWidth: 120
		,timeWidth: 120
	});
	ms2Gallery.combo.DateTime.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.DateTime,Ext.ux.form.DateTime);
Ext.reg('ms2gallery-xdatetime',ms2Gallery.combo.DateTime);


ms2Gallery.combo.Autocomplete = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		name: config.name
		,fieldLabel: _('ms2gallery_' + config.name)
		,id: 'ms2gallery-resource-' + config.name
		,hiddenName: config.name
		,displayField: config.name
		,valueField: config.name
		,anchor: '99%'
		,fields: [config.name]
		//,pageSize: 20
		,forceSelection: false
		,url: ms2Gallery.config.connector_url
		,typeAhead: true
		,editable: true
		,allowBlank: true
		,baseParams: {
			action: 'mgr/resource/autocomplete'
			,name: config.name
			,combo:1
			,limit: 0
		}
		,hideTrigger: true
	});
	ms2Gallery.combo.Autocomplete.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.Autocomplete,MODx.combo.ComboBox);
Ext.reg('ms2gallery-combo-autocomplete',ms2Gallery.combo.Autocomplete);


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


ms2Gallery.combo.Options = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		xtype:'superboxselect'
		,allowBlank: true
		,msgTarget: 'under'
		,allowAddNewData: true
		,addNewDataOnBlur : true
		,resizable: true
		,name: config.name || 'tags'
		,anchor:'100%'
		,minChars: 2
		,store:new Ext.data.JsonStore({
			id: (config.name || 'tags') + '-store'
			,root:'results'
			,autoLoad: true
			,autoSave: false
			,totalProperty:'total'
			,fields:['value']
			,url: ms2Gallery.config.connector_url
			,baseParams: {
				action: 'mgr/resource/getoptions'
				,key: config.name
			}
		})
		,mode: 'remote'
		,displayField: 'value'
		,valueField: 'value'
		,triggerAction: 'all'
		,extraItemCls: 'x-tag'
		,listeners: {
			newitem: function(bs,v, f){
				var newObj = {
					tag: v
				};
				bs.addItem(newObj);
			}
		}
	});
	config.name += '[]';
	ms2Gallery.combo.Options.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.Options,Ext.ux.form.SuperBoxSelect);
Ext.reg('ms2gallery-combo-options',ms2Gallery.combo.Options);


ms2Gallery.combo.Chunk = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'chunk'
		,hiddenName: 'chunk'
		,displayField: 'name'
		,valueField: 'id'
		,editable: true
		,fields: ['id','name']
		,pageSize: 20
		,emptyText: _('ms2gallery_combo_select')
		,hideMode: 'offsets'
		,url: ms2Gallery.config.connector_url
		,baseParams: {
			action: 'mgr/system/element/chunk/getlist'
			,mode: 'chunks'
		}
	});
	ms2Gallery.combo.Chunk.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.combo.Chunk,MODx.combo.ComboBox);
Ext.reg('ms2gallery-combo-chunk',ms2Gallery.combo.Chunk);


ms2Gallery.combo.Browser = function(config) {
	config = config || {};

	if (config.length != 0 && typeof config.openTo !== "undefined") {
		if (!/^\//.test(config.openTo)) {
			config.openTo = '/' + config.openTo;
		}
		if (!/$\//.test(config.openTo)) {
			var tmp = config.openTo.split('/')
			delete tmp[tmp.length - 1];
			tmp = tmp.join('/');
			config.openTo = tmp.substr(1)
		}
	}

	Ext.applyIf(config,{
		width: 300
		,triggerAction: 'all'
	});
	ms2Gallery.combo.Browser.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(ms2Gallery.combo.Browser,Ext.form.TriggerField,{
	browser: null

	,onTriggerClick : function(btn){
		if (this.disabled){
			return false;
		}

		if (this.browser === null) {
			this.browser = MODx.load({
				xtype: 'modx-browser'
				,id: Ext.id()
				,multiple: true
				,source: this.config.source || MODx.config.default_media_source
				,rootVisible: this.config.rootVisible || false
				,allowedFileTypes: this.config.allowedFileTypes || ''
				,wctx: this.config.wctx || 'web'
				,openTo: this.config.openTo || ''
				,rootId: this.config.rootId || '/'
				,hideSourceCombo: this.config.hideSourceCombo || false
				,hideFiles: this.config.hideFiles || true
				,listeners: {
					'select': {fn: function(data) {
						this.setValue(data.fullRelativeUrl);
						this.fireEvent('select',data);
					},scope:this}
				}
			});
		}
		this.browser.win.buttons[0].on('disable',function(e) {this.enable()})
		this.browser.win.tree.on('click', function(n,e) {
				path = this.getPath(n);
				this.setValue(path);
			},this
		);
		this.browser.win.tree.on('dblclick', function(n,e) {
				path = this.getPath(n);
				this.setValue(path);
				this.browser.hide()
			},this
		);
		this.browser.show(btn);
		return true;
	}
	,onDestroy: function(){
		ms2Gallery.combo.Browser.superclass.onDestroy.call(this);
	}
	,getPath: function(n) {
		if (n.id == '/') {return '';}
		data = n.attributes;
		path = data.path + '/';

		return path;
	}
});
Ext.reg('ms2gallery-combo-browser',ms2Gallery.combo.Browser);


ms2Gallery.combo.listeners_disable = {
	render: function() {
		this.store.on('load', function() {
			if (this.store.getTotalCount() == 1 && this.store.getAt(0).id == this.value) {
				this.readOnly = true;
				this.addClass('disabled');
			}
			else {
				this.readOnly = false;
				this.removeClass('disabled');
			}
		}, this);
	}
};
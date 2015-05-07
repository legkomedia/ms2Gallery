ms2Gallery.panel.Images = function(config) {
	config = config || {};

	this.view = MODx.load({
		xtype: 'ms2gallery-images-view',
		id: 'ms2gallery-images-view',
		cls: 'ms2gallery-images',
		//onSelect: {fn:function() { }, scope: this},
		containerScroll: true,
		pageSize: parseInt(config.pageSize) || MODx.config.default_per_page,
		resource_id: config.resource_id,
	});

	Ext.applyIf(config,{
		id: 'ms2gallery-images',
		cls: 'browser-view',
		border: false,
		items: [this.view],
		tbar: this.getTopBar(config),
		bbar: this.getBottomBar(config),
	});
	ms2Gallery.panel.Images.superclass.constructor.call(this,config);

	var dv = this.view;
	dv.on('render', function() {
		dv.dragZone = new ms2Gallery.DragZone(dv);
		dv.dropZone = new ms2Gallery.DropZone(dv);
	});
};
Ext.extend(ms2Gallery.panel.Images,MODx.Panel, {

	Tags: function(tf) {
		var s = this.view.getStore();
		s.baseParams.tags = tf.getValue();
		this.getBottomToolbar().changePage(1);
	},

	clearTags: function() {
		var s = this.view.getStore();
		s.baseParams.tags = '';
		this.getBottomToolbar().changePage(1);
	},

	Search: function(tf) {
		this.view.getStore().baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
	},

	clearSearch: function() {
		this.view.getStore().baseParams.query = '';
		this.getBottomToolbar().changePage(1);
	},

	getTopBar: function(config) {
		return new Ext.Toolbar({
			items: [{
				xtype: 'ms2gallery-combo-tags',
				id: 'ms2gallery-combo-tags-filter',
				width: 300,
				emptyText: _('ms2gallery_file_tags'),
				allowAddNewData: false,
				addNewDataOnBlur: false,
				supressClearValueRemoveEvents: true,
				pageSize: 10,
				listeners: {
					clear: {fn:function(tf) {this.clearTags();}, scope:this},
					additem: {fn:function(tf) {this.Tags(tf);}, scope:this},
					removeitem: {fn:function(tf) {this.Tags(tf);}, scope:this},
				},
			}, '->', {
				xtype: 'ms2gallery-field-search',
				width: 300,
				listeners: {
					search: {fn: function(field) {
						this.Search(field);
					}, scope: this},
					clear: {fn: function(field) {
						field.setValue('');
						this.clearSearch();
					}, scope: this},
				},
			}]
		})
	},

	getBottomBar: function(config) {
		return new Ext.PagingToolbar({
			pageSize: parseInt(config.pageSize) || MODx.config.default_per_page,
			store: this.view.store,
			displayInfo: true,
			autoLoad: true,
			items: ['-',
				_('per_page') + ':',
				{
					xtype: 'textfield',
					value: parseInt(config.pageSize) || MODx.config.default_per_page,
					width: 50,
					listeners: {
						change: {fn:function(tf,nv,ov) {
							if (Ext.isEmpty(nv)) {return;}
							nv = parseInt(nv);
							this.getBottomToolbar().pageSize = nv;
							this.view.getStore().load({params:{start:0, limit: nv}});
						}, scope:this},
						render: {fn: function(cmp) {
							new Ext.KeyMap(cmp.getEl(), {
								key: Ext.EventObject.ENTER
								,fn: function() {this.fireEvent('change',this.getValue());this.blur();return true;}
								,scope: cmp
							});
						}, scope:this}
					}
				}
			]
		});
	},

});
Ext.reg('ms2gallery-images-panel',ms2Gallery.panel.Images);


ms2Gallery.view.Images = function(config) {
	config = config || {};

	this._initTemplates();

	Ext.applyIf(config,{
		url: ms2Gallery.config.connector_url
		,fields: [
			'id','resource_id','name','description','url','createdon','createdby','file','thumbnail',
			'source','source_name','type','rank','active','properties','class',
			'add','alt','tags','actions'
		]
		,id: 'ms2gallery-images-view'
		,baseParams: {
			action: 'mgr/gallery/getlist'
			,resource_id: config.resource_id
			,parent: 0
			,type: 'image'
			,limit: config.pageSize || MODx.config.default_per_page
		}
		//,loadingText: _('loading')
		,enableDD: true
		,multiSelect: true
		,tpl: this.templates.thumb
		,itemSelector: 'div.modx-browser-thumb-wrap'
		,listeners: {}
		,prepareData: this.formatData.createDelegate(this)
	});
	ms2Gallery.view.Images.superclass.constructor.call(this,config);

	this.addEvents('sort','select');
	this.on('sort',this.onSort,this);
	this.on('dblclick',this.onDblClick,this);

	var widget = this;
	this.getStore().on('beforeload', function() {
		widget.getEl().mask(_('loading'),'x-mask-loading');
	});
	this.getStore().on('load', function() {
		widget.getEl().unmask();
	});
};
Ext.extend(ms2Gallery.view.Images,MODx.DataView,{

	templates: {},
	windows: {},

	onSort: function(o) {
		var el = this.getEl();
		el.mask(_('loading'),'x-mask-loading');
		MODx.Ajax.request({
			url: ms2Gallery.config.connector_url
			,params: {
				action: 'mgr/gallery/sort'
				,resource_id: this.config.resource_id
				,source: o.source.id
				,target: o.target.id
			}
			,listeners: {
				success: {fn: function() {
					el.unmask();
					this.store.reload();
				}, scope: this}
			}
		});
	},

	onDblClick: function(e) {
		var node = this.getSelectedNodes()[0];
		if (!node) {return;}

		this.cm.activeNode = node;
		this.updateFile(node,e);
	},

	updateFile: function(btn,e) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) {return;}

		var w = MODx.load({
			xtype: 'ms2gallery-gallery-image',
			record: data,
			listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
		w.setValues(data);
		w.show(e.target);
	},

	editTags: function(btn,e) {
		var ids = this._getSelectedIds();
		var arr1 = [];
		for (var id in ids) {
			if (!ids.hasOwnProperty(id)) {
				continue;
			}
			var data = this.lookup['ms2-resource-image-' + ids[id]];
			if (data) {
				var arr2 = [];
				for (var tag in data.tags) {
					if (data.tags.hasOwnProperty(tag)) {
						if (id == 0) {
							arr1.push(data.tags[tag]['tag']);
						}
						else {
							arr2.push(data.tags[tag]['tag']);
						}
					}
				}
				if (id > 0) {
					arr1 = this._array_intersect(arr1, arr2);
				}
			}
		}

		var tags = [];
		if (arr1.length > 0) {
			for (var i in arr1) {
				if (arr1.hasOwnProperty(i)) {
					tags.push({tag: arr1[i]});
				}
			}
		}

		var w = MODx.load({
			xtype: 'ms2gallery-gallery-tags',
			ids: Ext.util.JSON.encode(ids),
			tags: tags,
			listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
		w.show(e.target);
	},

	showFile: function(btn,e) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) {return;}

		window.open(data.url);
	},

	fileAction: function(method) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		this.getEl().mask(_('loading'),'x-mask-loading');
		MODx.Ajax.request({
			url: ms2Gallery.config.connector_url,
			params: {
				action: 'mgr/gallery/multiple',
				method: method,
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function () {
						this.store.reload();
					}, scope: this
				},
				failure: {
					fn: function (response) {
						MODx.msg.alert(_('error'), response.message);
					}, scope: this
				},
			}
		})
	},

	deleteFiles: function() {
		var ids = this._getSelectedIds();
		var title = ids.length > 1
			? 'ms2gallery_file_delete_multiple'
			: 'ms2gallery_file_delete';
		var message = ids.length > 1
			? 'ms2gallery_file_delete_multiple_confirm'
			: 'ms2gallery_file_delete_confirm';
		Ext.MessageBox.confirm(
			_(title),
			_(message),
			function(val) {
				if (val == 'yes') {
					this.fileAction('remove');
				}
			},
			this
		);
	},

	generateThumbs: function() {
		this.fileAction('generate');
	},

	activateFiles: function() {
		this.fileAction('activate');
	},

	inActivateFiles: function() {
		this.fileAction('inactivate');
	},

	run: function(p) {
		p = p || {};
		var v = {};
		Ext.apply(v,this.store.baseParams);
		Ext.apply(v,p);
		this.changePage(1);
		this.store.baseParams = v;
		this.store.load();
	},

	formatData: function(data) {
		data.shortName = Ext.util.Format.ellipsis(data.name, 20);
		data.createdon = ms2Gallery.utils.formatDate(data.createdon);
		data.size = (data.properties['width'] && data.properties['height'])
			? data.properties['width'] + 'x' + data.properties['height']
			: '';
		if (data.properties['size'] && data.size) {
			data.size += ', ';
		}
		data.size += data.properties['size']
			? ms2Gallery.utils.formatSize(data.properties['size'])
			: '';
		this.lookup['ms2-resource-image-'+data.id] = data;
		return data;
	},

	_initTemplates: function() {
		this.templates.thumb = new Ext.XTemplate(
			'<tpl for=".">\
				<div class="modx-browser-thumb-wrap modx-pb-thumb-wrap ms2gallery-thumb-wrap {class}" id="ms2-resource-image-{id}">\
					<div class="modx-browser-thumb modx-pb-thumb ms2gallery-thumb">\
						<img src="{thumbnail}" title="{name}" />\
					</div>\
					<small>{rank}. {shortName}</small>\
				</div>\
			</tpl>'
		);
		this.templates.thumb.compile();
	},

	_showContextMenu: function(v, i, n, e) {
		e.preventDefault();
		var data = this.lookup[n.id];
		var m = this.cm;
		m.removeAll();

		var menu = ms2Gallery.utils.getMenu(data.actions, this, this._getSelectedIds());
		for (var item in menu) {
			if (!menu.hasOwnProperty(item)) {
				continue;
			}
			m.add(menu[item]);
		}

		m.show(n, 'tl-c?');
		m.activeNode = n;
	},

	_getSelectedIds: function() {
		var ids = [];
		var selected = this.getSelectedRecords();

		for (var i in selected) {
			if (!selected.hasOwnProperty(i)) {
				continue;
			}
			ids.push(selected[i]['id']);
		}

		return ids;
	},

	_array_intersect: function(arr1, arr2) {
		var results = [];

		for (var i = 0; i < arr1.length; i++) {
			if (arr2.indexOf(arr1[i]) !== -1) {
				results.push(arr1[i]);
			}
		}

		return results;
	}

});
Ext.reg('ms2gallery-images-view', ms2Gallery.view.Images);
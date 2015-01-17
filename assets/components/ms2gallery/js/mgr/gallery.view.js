ms2Gallery.panel.Images = function(config) {
	config = config || {};

	this.view = MODx.load({
		xtype: 'ms2gallery-images-view'
		,id: 'ms2gallery-images-view'
		,cls: 'ms2gallery-images'
		//,onSelect: {fn:function() { }, scope: this}
		,containerScroll: true
		,pageSize: config.pageSize || MODx.config.default_per_page
		,resource_id: config.resource_id
	});

	Ext.applyIf(config,{
		id: 'ms2gallery-images'
		,cls: 'browser-view'
		,border: false
		,items: [this.view]
		,tbar: this.getTopBar(config)
		,bbar: this.getBottomBar(config)
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
	}

	,Search: function(tf) {
		var s = this.view.getStore();
		s.baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
	}

	,clearTags: function() {
		var s = this.view.getStore();
		s.baseParams.tags = '';
		this.getBottomToolbar().changePage(1);
	}

	,clearSearch: function() {
		var s = this.view.getStore();
		s.baseParams.query = '';
		Ext.getCmp('ms2gallery-search-filter').setValue('');
		this.getBottomToolbar().changePage(1);
	}

	,getTopBar: function(config) {
		return new Ext.Toolbar({
			items: [
				{
					xtype: 'ms2gallery-combo-tags'
					,id: 'ms2gallery-combo-tags-filter'
					,width: 330
					,emptyText: _('ms2gallery_file_tags')
					,allowAddNewData: false
					,addNewDataOnBlur: false
					,supressClearValueRemoveEvents: true
					,pageSize: 10 //MODx.config.default_per_page
					//,editable: false
					,listeners: {
						clear: {fn:function(tf) {this.clearTags();}, scope:this}
						,additem: {fn:function(tf) {this.Tags(tf);}, scope:this}
						,removeitem: {fn:function(tf) {this.Tags(tf);}, scope:this}
					}
				} ,'->' ,{
					xtype: 'trigger'
					,id: 'ms2gallery-search-filter'
					,width: 300
					,emptyText: _('ms2gallery_file_search')
					,triggerClass: 'ms2gallery-search-clear' + (MODx.modx23 ? '' : ' x-superboxselect-btn-clear')
					,onTriggerClick: function() {this.fireEvent('click',this);}
					,listeners: {
						render: {fn:function(tf) {
							tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
								this.Search(tf);
							},this);
						},scope:this}
						,click: {fn:function() {
							this.clearSearch();
						}, scope:this}
					}
				}
			]
		})
	}
	,getBottomBar: function(config) {
		return new Ext.PagingToolbar({
			pageSize: config.pageSize || MODx.config.default_per_page
			,store: this.view.store
			,displayInfo: true
			,autoLoad: true
			,items: ['-',
				_('per_page') + ':'
				,{
					xtype: 'textfield'
					,value: config.pageSize || MODx.config.default_per_page
					,width: 50
					,listeners: {
						change: {fn:function(tf,nv,ov) {
							if (Ext.isEmpty(nv)) {return;}
							nv = parseInt(nv);
							this.getBottomToolbar().pageSize = nv;
							this.view.getStore().load({params:{start:0, limit: nv}});
						}, scope:this}
						,render: {fn: function(cmp) {
							new Ext.KeyMap(cmp.getEl(), {
								key: Ext.EventObject.ENTER
								,fn: function() {this.fireEvent('change',this.getValue());this.blur();return true;}
								,scope: cmp
							});
						}, scope:this}
					}
				}
			]
		})
	}

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
			'add','alt','tags'
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

	templates: {}
	,windows: {}

	,onSort: function(o) {
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
	}

	,onDblClick: function(e) {
		var node = this.getSelectedNodes()[0];
		if (!node) {return;}

		this.cm.activeNode = node;
		this.updateImage(node,e);
	}

	,updateImage: function(btn,e) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) {return;}

		var w = MODx.load({
			xtype: 'ms2gallery-gallery-image'
			,record: data
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
		w.setValues(data);
		w.show(e.target);
	}

	,showImage: function(btn,e) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) {return;}

		window.open(data.url);
	}

	,deleteImage: function(btn,e) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) return;

		MODx.msg.confirm({
			text: _('ms2gallery_file_delete_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/gallery/remove'
				,id: data.id
			}
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
	}

	,deleteMultiple: function(btn,e) {
		var recs = this.getSelectedRecords();
		if (!recs) return;

		var ids = '';
		for (var i=0;i<recs.length;i++) {
			ids += ','+recs[i].id;
		}

		MODx.msg.confirm({
			text: _('ms2gallery_file_delete_multiple_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/gallery/remove_multiple'
				,ids: ids.substr(1)
				,resource_id: this.config.resource_id
			}
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
	}

	,generateThumbs: function() {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) return;

		MODx.Ajax.request({
			url: ms2Gallery.config.connector_url
			,params: {
				action: 'mgr/gallery/generate'
				,id: data.id
			}
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
	}

	,generateThumbsMultiple: function() {
		var recs = this.getSelectedRecords();
		if (!recs) return;

		var ids = '';
		for (var i=0;i<recs.length;i++) {
			ids += ','+recs[i].id;
		}
		MODx.Ajax.request({
			url: ms2Gallery.config.connector_url
			,params: {
				action: 'mgr/gallery/generate_multiple'
				,ids: ids.substr(1)
			}
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
	}

	,Activate: function() {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) return;

		MODx.Ajax.request({
			url: ms2Gallery.config.connector_url
			,params: {
				action: 'mgr/gallery/activate'
				,id: data.id
			}
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
	}

	,activateMultiple: function() {
		var recs = this.getSelectedRecords();
		if (!recs) return;

		var ids = '';
		for (var i=0;i<recs.length;i++) {
			ids += ','+recs[i].id;
		}
		MODx.Ajax.request({
			url: ms2Gallery.config.connector_url
			,params: {
				action: 'mgr/gallery/activate_multiple'
				,ids: ids.substr(1)
			}
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
	}

	,inActivate: function() {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) return;

		MODx.Ajax.request({
			url: ms2Gallery.config.connector_url
			,params: {
				action: 'mgr/gallery/inactivate'
				,id: data.id
			}
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
	}

	,inactivateMultiple: function() {
		var recs = this.getSelectedRecords();
		if (!recs) return;

		var ids = '';
		for (var i=0;i<recs.length;i++) {
			ids += ','+recs[i].id;
		}
		MODx.Ajax.request({
			url: ms2Gallery.config.connector_url
			,params: {
				action: 'mgr/gallery/inactivate_multiple'
				,ids: ids.substr(1)
			}
			,listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
	}

	,run: function(p) {
		p = p || {};
		var v = {};
		Ext.apply(v,this.store.baseParams);
		Ext.apply(v,p);
		this.changePage(1);
		this.store.baseParams = v;
		this.store.load();
	}

	,formatData: function(data) {
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
	}

	,_initTemplates: function() {
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
	}

	,_showContextMenu: function(v,i,n,e) {
		e.preventDefault();
		var data = this.lookup[n.id];
		var m = this.cm;
		m.removeAll();
		var ct = this.getSelectionCount();
		var icon = MODx.modx23
			? 'x-menu-item-icon icon icon-'
			: 'x-menu-item-icon fa fa-';

		if (ct == 1) {
			m.add({
				text: '<i class="'+icon+'edit"></i> ' + _('ms2gallery_file_update')
				,handler: this.updateImage
				,scope: this
			});
			m.add({
				text: '<i class="'+icon+'share"></i> ' + _('ms2gallery_file_show')
				,handler: this.showImage
				,scope: this
			});
			if (data.type == 'image') {
				m.add({
					text: '<i class="'+icon+'refresh"></i> ' + _('ms2gallery_image_generate_thumbs')
					,handler: this.generateThumbs
					,scope: this
				});
			}
			if (data.active == 1) {
				m.add({
					text: '<i class="'+icon+'power-off"></i> ' + _('ms2gallery_file_inactivate')
					,handler: this.inActivate
					,scope: this
				});
			}
			else {
				m.add({
					text: '<i class="'+icon+'check"></i> ' + _('ms2gallery_file_activate')
					,handler: this.Activate
					,scope: this
				});
			}
			m.add('-');
			m.add({
				text: '<i class="'+icon+'times"></i> ' + _('ms2gallery_file_delete')
				,handler: this.deleteImage
				,scope: this
			});
			m.show(n,'tl-c?');
		}
		else if (ct > 1) {
			if (data.type == 'image') {
				m.add({
					text: '<i class="'+icon+'refresh"></i> ' + _('ms2gallery_image_generate_thumbs')
					,handler: this.generateThumbsMultiple
					,scope: this
				});
			}
			m.add({
				text: '<i class="'+icon+'check"></i> ' + _('ms2gallery_file_activate_multiple')
				,handler: this.activateMultiple
				,scope: this
			});
			m.add({
				text: '<i class="'+icon+'power-off"></i> ' + _('ms2gallery_file_inactivate_multiple')
				,handler: this.inactivateMultiple
				,scope: this
			});
			m.add('-');
			m.add({
				text: '<i class="'+icon+'times"></i> ' + _('ms2gallery_file_delete_multiple')
				,handler: this.deleteMultiple
				,scope: this
			});
			m.show(n,'tl-c?');
		}

		m.activeNode = n;
	}

});
Ext.reg('ms2gallery-images-view',ms2Gallery.view.Images);
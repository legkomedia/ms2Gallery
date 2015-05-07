ms2Gallery.window.Image = function(config) {
	config = config || {};
	this.ident = config.ident || Ext.id();

	var img = MODx.config.connectors_url + 'system/phpthumb.php?src=';
	img += config.record['type'] == 'image'
		? config.record['url']
		: config.record['thumbnail'];
	img += '&w=333&h=198&f=jpg&bg=efefef&q=90&zc=0&far=1&HTTP_MODAUTH=' + MODx.siteId + '&wctx=mgr&source=' + config.record['source'];

	var fields = {
		ms2gallery_source: config.record['source_name'],
		ms2gallery_size: config.record['size'],
		ms2gallery_createdon: config.record['createdon'],
		//ms2gallery_rank: config.record['rank'],
	};
	var details = '';
	for (var i in fields) {
		if (!fields.hasOwnProperty(i)) {continue;}
		if (fields[i]) {
			details += '<tr><th>' + _(i) + ':</th><td>' + fields[i] + '</td></tr>';
		}
	}

	Ext.applyIf(config, {
		title: config.record['name'] || _('ms2gallery_file_update'),
		id: this.ident,
		cls: 'ms2gallery ' + (MODx.modx23 ? 'modx23' : 'modx22'),
		width: 700,
		autoHeight: true,
		url: ms2Gallery.config.connector_url,
		action: 'mgr/gallery/update',
		layout: 'form',
		resizable: false,
		maximizable: false,
		minimizable: false,
		fields: [
			{xtype: 'hidden',name: 'id',id: this.ident+'-id'},
			{
				layout: 'column',
				border: false,
				anchor: '100%',
				items: [{
					columnWidth: .5,
					layout: 'form',
					defaults: { msgTarget: 'under' },
					border:false,
					items: [{
						xtype: 'displayfield',
						hideLabel: true,
						html: '\
						<a href="' + config.record['url'] + '" target="_blank">\
							<img src="' + img + '" class="ms2gallery-window-thumb" />\
						</a>\
						<table class="ms2gallery-window-details">' + details + '</table>'
					}]
				},{
					columnWidth: .5,
					layout: 'form',
					defaults: { msgTarget: 'under' },
					border:false,
					items: [
						{
							layout: 'column',
							border: false,
							anchor: '100%',
							items: [{
								columnWidth: .75,
								layout: 'form',
								items: [
									{xtype: 'textfield',fieldLabel: _('ms2gallery_file_name'),name: 'file',id: this.ident+'-file',anchor: '100%'}
								]
							},{
								columnWidth: .25,
								layout: 'form',
								items: [
									{xtype: 'xcheckbox',fieldLabel: _('ms2gallery_file_active'),name: 'active',id: this.ident+'-active',anchor: '100%', ctCls: 'ms2gallery-cba'}
								]
							}]
						},
						{xtype: 'textfield',fieldLabel: _('ms2gallery_file_title'),name: 'name',id: this.ident+'-name',anchor: '100%'},
						{xtype: 'textfield',fieldLabel: _('ms2gallery_file_alt'),name: 'alt',id: this.ident+'-alt',anchor: '100%'},
						{xtype: 'ms2gallery-combo-tags',fieldLabel: _('ms2gallery_file_tags'),name: 'tags',id: this.ident+'-tags',anchor: '100%', value: config.record['tags']}
					]
				}]
			},{
				layout: 'column',
				border: false,
				anchor: '100%',
				items: [{
					columnWidth: .5,
					layout: 'form',
					defaults: { msgTarget: 'under'},
					border:false,
					items: [
						{xtype: 'textarea',fieldLabel: _('ms2gallery_file_add'),name: 'add',id: this.ident+'-add',anchor: '100%', height: 50}
					]
				},{
					columnWidth: .5,
					layout: 'form',
					defaults: { msgTarget: 'under' },
					border:false,
					items: [
						{xtype: 'textarea',fieldLabel: _('ms2gallery_file_description'),name: 'description',id: this.ident+'-description',anchor: '100%', height: 50}
					]
				}]
			}
		],
		keys: [{key: Ext.EventObject.ENTER,shift: true,fn: this.submit,scope: this}]
	});
	ms2Gallery.window.Image.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.window.Image, MODx.Window);
Ext.reg('ms2gallery-gallery-image', ms2Gallery.window.Image);


ms2Gallery.window.Tags = function(config) {
	config = config || {};
	this.ident = config.ident || Ext.id();

	Ext.applyIf(config, {
		title: _('ms2gallery_file_edit_tags'),
		id: this.ident,
		cls: 'ms2gallery ' + (MODx.modx23 ? 'modx23' : 'modx22'),
		width: 500,
		autoHeight: true,
		url: ms2Gallery.config.connector_url,
		action: 'mgr/gallery/savetags',
		layout: 'form',
		resizable: false,
		maximizable: false,
		minimizable: false,
		fields: [{
			xtype: 'hidden',
			name: 'ids',
			id: this.ident + '-ids',
			value: config.ids,
		},{
			xtype: 'ms2gallery-combo-tags',
			fieldLabel: _('ms2gallery_file_tags'),
			name: 'tags',
			id: this.ident + '-tags',
			anchor: '100%',
			value: config.tags.length > 0
				? config.tags
				: '',
		},{
			xtype: 'displayfield',
			html: '<em>' + _('ms2gallery_file_edit_tags_intro') + '</em>',
			id: this.ident + '-desc',
			hideLabel: true,
		}],
		keys: [{key: Ext.EventObject.ENTER,shift: true,fn: this.submit,scope: this}]
	});
	ms2Gallery.window.Image.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.window.Tags, MODx.Window);
Ext.reg('ms2gallery-gallery-tags', ms2Gallery.window.Tags);
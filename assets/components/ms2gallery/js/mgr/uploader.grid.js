ms2Gallery.grid.Plupload = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: 'ms2gallery-uploader-grid',
		width: '100%',
		height: (config.gridHeight || 200) + 50,
		border: false,
		emptyText: _('ms2gallery_emptymsg'),
		fields: ['id', 'name', 'size', 'status', 'progress'],
		columns:[
			{header: _('ms2gallery_filename'), dataIndex:'name', width:250, id: 'plupload-column-filename'},
			{header: _('ms2gallery_size'), dataIndex:'size', width:100, renderer:Ext.util.Format.fileSize},
			{header: _('ms2gallery_status'), dataIndex:'status', width: 100, renderer:this.statusRenderer},
			{header: _('ms2gallery_progress'), dataIndex:'percent', width: 200, scope:this, renderer:this.progressBarColumnRenderer}
		],
		tbar: [
			{xtype: 'button', id: 'ms2gallery-upload-button-' + config.record.id, text: '<i class="' + (MODx.modx23 ? 'icon icon-' : 'fa fa-') + 'plus"></i> ' + _('ms2gallery_button_upload')},
			{xtype: 'tbspacer',width: 30},
			'->',
			{xtype: 'displayfield',html: '<b>' + _('ms2gallery_source') + '</b>:&nbsp;&nbsp;'},
			{xtype: 'ms2gallery-combo-source',id: 'ms2gallery-resource-source',description: '<b>[[+source]]</b><br />'+_('ms2gallery_source_help'),
				value: config.record.source,
				name: 'media_source',
				hiddenName: 'media_source',
				listeners: {select: {fn: this.sourceWarning, scope: this}}
			},
			'-',
			{xtype: 'button',text: _('ms2gallery_uploads_clear'), handler: function() {this.getStore().removeAll();this.resetUploader();},scope: this}
		],
		listeners:{
			afterrender: {fn: function() {
				this._initUploader();
				var store = this.getStore();
				window.setTimeout(function() {
					store.removeAll();
				}, 100)
			},scope:this}
		}
	});
	ms2Gallery.grid.Plupload.superclass.constructor.call(this,config);

	this.store = new Ext.data.ArrayStore({
		fields: this.config.fields,
		reader: new Ext.data.ArrayReader({idIndex: 0}, this.fileRecord)
	})
};
Ext.extend(ms2Gallery.grid.Plupload,MODx.grid.LocalGrid, {

	uploader: null,
	errors: '',

	sourceWarning: function(combo) {
		var source_id = this.config.record.source;
		var sel_id = combo.getValue();
		if (source_id != sel_id) {
			Ext.Msg.confirm(_('warning'), _('ms2gallery_change_source_confirm'), function(e) {
				if (e == 'yes') {
					combo.setValue(sel_id);
					var f = Ext.getCmp('modx-page-update-resource');
					MODx.activePage.submitForm({
						success: {fn:function(r) {
							var page = MODx.action ? MODx.action['resource/update'] : 'resource/update';
							MODx.loadPage(page, 'id='+r.result.object.id);
						},scope:this}
					});
				}
				else {
					combo.setValue(source_id);
				}
			},this);
		}
	},

	progressBarColumnTemplate: new Ext.XTemplate(
		'<div class="ux-progress-cell-inner ux-progress-cell-inner-center ux-progress-cell-foreground">\
			<div>{value} %</div>\
			</div>\
			<div class="ux-progress-cell-inner ux-progress-cell-inner-center ux-progress-cell-background" style="left:{value}%">\
			<div style="left:-{value}%">{value} %</div>\
		</div>'
	),

	progressBarColumnRenderer:function(value, meta, record, rowIndex, colIndex, store) {
		meta.css += ' x-grid3-td-progress-cell';
		return this.progressBarColumnTemplate.apply({
			value: value
		});
	},

	statusRenderer:function(value, meta, record, rowIndex, colIndex, store) {
		var icon = MODx.modx23
			? 'icon icon-'
			: 'fa fa-';
		switch (value) {
			case 1:
			case 2:
				icon += 'repeat';
				break;
			case 4:
				icon += 'times';
				break;
			case 5:
				icon += 'check';
				break;
			default:
				icon = '';
		}
		var text = _('ms2gallery_status_code_' + value);
		if (icon) {
			text = '<i class="' + icon + '"> ' + text;
		}
		return text;
	},

	updateFile:function(file) {
		var store = this.getStore();
		var storeId = store.find('id',file.id);
		var fileRec = store.getAt(storeId);

		fileRec.set('percent', file.percent);
		fileRec.set('status', file.status);
		fileRec.set('size', file.size);
		fileRec.commit();
	},

	_initUploader: function() {
		this.fileRecord = Ext.data.Record.create(this.config.fields);

		var params = {
			action: 'mgr/gallery/upload',
			id: this.record.id,
			source: this.record.source,
			ctx: 'mgr',
			HTTP_MODAUTH: MODx.siteId
		};

		this.uploader = new plupload.Uploader({
			url: ms2Gallery.config.connector_url + '?' + Ext.urlEncode(params),
			runtimes: 'html5,flash,html4',
			browse_button: 'ms2gallery-upload-button-' + this.record.id,
			container: this.id,
			drop_element: this.config.id,
			multipart: false,
			max_file_size : ms2Gallery.config.media_source.maxUploadSize || 10485760,
			flash_swf_url : ms2Gallery.config.assets_url + 'js/mgr/misc//plupload/plupload.flash.swf',
			filters : [{
				title : "Image files",
				extensions : ms2Gallery.config.media_source.allowedFileTypes || 'jpg,jpeg,png,gif'
			}],
			resize : {
				width : ms2Gallery.config.media_source.maxUploadWidth || 1920,
				height : ms2Gallery.config.media_source.maxUploadHeight || 1080
				//,quality : 100
			}
		});

		var uploaderEvents = ['FilesAdded', 'FileUploaded', 'QueueChanged', 'UploadFile', 'UploadProgress', 'UploadComplete' ];
		Ext.each(uploaderEvents, function (v) {
			var fn = 'on' + v;
			this.uploader.bind(v, this[fn], this);
		}, this);

		this.uploader.init();
	},

	onFilesAdded: function(up, files) {
		this.updateList = true;
	},

	removeFile: function(id) {
		this.updateList = true;
		var f = this.uploader.getFile(id);
		this.uploader.removeFile(f);
	},

	onQueueChanged: function(up) {
		if (this.updateList) {
			if (this.uploader.files.length > 0) {
				var ms2g = this;
				Ext.each(this.uploader.files, function(file, i) {
					var fileRec = new ms2g.fileRecord(file);
					ms2g.store.add(fileRec);
				});
				ms2g.uploader.start();
			} else {
				this.getStore().removeAll();
			}
			up.refresh();
		}
	},

	onUploadFile: function(uploader, file) {
		this.updateFile(file);
	},

	onUploadProgress: function(uploader, file) {
		this.updateFile(file);
	},

	onUploadComplete: function(uploader, files) {
		if (this.errors.length > 0) {
			this.fireAlert();
		}
		Ext.getCmp('ms2gallery-images-panel').view.getStore().reload();
		this.resetUploader();
	},

	onFileUploaded: function(uploader, file, xhr) {
		var r = Ext.util.JSON.decode( xhr.response );
		if(!r.success) {
			this.addError(file.name, r.message);
		}
		this.updateFile(file);
	},

	resetUploader: function() {
		this.uploader.files = {};
		this.uploader.destroy();
		this.errors = '';
		this._initUploader();
	},

	addError: function(file, message) {
		this.errors += file + ': ' + message + '<br/>';
	},

	fireAlert: function() {
		MODx.msg.alert(_('ms2gallery_errors'), this.errors);
	},

});
Ext.reg('ms2gallery-uploader-grid',ms2Gallery.grid.Plupload);
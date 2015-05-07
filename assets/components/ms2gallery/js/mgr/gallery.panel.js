ms2Gallery.panel.Gallery = function(config) {
	config = config || {};

	Ext.apply(config,{
		id: 'ms2gallery-page',
		baseCls: 'x-panel ms2gallery ' + (MODx.modx23 ? 'modx23' : 'modx22'),
		items: [{
			border: false,
			baseCls: 'panel-desc',
			html: '<p>' + _('ms2gallery_introtext') + '</p>'
		},{
			border: false,
			style: {padding: '5px', overflow: 'hidden'},
			layout: 'anchor',
			items: [{
				border: false,
				xtype: 'ms2gallery-uploader-grid',
				id: 'ms2gallery-uploader-grid',
				record: config.record,
				gridHeight: 150
			},{
				border: false,
				xtype: 'ms2gallery-images-panel',
				id: 'ms2gallery-images-panel',
				cls: 'modx-pb-view-ct',
				resource_id: config.record.id,
				pageSize: config.pageSize || MODx.config.ms2gallery_page_size
			}]
		}]
	});
	ms2Gallery.panel.Gallery.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery.panel.Gallery,MODx.Panel);
Ext.reg('ms2gallery-page',ms2Gallery.panel.Gallery);
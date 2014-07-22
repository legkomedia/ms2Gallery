<input id="tv{$tv->id}" type="hidden" name="tv{$tv->id}" value="{if $tv->value ne ''}{$tv->value}{else}{$source_id}{/if}"/>

<script src="/assets/components/ms2gallery/js/mgr/ms2gallery.js" type="text/javascript"></script>
<script src="/assets/components/ms2gallery/js/mgr/misc/ms2.combo.js" type="text/javascript"></script>
<script src="/assets/components/ms2gallery/js/mgr/misc/ms2.utils.js" type="text/javascript"></script> {*check this*}

<script src="/assets/components/ms2gallery/js/mgr/misc/plupload/plupload.full.js" type="text/javascript"></script>

<script src="/assets/components/ms2gallery/js/mgr/misc/ext.ddview.js" type="text/javascript"></script>
{*<link href="/assets/components/ms2gallery/css/mgr/bootstrap.min.css" rel="stylesheet" type="text/css" />*}

<script type="text/javascript">
	// <![CDATA[

	ms2Gallery.config = {$config};
	ms2Gallery.config.connector_url = '{$connector_url}';
	ms2Gallery.config.media_source = {$media_source};

	MODx.on("ready",function() {
		MODx.addTab("modx-resource-tabs",{
			title: _('ms2gallery'),
			id: 'ms2gallery-tab',
			width: '95%'
		});
		Ext.getCmp('ms2gallery-tab').add({
			xtype: 'ms2gallery-resource-gallery'
			,value: _('ms2gallery_disabled_while_creating')
			,id: 'ms2gallery-resource-gallery'
			,record: {
				id: {$resource_id}
				,source: {if $tv->value ne ''}{$tv->value}{else}{$source_id}{/if}
			}
			,targetTv: 'tv{$tv->id}'
			,pageSize: ms2Gallery.config.pageSize
			,width: '95%'
			,listeners: {
				afterrender: function(){
					Ext.get('tv{$tv->id}-tr').insertBefore(Ext.get('ms2gallery-resource-gallery'));
				}
			}
		});
	});

	// ]]>
</script>
<script src="/assets/components/ms2gallery/js/mgr/gallery.panel.js" type="text/javascript"></script>
Ext.override(MODx.panel.Resource, {

	originals: {
		getFields: MODx.panel.Resource.prototype.getFields
	},

	getFields: function(config) {
		var originals = this.originals.getFields.call(this, config);

		for (var i in originals) {
			if (!originals.hasOwnProperty(i)) {
				continue;
			}
			var item = originals[i];

			if (item.id == 'modx-resource-tabs') {
				if (typeof config.record.properties['ms2gallery'] != 'undefined') {
					item.items.push({
						xtype: "ms2gallery-page",
						id: "ms2gallery-page",
						title: _("ms2gallery"),
						record: {
							id: config.record.id,
							source: config.record.properties['ms2gallery']['media_source']
						}
					});
				}
				else {
					console.log('Could not find ms2gallery properties in resource');
				}
			}
		}

		return originals;
	}

});

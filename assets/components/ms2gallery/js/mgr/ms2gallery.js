var ms2Gallery = function(config) {
	config = config || {};
    ms2Gallery.superclass.constructor.call(this,config);
};
Ext.extend(ms2Gallery,Ext.Component,{
	page:{},window:{},grid:{},tree:{},panel:{},combo:{},config:{},view:{},keymap:{}, plugin:{}
});
Ext.reg('ms2gallery',ms2Gallery);

ms2Gallery = new ms2Gallery();

ms2Gallery.PanelSpacer = { html: '<br />' ,border: false, cls: 'ms2gallery-panel-spacer' };
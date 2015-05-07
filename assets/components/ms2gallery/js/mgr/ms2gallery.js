var ms2Gallery = function(config) {
	config = config || {};
	ms2Gallery.superclass.constructor.call(this,config);
};

Ext.extend(ms2Gallery,Ext.Component,{
	page:{},window:{},grid:{},tree:{},panel:{},combo:{},config:{},view:{},keymap:{},plugin:{},utils:{},
});

Ext.reg('ms2gallery', ms2Gallery);
ms2Gallery = new ms2Gallery();
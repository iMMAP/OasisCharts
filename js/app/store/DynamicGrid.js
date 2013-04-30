Ext.define('Immap.store.DynamicGrid', {
    extend: 'Ext.data.Store',
    model: 'Immap.model.Dynamic',
    autoLoad: false,
    proxy:{
        type: 'ajax',
        url: 'immap_controller_chart.php',
        actionMethods: {
            read: 'POST'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
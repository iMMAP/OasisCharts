Ext.define('Immap.store.Filter', {
    extend: 'Ext.data.Store',
    model: 'Immap.model.Filter',
    autoLoad: false,
    proxy: {
        type: 'ajax',
        url: 'immap_controller_chart.php',
        actionMethods: {
            read: 'POST'
        },
        reader: {
            type: 'json',
            root: 'data'
        }
    }
});
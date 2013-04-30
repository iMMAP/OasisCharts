Ext.define('Immap.store.ReportGroup', {
    extend: 'Ext.data.Store',
    model: 'Immap.model.ReportGroup',
    autoLoad: false,
    autoSync: false,
    proxy: {
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
    },
    listeners : {
        load : {
            fn : function(){
                var panelGroupQuery = Ext.getCmp('PanelGroupQuery');
                panelGroupQuery.getSelectionModel().select(0);
            },
            scope : this
        },
        exception : {
            fn : function( err ){
            //Ext.Msg.alert('App',"Oh Crap!"+err);
            },
            scope : this
        }
    }
});
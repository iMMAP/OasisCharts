Ext.define('Immap.store.QueryReport', {
    extend: 'Ext.data.Store',
    model: 'Immap.model.QueryReport',
    autoLoad: false,
    proxy: {
        type:'ajax',
        url:'immap_controller_chart.php',
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
                var panelQuery = Ext.getCmp('PanelQuery');
                if (panelQuery) {
                    panelQuery.getSelectionModel().select(0);
                }
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
}



Ext.define('Immap.store.Report', {
    extend: 'Ext.data.Store',
    model: 'Immap.model.Report',
    autoLoad: true,
    autoSync: true,
    proxy: {
        type: 'ajax',
        url: 'immap_controller_chart.php',
        actionMethods: {
            read: 'POST'
        },
        extraParams : {
            request:'get_dddname'
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
                var panelReport = Ext.getCmp('PanelReport');
                if (panelReport) {
                    panelReport.getSelectionModel().select(0);
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
});
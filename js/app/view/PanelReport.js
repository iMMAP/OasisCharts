//app/view/PanelReport.js 
Ext.define('Immap.view.PanelReport', {
    extend:'Ext.grid.Panel',
    id:'PanelReport',
    alias:'widget.panelreport',
    title:'Reports',
    region:'north',
    store:'Immap.store.Report',
    width: 200,
    minSize:100,
    height: '20%',
    split: true,
    autoScroll: true,
    initComponent: function () {
        this.columns = [
        {
            header: 'DDDefName', 
            dataIndex: 'DDDefName',  
            hidden:true, 
            hideable:false
        },
        {
            header: 'Module', 
            dataIndex: 'Name', 
            hideable: false, 
            flex:1
        }
        ];
        this.callParent(arguments);
        this.getSelectionModel().on('selectionchange', this.onSelectChange, this);
    },
    onSelectChange: function(selModel, selectedRecord){
        if (selectedRecord.length) {
            var pcenter = Ext.getCmp('pcenter');
            var dynamicgrid = Ext.getCmp('dynamicgrid');
            dynamicgrid.filters.clearFilters();
            pcenter.removeAll();
            var storeDynamicGrid = Ext.getCmp('Immap.store.DynamicGrid')
            var storeQueryReport = Ext.getCmp('Immap.store.QueryReport')
            var storeReportGroup = Ext.getCmp('Immap.store.ReportGroup')
            if (storeDynamicGrid) {
                storeDynamicGrid.removeAll();
                dynamicgrid.reconfigure(storeDynamicGrid, []);
                storeQueryReport.removeAll();
                        
                storeReportGroup.getProxy().extraParams=  {
                    'request' : 'get_chart_report_group',
                    'dddname': selectedRecord[0].get('DDDefName')
                }
                storeReportGroup.load();
                cboFilter.setValue("");
                cboFilter.hide();
            }
        }
    }
});  
<!DOCTYPE html>
<?php
include_once "immap_service_chart.php";
include_once "immap_service_login.php";

header('Content-type: text/html; charset=utf-8');
if (is_login() === FALSE) {
    header("Location: immap_view_login.php");
}
?>

<html>
    <head>
        <title>OASIS Online Charting</title>
        <link rel="stylesheet" type="text/css" href="ext-4.1.1a/resources/css/ext-all-gray.css" />
        <link rel="stylesheet" type="text/css" href="ext-4.1.1a/ux/grid/css/GridFilters.css" />
        <link rel="stylesheet" type="text/css" href="ext-4.1.1a/ux/grid/css/RangeMenu.css" />
        <script type="text/javascript" src="ext-4.1.1a/ext-all.js"></script>
        <script type="text/javascript" src="ext-4.1.1a/ux/chart/HighChart.js"></script>
        <script type="text/javascript" src="js/jquery-1.8.1.min.js"></script>
        <script type="text/javascript" src="js/highcharts.src.js"></script>
        <script type="text/javascript" src="js/highcharts-more.js"></script>
        <script type="text/javascript" src="js/highcharts-exporting.js"></script>
        <script>
            //Loader is commented because all code is on page.
            //Uncomment it in your app.
            //Ext.Loader.setConfig({ enabled: true });
            //
            var guid = null;
            var pageParameters  = Ext.urlDecode(window.location.search.substring(1));
            Ext.Loader.setPath('Ext.ux', 'ext-4.1.1a/ux');
            Ext.Loader.setPath('Chart.ux', 'ext-4.1.1a/ux');
            Ext.require('Chart.ux.HighChart');
            Ext.require([
                'Ext.ux.grid.FiltersFeature',
                'Ext.chart.*'
            ]);
            //app/model/Report.js
            Ext.define('Immap.model.Report', {
                extend: 'Ext.data.Model',
                fields: [
                    {name:'DDDefName',type: 'string'},
                    {name:'Name',type: 'string'}
                ]
            });
            
            Ext.define('Immap.model.ReportGroup', {
                extend: 'Ext.data.Model',
                fields: [
                    {name:'reportgroup',type: 'string'}
                ]
            });
            
            //app/model/QueryReport.js 
            Ext.define('Immap.model.QueryReport', {
                extend: 'Ext.data.Model',
                fields: [
                    {name:'GUID1',type:'string'},
                    {name:'QueryName',type:'string'},
                    {name:'IsFilter',type:'boolean'}
                ]
            });
    
            //app/model/Dynamic.js 
            Ext.define('Immap.model.Dynamic', {
                extend: 'Ext.data.Model'
            });
            
            //app/model/DynamicChart.js 
            Ext.define('Immap.model.DynamicChart', {
                extend: 'Ext.data.Model'
            });
            
            Ext.define('Immap.model.Filter', {
                extend: 'Ext.data.Model',
                fields: [
                    {name: 'filter',type: 'string'}
                ]
            });
            //            
            var storeReport = Ext.create('Ext.data.Store', {
                model: 'Immap.model.Report',
                autoLoad:true,
                proxy: {
                    type: 'ajax',
                    url: 'immap_controller_chart.php',
                    actionMethods: {
                        read: 'POST'
                    },
                    extraParams : {
                        request:'get_dddname',
                        db:pageParameters.db
                    },
                    reader: {
                        type: 'json',
                        root: 'data',
                        successProperty: 'success'
                    }
                },listeners : {
                    load : {
                        fn : function( store, records, successful, eOpts  ){
                            if (successful) {
                                //var panelReport = Ext.getCmp('panelreport');
                                //if (panelReport) {
                                //    panelReport.getSelectionModel().select(0);
                                //}
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
            
            var storeReportGroup = Ext.create('Ext.data.Store', {
                model: 'Immap.model.ReportGroup',
                autoLoad:false,
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
                },listeners : {
                    load : {
                        fn : function( store, records, successful, eOpts ){    
                            if (successful) {
                                var panelGroupQuery = Ext.getCmp('PanelGroupQuery');
                                panelGroupQuery.getSelectionModel().select(0);
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
                                
            var storeQueryReport = Ext.create('Ext.data.Store', {
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
                },listeners : {
                    load : {
                        fn : function( store, records, successful, eOpts ){
                            if (successful) {
                                var panelQuery = Ext.getCmp('PanelQuery');
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
            });
            
            var storeFilter = Ext.create('Ext.data.Store', {
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
            
            var cboFilter = Ext.create('Ext.form.field.ComboBox', {
                displayField:'filter',
                width: 300,
                labelWidth: 70,
                fieldLabel:'Report Filter',
                store: storeFilter,
                queryMode:'local',
                hidden:true,
                typeAhead:true,
                listeners: {
                    select: function(combo,records,opts) {
                       // showDataview();
                        showDataview(pageParameters.chartdd,pageParameters.chartguid);
                    }
                }    
            });
                
            var storeDynamicGrid = Ext.create('Ext.data.Store', {
                autoLoad:false,
                model: 'Immap.model.Dynamic',
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
            
            var storeChartTypes = Ext.create('Ext.data.Store', {
                fields:['value','display'],
                data : [
                    {"value":"column","display":"Column"},
                    {"value":"bar","display":"Bar"},
                    {"value":"spline","display":"Spline"},
                    {"value":"pie","display":"Pie"}
                ]
            });
            
            var storeSeriedAlignment = Ext.create('Ext.data.Store', {
                fields:['value','display'],
                data : [
                    {"value":"left","display":"Left"},
                    {"value":"right","display":"Right"},
                    {"value":"top","display":"Top"},
                    {"value":"buttom","display":"Buttom"}
                ]
            });
            
            var cboChartType = Ext.create('Ext.form.field.ComboBox', {
                id:'cboChartType',
                name:'cboChartType',
                displayField:'display',
                valueField:'value',
                fieldLabel:'Type',
                store: storeChartTypes,
                anchor: '100%',
                queryMode:'local'
            });
            
            var cboSeriedAlignment = Ext.create('Ext.form.field.ComboBox', {
                id:'cboSeriedAlignment',
                name:'cboSeriedAlignment',
                displayField:'display',
                valueField:'value',
                fieldLabel:'Seried Alignment',
                store: storeSeriedAlignment,
                anchor: '100%',
                queryMode:'local'
            });      
            Ext.define('Immap.view.frmChartSetting', {
                extend: 'Ext.form.Panel',
                name:'frmChartSetting',
                id:'frmChartSetting',
                anchor:'100%',
                autoScroll:true,
                bodyPadding: 10,
                title: 'Chart Setting',
                initComponent: function() {
                    var me = this;
                    Ext.applyIf(me, {
                        items: [
                            {
                                xtype: 'fieldset',
                                title: 'Chart',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        anchor: '100%',
                                        name: 'txtChartTitle',
                                        fieldLabel: 'Title'
                                    },
                                    {
                                        xtype: 'textfield',
                                        anchor: '100%',
                                        name: 'txtChartSubTitle',
                                        fieldLabel: 'Sub Title'
                                    },
                                    cboChartType,
                                    {
                                        xtype: 'checkboxfield',
                                        anchor: '100%',
                                        fieldLabel: '',
                                        name: 'chkStacked',
                                        fieldLabel: 'Stacked / Side By Side'
                                    }
                                ]
                            },
                            {
                                xtype: 'fieldset',
                                title: 'Legend',
                                items: [cboSeriedAlignment]
                            },
                            {
                                xtype: 'fieldset',
                                title: 'X Axis',
                                items: [
                                    {
                                        xtype: 'numberfield',
                                        anchor: '100%',
                                        name: 'nbfXAngle',
                                        step:5,
                                        maxValue:360,
                                        minValue:-360,
                                        fieldLabel: 'Angle'
                                    },
                                    {
                                        xtype: 'textfield',
                                        anchor: '100%',
                                        name: 'txtXTitle',
                                        fieldLabel: 'X Axis Title'
                                    },
                                ]
                            },
                            {
                                xtype: 'fieldset',
                                title: 'Y Axis',
                                items: [
                                    {
                                        xtype: 'numberfield',
                                        anchor: '100%',
                                        name: 'nbfYAngle',
                                        step:5,
                                        maxValue:360,
                                        minValue:-360,
                                        fieldLabel: 'Angle'
                                    },
                                    {
                                        xtype: 'textfield',
                                        anchor: '100%',
                                        name: 'txtYTitle',
                                        fieldLabel: 'Y Axis Title'
                                    },
                                ]
                            }
                        ],
                        buttons: [
                            {
                                text: 'Save',
                                handler: function(){
                                    //var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Saving..."});
                                    var myMask = new Ext.LoadMask(Ext.getCmp('pcenter').el, {msg:"Saving..."});
                                    myMask.show();
                                    var panelReport = Ext.getCmp('PanelReport');
                                    var panelQuery = Ext.getCmp('PanelQuery')
                                    var panelReportRecords = panelReport.getSelectionModel().getSelection();
                                    var panelQueryRecords= panelQuery.getSelectionModel().getSelection();
                                    var chartconfigsave = getChartConfig();
                                    var dddname,guid1;
                                    if (typeof pageParameters.chartdd === "undefined")  {
                                        dddname = panelReportRecords[0].get('DDDefName'); 
                                    } else{ 
                                        dddname = pageParameters.chartdd; 
                                    }
                                        
                                    if (typeof pageParameters.chartguid === "undefined")  {
                                        guid1 = panelQueryRecords[0].get('GUID1');
                                    }else{ 
                                        guid1 = pageParameters.chartguid; 
                                    }

                                    Ext.Ajax.request({
                                        method:'post',
                                        url: 'immap_controller_chart.php',
                                        params :{
                                            'request' : 'set_chart_config',
                                            'chartconfig': chartconfigsave,
                                            'dddname': dddname,
                                            'guid1': guid1,
                                            'db':pageParameters.db
                                        },
                                        callback: function(opt,success,response){
                                            myMask.hide();
                                            removeChart();
                                            reloadChart();
                                        },
                                        success: function( response, request ){
                                            myMask.hide();  
                                        }, 
                                        failure: function () {
                                            myMask.hide();
                                            Ext.MessageBox.alert('Status', 'fail to saved.');
                                        }
                                    });
                                }
                            },
                            {   
                                text: 'Apply without saving',
                                handler: function(){
                                    var chartconfigsave = getChartConfig();
                                    removeChart();
                                    reloadChart(chartconfigsave);
                                }
                            }
                        ]
                    });
                    me.callParent(arguments);
                }
            });
            Ext.define('Immap.view.PanelReport', {
                extend:'Ext.grid.Panel',
                id:'PanelReport',
                alias:'widget.panelreport',
                title:'Reports',
                region:'north',
                store:storeReport,
                width: 200,
                minSize:100,
                height: '20%',
                split: true,
                stateful : false,
                autoScroll: true,
                initComponent: function () {
                    this.columns = [
                        { header: 'DDDefName', dataIndex: 'DDDefName',  hidden:true, hideable:false },
                        { header: 'Module', dataIndex: 'Name', hideable: false, flex:1}
                    ];
                    this.callParent(arguments);
                    this.getSelectionModel().on('selectionchange', this.onSelectChange, this);
                },
                onSelectChange: function(selModel, selectedRecord){
                    if (selectedRecord.length) {
                        var pcenter = Ext.getCmp('pcenter');
                        var dynamicgrid = Ext.getCmp('dynamicgrid');
                        dynamicgrid.filters.clearFilters();
                        removeChart();
                        storeDynamicGrid.removeAll();
                        dynamicgrid.reconfigure(storeDynamicGrid, []);
                        storeQueryReport.removeAll();
                        storeReportGroup.getProxy().extraParams=  {
                            'request' : 'get_chart_report_group',
                            'dddname': selectedRecord[0].get('DDDefName'),
                            'db':pageParameters.db
                        }
                        storeReportGroup.load();
                        cboFilter.setValue("");
                        cboFilter.hide();
                    }
                },listeners : {
                    render : function(grid) {
                        grid.expand();
                    }

                } 
            });  
            
            Ext.define('Immap.view.PanelGroupQuery', {
                extend:'Ext.grid.Panel',
                id:'PanelGroupQuery',
                alias:'widget.panelgroupquery',
                region:'center',
                store:storeReportGroup,
                minSize:100,
                width: 200,
                height: '20%',
                split: true,
                autoScroll: true,
                initComponent: function () {
                    this.columns = [
                        { header: 'Report Group', dataIndex: 'reportgroup', hidden:false, hideable:false,flex:1 }
                    ];
                    this.callParent(arguments);
                    this.getSelectionModel().on('selectionchange', this.onSelectChange, this);
                },
                onSelectChange: function(selModel, selectedRecord){
                    if (selectedRecord.length) {
                        //var pcenter = Ext.getCmp('pcenter');
                        removeChart();
                        var dynamicgrid = Ext.getCmp('dynamicgrid');
                        var panelReport = Ext.getCmp('PanelReport');
                        var rec = panelReport.getSelectionModel().getSelection();
                        dynamicgrid.filters.clearFilters();
                        storeDynamicGrid.removeAll();
                        dynamicgrid.reconfigure(storeDynamicGrid, []);
                        storeQueryReport.getProxy().extraParams=  {
                            'request' : 'get_query_name',
                            'dddname': rec[0].get('DDDefName'),
                            'reportgroup': selectedRecord[0].get('reportgroup'),
                            'db':pageParameters.db
                        }
                        storeQueryReport.load();
                        cboFilter.setValue("");
                        cboFilter.hide();
                    }
                }
            });  
          
            Ext.define('Immap.view.PanelQuery', {
                extend:'Ext.grid.Panel',
                id:'PanelQuery',
                alias:'widget.panelquery',
                region:'south',
                minSize:100,
                height: '60%',
                split: true,
                autoScroll:true,
                store: storeQueryReport,
                initComponent: function () {
                    this.columns = [
                        { header: 'GUID1', dataIndex: 'GUID1', hidden:true, hideable: false},
                        { header: 'Report', dataIndex: 'QueryName', hideable: false,flex:1},
                        { header: 'IsFilter', dataIndex: 'IsFilter', hidden:true, hideable: false}
                        
                    ];
                    this.callParent(arguments);
                    this.getSelectionModel().on('selectionchange', this.onSelectChange, this);
        
                },
                onSelectChange: function(selModel, selectedRecord){
                    if (selectedRecord.length) {
                        cboFilter.show(); 
                        var panelReport = Ext.getCmp('PanelReport');
                        var dynamicgrid = Ext.getCmp('dynamicgrid');
                        dynamicgrid.filters.clearFilters();
                        var rec = panelReport.getSelectionModel().getSelection();
                        if (selectedRecord[0].get('IsFilter') == true) {
                            storeFilter.getProxy().extraParams=  {
                                'request' : 'get_filter',
                                'dddname': rec[0].get('DDDefName'),
                                'guid1': selectedRecord[0].get('GUID1'),
                                'db':pageParameters.db
                            }
                            storeFilter.load();
                            cboFilter.setValue("  -- ALL --");
                        } else {
                            storeFilter.removeAll();
                            cboFilter.setValue("  -- ALL --");
                            cboFilter.hide();
                        }
                        //showDataview();
                        showDataview(pageParameters.chartdd,pageParameters.chartguid);
                    }
                }
            });
            
            var btnShowView = Ext.create('Ext.Button', {
                text:'Refresh',
                tooltip:'Refresh Chart and Data view',
                listeners:{
                    click: function() {
                        showDataview(pageParameters.chartdd,pageParameters.chartguid);
                    }
                }
            });
            var btnClearFilter = Ext.create('Ext.Button', {
                text:'Clear Filter Data',
                tooltip:'Clear Filter Data',
                listeners:{
                    click: function() { 
                        var dynamicgrid = Ext.getCmp('dynamicgrid');
                        dynamicgrid.filters.clearFilters();
                        showDataview(pageParameters.chartdd,pageParameters.chartguid);
                    }
                }
            });          
            Ext.define("Immap.config.HighChart",{
                config : { 
                    'chartConfig':''
                },
                constructor : function (cfg) {
                    this.initConfig (cfg);
                }
            });
            
            var hcConfg = null;
                
            function reloadChart(pCharConfig) {
                var panelReport = Ext.getCmp('PanelReport');
                var panelQuery = Ext.getCmp('PanelQuery')
                var panelReportRecords = panelReport.getSelectionModel().getSelection();
                var panelQueryRecords= panelQuery.getSelectionModel().getSelection();
                var pcenter = Ext.getCmp('pcenter');
                var myMask = new Ext.LoadMask(pcenter.el, {msg:"Loading..."});
                var xParams,dddname,guid1;

                //if ((pageParameters.rlhidden === "true") && (pageParameters.gridhidden === "true")) {
                if (typeof pageParameters.chartdd !== "undefined" && typeof pageParameters.chartguid !== "undefined")  {
                    dddname = pageParameters.chartdd;
                    guid1 = pageParameters.chartguid;
                } else {
                    dddname = panelReportRecords[0].get('DDDefName');
                    guid1 = panelQueryRecords[0].get('GUID1');
                }
                //} 
                if (typeof pCharConfig === "undefined")  {
                    xParams = {
                        request : 'get_chart_config',
                        'dddname': dddname,
                        'guid1': guid1,
                        'width':pcenter.getWidth()-20,
                        'height':pcenter.getHeight()-30,
                        'db':pageParameters.db
                    };
                } else {
                    xParams = {
                        request : 'get_chart_config',
                        'dddname': dddname,
                        'guid1': guid1,
                        'chartconfig': pCharConfig,
                        'width':pcenter.getWidth()-20,
                        'height':pcenter.getHeight()-30,
                        'db':pageParameters.db
                    };
                }
                
                myMask.show();
                Ext.Ajax.request({
                    method:'post',
                    url: 'immap_controller_chart.php',
                    params :xParams,
                    success: function( response, request ){
                    },
                    failure: function ( response, request ) {
                        myMask.hide();
                    },
                    callback: function(opt,success,response){                        
                        var mainChart = Ext.getCmp('main_chart');
                        var config = Ext.create('Immap.config.HighChart');
                        config.setChartConfig(Ext.decode(response.responseText));
                        hcConfig = config.getChartConfig();
                        //console.log(hcConfig);
                        hcConfig.id = 'main_chart';
                        hcConfig.store = storeDynamicGrid
                        hcConfig.width = pcenter.getWidth()-20;
                        hcConfig.height = pcenter.getHeight()-30;
                        mainChart = Ext.widget('highchart',hcConfig );
                        var ranNum = Math.floor((Math.random()*1000000)+1);
                        pcenter.add(new Ext.Panel({
                            id: 'tab_chart'+ranNum,
                            title:'Chart View', //'Chart View [' + panelQueryRecords[0].get('QueryName') +']',
                            items:[{xtype:mainChart}],
                            autoScroll:true,
                            closable: true
                        }));
                        setChartConfig(hcConfig);
                        myMask.hide();
                        pcenter.items.each(function(tabPanel){
                            if (tabPanel.getId()==='frmChartSetting') {
                                tabPanel.show();
                            }
                        });
                        var tab_chart = Ext.getCmp('tab_chart'+ranNum);
                        pcenter.setActiveTab(tab_chart);
                    } 
                });
            }
            
            function setChartConfig(hcConfig) 
            {
                var  frmChartSetting = Ext.getCmp('frmChartSetting');
                frmChartSetting.query('textfield[name="txtChartTitle"]')[0].setValue(hcConfig.chartConfig.title.text);
                frmChartSetting.query('textfield[name="txtChartSubTitle"]')[0].setValue(hcConfig.chartConfig.subtitle.text);
                frmChartSetting.query('combobox[name="cboChartType"]')[0].setValue(hcConfig.dbSetting.chartType);
                frmChartSetting.query('combobox[name="cboSeriedAlignment"]')[0].setValue(hcConfig.dbSetting.legend);
                frmChartSetting.query('checkboxfield[name="chkStacked"]')[0].setValue(hcConfig.dbSetting.stacking);
                frmChartSetting.query('numberfield[name=nbfXAngle]')[0].setValue(hcConfig.dbSetting.xAxisLabelAngle);
                frmChartSetting.query('numberfield[name=nbfYAngle]')[0].setValue(hcConfig.dbSetting.yAxisLabelAngle);
                frmChartSetting.query('textfield[name="txtXTitle"]')[0].setValue(hcConfig.chartConfig.xAxis.title.text);
                frmChartSetting.query('textfield[name="txtYTitle"]')[0].setValue(hcConfig.chartConfig.yAxis.title.text);
            }
            
            function getChartConfig() 
            {
                var frmChartSetting = Ext.getCmp('frmChartSetting');
                var chartconfigsave = {
                    "chartType": frmChartSetting.query('combobox[name="cboChartType"]')[0].getValue(),
                    "chartStack": String(frmChartSetting.query('checkboxfield[name="chkStacked"]')[0].getValue()),
                    "chartTitle":frmChartSetting.query('textfield[name="txtChartTitle"]')[0].getValue(),
                    "subtitleText":frmChartSetting.query('textfield[name="txtChartSubTitle"]')[0].getValue(),
                    "linkText":'immap.org',
                    "href":'http://www.immap.org/',
                    "legend":frmChartSetting.query('combobox[name="cboSeriedAlignment"]')[0].getValue(),
                    "xAxisLabelTitle":String(frmChartSetting.query('textfield[name="txtXTitle"]')[0].getValue()),
                    "xAxisLabelAngle":String(frmChartSetting.query('numberfield[name=nbfXAngle]')[0].getValue()),
                    "yAxisLabelTitle":String(frmChartSetting.query('textfield[name="txtYTitle"]')[0].getValue()),
                    "yAxisLabelAngle":String(frmChartSetting.query('numberfield[name=nbfYAngle]')[0].getValue())
                };
                return Ext.encode(chartconfigsave);
            }
         
            function showDataview(dddname,guid1) {
                var panelReport = Ext.getCmp('PanelReport');
                var panelQuery = Ext.getCmp('PanelQuery');
                var panelReportRecords = panelReport.getSelectionModel().getSelection();
                var panelQueryRecords= panelQuery.getSelectionModel().getSelection();
                var XxXxX=cboFilter.getValue();
                if (typeof dddname === "undefined")  {
                    dddname = panelReportRecords[0].get('DDDefName');
                }
                if (typeof guid1 === "undefined")  {
                    guid1 = panelQueryRecords[0].get('GUID1');
                }
                removeChart();
                storeDynamicGrid.getProxy().extraParams = { 
                    request : 'get_datagridview',
                    'dddname': dddname,
                    'guid1': guid1,
                    'XxXxX': XxXxX,
                    'db':pageParameters.db
                }
                storeDynamicGrid.load({
                    scope: this,
                    failure: function ( response, request ) {
                    },
                    callback: function(records, operation, success) {
                        if (success) {
                            reloadChart();
                        } else {;
                        }
                    }
                });
            }
            
            function showDataviewBydDddnameAndGuid(dddname,guid1) {
                var XxXxX=cboFilter.getValue();
                removeChart();
                storeDynamicGrid.getProxy().extraParams = { 
                    request : 'get_datagridview',
                    'dddname': dddname,
                    'guid1': guid1,
                    'XxXxX': XxXxX,
                    'db':pageParameters.db
                }
                storeDynamicGrid.load({
                    scope: this,
                    failure: function ( response, request ) {
                    },
                    callback: function(records, operation, success) {
                        if (success) {
                            reloadChart();
                        } else {;
                        }
                    }
                });
            }
            
            
            
            function refreshChart() 
            {
                var mainChart = Ext.getCmp('main_chart');
                if (mainChart) {
                    mainChart.refresh()
                }
            }  
            function removeChart()
            {
                var pcenter = Ext.getCmp('pcenter');
                pcenter.items.each(function(tabPanel){
                    if (tabPanel.getId()!=='frmChartSetting') {
                        pcenter.remove(tabPanel);
                    } else {
                        pcenter.setActiveTab(tabPanel);
                        tabPanel.hide();
                        
                    }
                });
                var mainChart = Ext.getCmp('main_chart');
                if (mainChart) {
                    mainChart.destroy();
                }
                
            }
            var countsort = 0;
            var encode = false;
            var local = true;
            var filters = {
                ftype: 'filters',
                encode: encode,
                local: local
            };

            //app/view/Dynamicgrid.js 
            Ext.define('Immap.view.Dynamicgrid', {
                extend: 'Ext.grid.Panel',
                id: 'dynamicgrid',
                alias: 'widget.dynamicgrid',
                region:'south',
                height: '30%',
                autoScroll:true,
                split:true,
                features: [filters],
                initComponent: function () {
                    this.tbar = [cboFilter,btnShowView,'-',btnClearFilter,];
                    this.rowNumberer = true;
                    this.store=storeDynamicGrid;
                    this.columns = [];
                    this.callParent(arguments);
                },
                storeLoad: function()
                {   
                    if(typeof this.store.proxy.reader.jsonData !== 'undefined') {
                        if(typeof(this.store.proxy.reader.jsonData.columns) === 'object') {
                            var columns = [];
                            if (this.rowNumberer) {
                                columns.push(Ext.create('Ext.grid.RowNumberer'));
                            }
                            Ext.each(this.store.proxy.reader.jsonData.columns, function(column){
                                columns.push(column);
                            });
                            this.reconfigure(this.store, columns);
                        }
                    }
                },
                onRender: function(ct, position){
                    Immap.view.Dynamicgrid.superclass.onRender.call(this, ct, position);
                    this.store.on('load', this.storeLoad, this);
                },
                listeners: {
                    sortchange: {
                        fn: function(header, column, direction, eOpts) {
                            if (countsort===0) {
                                refreshChart();
                                countsort = countsort + 1;
                            } else {
                                countsort = 0;
                            }
                        }
                    },
                    afterfilterupdate: {
                        fn: function(xthis, filter) {
                            refreshChart();
                        }
                    },
                    filterupdate :{
                        fn: function(xthis, filter) {

                        }
                    }
                }
            });
            
            //Chart panel
            //app/view/Panelcenter.js 
            Ext.define('Immap.view.Panelcenter', {
                extend: 'Ext.tab.Panel',
                alias: 'widget.pcenter',
                id:'pcenter',
                region:'center',
                height: '60%',
                split: true,
                autoScroll:false
            });           
            //app/view/Viewport.js 
            Ext.define('Immap.view.Viewport', {
                extend: 'Ext.container.Viewport',
                layout: 'border',
                requires: [
                    'Immap.view.Panelcenter',
                    'Immap.view.PanelReport',
                    'Immap.view.PanelQuery',
                    'Immap.view.Dynamicgrid',
                    'Immap.config.HighChart',
                    'Immap.view.frmChartSetting'
                ],
                initComponent: function () {
                    Ext.apply(this, {
                        items:[ 
                            {
                                layout:'border',
                                region: 'center',
                                border: false,
                                split:true,
                                autoScroll:true,
                                margins: '0 5 5 5',
                                items: [{xtype:'pcenter'},{xtype:'dynamicgrid'}]

                            },
                            {
                                layout: 'border',
                                title: '',
                                id: 'layout-query',
                                region: 'west',
                                border: false,
                                split:true,
                                margins: '2 0 5 5',
                                width: 200,
                                minSize: 200,
                                maxSize: 500,
                                items: [{xtype:'panelreport'},{xtype:'panelgroupquery'},{xtype:'panelquery'}],
                                listeners: {
                                    render: function(panel) {
                                        panel.expand()
                                    }
                                }
                            }
                        ]
                    });
                    this.callParent(arguments);
                }
            });
            
            //app/controller/charts.js
            Ext.define('Immap.controller.charts', {
                extend: 'Ext.app.Controller',
                views: [
                    'Immap.view.Panelcenter',
                    'Immap.view.Dynamicgrid',
                    'Immap.view.PanelReport',
                    'Immap.view.PanelQuery'               
                ]
            });
            
            //app.js
            Ext.application({
                name: 'Immap',
                appFolder: 'app',
                controllers: [
                    'charts'
                ],
                autoCreateViewport:true,
                launch : function() {
                    if (Ext.get('loading-mask'))  {
                        Ext.get('loading-mask').fadeOut({remove:true});
                        var frmChartSetting = Ext.create('Immap.view.frmChartSetting');
                        Ext.getCmp('pcenter').add(frmChartSetting);
                        removeChart() 
                        if (pageParameters.rlhidden === "true") {
                            Ext.getCmp('layout-query').hide();
                        }
                        if (pageParameters.gridhidden === "true") {
                            Ext.getCmp('dynamicgrid').hide();
                        }
                        if (typeof pageParameters.chartdd !== "undefined" && typeof pageParameters.chartguid !== "undefined")  {
                            showDataviewBydDddnameAndGuid(pageParameters.chartdd,pageParameters.chartguid);
                        }
                    }
                }
            });
      
            Ext.QuickTips.init();
            
        </script>
    </head>
    <body>
        <div id="loading-mask">  
            <img style="position:absolute; width:128px; height:128px; left:50%; top:40%; margin-left:-64px; margin-top: -3px;" src="images/ajax-loader.gif" />
        </div>

    </body>
</html>
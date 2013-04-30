Ext.Loader.setConfig({
  enabled : true,
  disableCaching : true, // For debug only
  paths : {
    'Chart' : HOME + '/highcharts_extjs4'
  }
});

Ext.require('Chart.ux.HighChart');
Ext.require('Chart.ux.SampleConfigs');

// ALWAYS POST!!
Ext.override(Ext.data.proxy.Ajax,{ 
    getMethod: function(request) { 
        return 'POST'; 
    } 
});

Ext.ns("Demo");

Ext.application({
  name : 'HighCharts',
  appFolder : HOME + '/demos/Highcharts_ExtJs_4/app',
  controllers : ['Charts'],

  launch : function() {

        setTimeout(function(){
                var cmp = Ext.get('loading');
                if (cmp)
                    cmp.remove();
                cmp = Ext.get('loading-mask');
                if (cmp)
                    cmp.fadeOut({remove:true});
            }, 250);


    Ext.create('Ext.container.Viewport', {
      layout : 'border',
      border : '5 5 5 5',
      items : [{
        region : 'north',
        listeners: {
           'render': function(panel) {
               panel.body.on('click', function() {
                   Ext.Msg.alert('Info', 
                      'ExtJs version: ' + Ext.versions.core.version + ", <br/>" + 
                      'Highcharts version: ' + Highcharts.version + ", <br/>" + 
                      'Chart.ux.Highchart: ' + Chart.ux.HighChart.version);
               });
            }
        },
        html : '<h1 class="x-panel-header">Highcharts examples</h1>',
        height: 40,
        id: 'banner',
        border : false,
        margins : '0 0 5 0',
        bodyStyle: { 'background-image': 'url(./images/banner.gif)',
                     'background-repeat': 'repeat-x',
                      color: '#7a7a7a'
                   }
      }, {
        region : 'west',
        width : 200,
        title : 'Charts',
        id: 'leftTree',
        xtype : 'chartsTree',
        margins : '0 5 5 5',
        split: true
      }, {
        region : 'center',
        id : 'centerpane',
        xtype : 'panel',
        layout : 'fit',
        margins : '0 5 5 0',
        tbar : [{
          text : 'Reload Data',
          id : 'reload',
          disabled : true,
          action: 'reload'
        }]
      }]
    });

  }
});

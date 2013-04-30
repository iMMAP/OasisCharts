Ext.define('Immap.form.cboFilter', {
    extend:'Ext.form.field.ComboBox',
    id:'cboFilter',
    alias:'widget.cboFilter',
    width: 300,
    labelWidth: 70,
    fieldLabel:'Report Filter',
    store: storeFilter,
    queryMode:'local',
    hidden:true,
    typeAhead:true,
    listeners: {
        select: function(combo,records,opts) {
            showDataview();
            showChart();
                        
        }
    }
});

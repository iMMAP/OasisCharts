//app/model/QueryReport.js 
Ext.define('Immap.model.QueryReport', {
    extend: 'Ext.data.Model',
    fields: [
    {
        name:'GUID1',
        type:'string'
    },

    {
        name:'QueryName',
        type:'string'
    },

    {
        name:'IsFilter',
        type:'boolean'
    }
    ]
});
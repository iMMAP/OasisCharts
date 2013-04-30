
var getHeadHTML = function(){
    var html = '<div id="banner">';
    html += '<img src="images/logonew.gif" id="logo"/>';
    html += '<div id="title">OASIS Online Charting<h id="version">&nbsp;1.0 Beta<h></div><br>';
    html += '<div id="subTitle"></div>';
    html += '<div id="info_user"><p> Logged in as ['+ Ext.util.Cookies.get("UName") + '] to server [' + Ext.util.Cookies.get("DbName")+"]</p></div>";
    html += '<div id="info_panel"><a class="info_menu" href="immap_controller_login.php?request=logout">Logout</a></div>';
    html += '</div>';
    return html;
}
            

Ext.define('Immap.view.PanelLogo', {
    extend: 'Ext.panel.Panel',
    id: 'PanelLogo',
    alias:'widget.PanelLogo',
    html:getHeadHTML()
});
           

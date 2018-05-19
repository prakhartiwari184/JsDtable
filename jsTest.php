<!DOCTYPE html>
<html>
<title>W3.JS</title>
<!--<script src="w3.js"></script>-->
<head>
    <script type="text/javascript" src="script.js"></script>
    <style>
        table{background: #fff;width: 100%;border-collapse:collapse;margin-left: auto; margin-right: auto;font-family: arial;border: 1px solid #ccc;font-size: 12px;}
        thead,tfoot{background: #ECF0F1;}
        tbody{}
        table td,table th{padding: 4px;overflow: hidden;max-width:0;white-space: nowrap;text-overflow: ellipsis;}
        table input[type=search]{border-radius: 0px;border: 1px solid #ccc;padding: 5px;}
        thead th{text-transform: capitalize;border: 1px solid #ccc;text-align: left;}
        tbody tr>td{border: 1px solid #ccc}
        tfoot tr>td{border: 1px solid #ccc;height: 30px;font-size: 12px;}
        .uparr{cursor: pointer;margin-left: 2px;}
        .uparr:hover,.dnarr:hover{color: red;}.uparr,.dnarr{color:#B3B6B7;}
        .activePg{background: #17202A!important;}
        .pgBtn{background: #85929E;border:  1px solid #85929E;margin-right: 2px; cursor: pointer;color: #fff;}
        .pgBtn:hover{background: #17202A;}
        .disabled{cursor:not-allowed;background: #D6DBDF;}
        .disabled:hover{background: #D6DBDF;}
        .dnarr{cursor: pointer;}
        .active-sort{color: red;font-weight: bolder;}
        .record-info{padding-top: 5px; float: left;}
        .paging{float: right;}
        .error{background: red; color: #fff;padding: 5px;border-radius: 2px;animation: myAnim 1s;position: absolute;margin-bottom: 10px;left:45%;}
         .info{background: #77B136; color: #fff;padding: 5px;border-radius: 2px;animation: myAnim 2s;position: absolute;margin-bottom: 10px;left:45%;}
        @keyframes myAnim{0%{left:0;opacity:.8;} 100%{left:45%;opacity:1;}}
    </style>
</head>
<body>

    
    <div id="div1">
    
    </div>
    
     <div id="div2">
    
     </div>
    
    
 
    
    
</body>
<script>
var options={"container":"div1","tableData":[],"ajax":{"method":"POST","url":"server.php","params":""}};
buildTable(options);



</script>
</html>

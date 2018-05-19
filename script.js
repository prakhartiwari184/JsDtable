window.onload=function(){
    
   
 };
 
 
 var _=function(id){return document.getElementById(id)};
 var __=function(cls){return document.getElementsByClassName(cls)};
 var ___=function(container,cls){return _(container).getElementsByClassName(cls);}
 var log=function(obj){return console.log(obj)};

 
 function buildTable(options)
 {
     var container=options.container;
     var tableData=options.tableData;
     var html='<table border="1">';
     html+='<thead><tr id="'+container+'_tblNav"></tr> <tr id="'+container+'_tblCols"></tr></thead>'; 
     html+='<tbody id="'+container+'_tbody">';
     html+='<tr><td height=25  valign="baseline"><span class="info">Loading..</span></td></tr>';
     html+='</tbody>';
     html+='<tfoot id="'+container+'_tfoot">';
     html+='</tfoot>';
     html+='</table>';
     _(container).innerHTML=html;
      
     
    tempData=[],renderedData=[], originalData=[];    
     
     if(typeof tableData=="object" && tableData.length>0)
     {
        originalData= tableData; buildHeader(container);buildHtml(0,container); setEvents(container);
     }
     else
     {
     var ajaxUrl=options.ajax.url;
     var ajaxMethod=options.ajax.method;
     var ajaxParams=options.ajax.params; 
     getData(ajaxMethod,ajaxUrl,ajaxParams,container);
     }
    
     
     
 }
 
 
 function getData(method,url,body,container)
 {
     
     var xhr=null;
     if(window.XMLHttpRequest)
     {
         xhr=new XMLHttpRequest();
     }
     else
     {
         xhr=new ActiveXObject("Microsoft.XMLHTTP");
     }
     
     xhr.open(method,url,true);
     xhr.send(body);
     xhr.onreadystatechange=function(){if(xhr.readyState==4){parse(xhr.responseText,container)}};
     
 }
 
 function parse(response,container)
 {
     var obj=JSON.parse(response);
     var len=obj.length,i;
     for(i=0;i<len;i++)
     originalData.push(obj[i]);
     buildHeader(container);
     buildHtml(0,container); 
     setEvents(container);
     
 }
 
function filterTbl(str,container)
{   //alert();
    tempData=[]; 
    if(str==undefined || str==""){buildHtml(0,container); return true;}
    var len=originalData.length;
   
    var srchstr=str.toLowerCase();

    for(var i=0;i<len;i++)
    {
        for(var prop in originalData[i])
        {
            if(originalData[i][prop].toString().toLowerCase().indexOf(srchstr)>=0)
            {
                tempData.push(originalData[i]); break;
            }
            
        }
              
                
    }
    
    //console.log(tempData);
    
    buildHtml(0,container); 
}


function sortTbl(tag,id,container)
{ 
    var elem=_(id);
    for(var i=0;i<uparrLen;i++){uparr[i].classList.remove("active-sort");dnarr[i].classList.remove("active-sort")};
    elem.classList.add("active-sort");
    var field=elem.getAttribute("data-field");
  
    var obj=(renderedData.length==0)?originalData:renderedData;
    
  
    var len=obj.length,temp=null;  
    
    for(var i=0;i<len;i++)
    {
        for(var j=0;j<len-1;j++)
        {
            if(tag=="asc")
            {
              if(obj[j][field]>obj[j+1][field])
                {
                    temp=obj[j+1];
                    obj[j+1]=obj[j]; 
                    obj[j]=temp;
                }
                
            }  
            
            else
            {
                if(obj[j][field]<obj[j+1][field])
                {
                    temp=obj[j+1];
                    obj[j+1]=obj[j]; 
                    obj[j]=temp;
                }
            }
           
           
        }
    }
   //alert("Done");
    buildSortedHtml(container);
}

function buildSortedHtml(container)
{
    var obj=renderedData;
    var len=obj.length
    var html="";
           for(var i=0;i<len;i++)
              {   
                  html+='<tr>';
                      for(var prop in obj[i])
                         {
                         
                           html+='<td>'+obj[i][prop]+'</td>';
                           
                         }
                   html+='</tr>';
               } 
           
           _(container+"_tbody").innerHTML=html; 
}

function setEvents(container)
{
    uparr=__("uparr"); uparrLen=uparr.length;
    dnarr=__("dnarr"); dnarrLen=dnarr.length;
    for(var i=0;i<uparrLen;i++)uparr[i].addEventListener("click",function(){sortTbl("asc",this.id,container)});
    for(var i=0;i<dnarrLen;i++)dnarr[i].addEventListener("click",function(){sortTbl("desc",this.id,container)});
    _(container+"_inputVal").addEventListener("input",function(){filterTbl(this.value,container)});
    _(container+"_rowLimit").addEventListener("change",function(){buildHtml(0,container)});
}

function buildHtml(pg,container)
{
 
  renderedData=[];   
  var pgNo=(pg==undefined)?0:pg;
  var cols=Object.keys(originalData[0]);
  var totlaCols=cols.length;
  var parent=_(container+"_tbody");
  var foot=_(container+"_tfoot");
  var inputVal=document.getElementById(container+"_inputVal").value;
  var obj=(tempData.length==0 && inputVal=="")?originalData:tempData;
  var totalRows=obj.length;var col="";
  var rowLimit=_(container+"_rowLimit").value;
  var start=rowLimit*(pgNo);
  var end=parseInt(start)+parseInt(rowLimit);
      end=(end>totalRows)?totalRows:end;
  //alert(start+"--"+end);
   
  
   if(totalRows>0)
   {
           var html="",itration=0;
           for(var i=start;i<end;i++)
              {   
                  renderedData.push(obj[i]); 
                  html+='<tr>';var index=0;
                      for(var prop in obj[i])
                         {
                           
                           html+='<td title="'+obj[i][prop]+'">'+obj[i][prop]+'</td>';
                           index++;
                         }
                   html+='</tr>';itration++;
               } 
               
         
          parent.innerHTML=html; 
          foot.innerHTML=buildFooter(totalRows,totlaCols,pgNo,start,end,container);
           
    }
    else
    {
           parent.innerHTML="<tr><td colspan="+totlaCols+" align='center' height='50'><span class='error'>No records found..</span></td></tr>";
    }
   
}

function buildHeader(container)
{   
        var cols=Object.keys(originalData[0]);
        var totlaCols=cols.length;
        var colCount=parseInt(totlaCols)-1;
        var html='<td colspan="'+colCount+'"> <input type="search" id="'+container+'_inputVal" placeholder="Search table"></td>';
        html+='<td title="% Rows displayed "><select id="'+container+'_rowLimit">';
        for(var i=50;i<=500;i+=50) html+= '<option value='+i+'>'+i+'</option>'; 
        html+='</select></td>';
        _(container+"_tblNav").innerHTML=html;
        var col='';
        for(var i=0;i<totlaCols;i++)
        col+='<th>'+cols[i]+'<span class="uparr" data-field="'+cols[i]+'" id="uarr'+i+'" title="Sort ASC">&uarr;</span><span class="dnarr" data-field="'+cols[i]+'" id="darr'+i+'" title="Sort DESC">&darr;</span></th>'; 
        _(container+"_tblCols").innerHTML=col;
        
        
}

function buildFooter(rsltCount,colCount,pgNo,start,end,container)
{
   var rowLimit=_(container+"_rowLimit").value;
   var pgCount=rsltCount/rowLimit;
   var displayCount=renderedData.length;
   
  
   
   var footer='<tr><td colspan="'+colCount+'"><span class="record-info"> Records displayed  : '+displayCount+' ('+parseInt(start+1)+' to '+end+') of '+rsltCount+'</span> <span class="paging">'; 
      
       if(rsltCount>rowLimit)
       for(var i=0;i<pgCount;i++)
       {
           if(i==pgNo)
           var active="activePg";
           else
           active="";
           
           if(i<5)
           {
            footer+='<button class="pgBtn '+active+'"  onclick=buildHtml(this.getAttribute("data-pgno"),"'+container+'") data-pgno='+parseInt(i)+'>'+parseInt(i+1)+'</button>';
           }
           if(i>5 && i<=6)
           {
            var next=parseInt(pgNo)+(1);var prev=parseInt(pgNo)-(1);
            if(parseInt(prev)<0){var prevDisabled="disabled"; } else prevDisabled="";
            if(next>=pgCount){var nextDisabled="disabled"} else nextDisabled="";
            
            var nextLevel=next+1;
            var prevLevel=prev+1;
            //log("i"+ i+"--- pgNo "+pgNo)
            
            footer+='<button class="pgBtn  '+nextDisabled+'" onclick=buildHtml(this.getAttribute("data-pgno"),"'+container+'") '+nextDisabled+' data-pgno='+next+' >Next -> '+nextLevel+'</button>';
           
            footer+='<button class="pgBtn '+prevDisabled+'" onclick=buildHtml(this.getAttribute("data-pgno"),"'+container+'") '+prevDisabled+' data-pgno='+prev+'>'+prevLevel+' <- Prev </button>';
            
           
           }
           if(i>(pgCount-1))
           {
            footer+='<button class="pgBtn '+active+'" onclick=buildHtml(this.getAttribute("data-pgno"),"'+container+'") data-pgno='+parseInt(i)+'>Last</button>';
           }
           
           
       }
      
       footer+='</span></td></tr>';
       
       return footer;
}


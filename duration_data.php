<?php
ini_set("display_errors",1);
$table_id = filter_input(INPUT_POST, "table_id", FILTER_SANITIZE_STRING);
$dbhandle = mssql_connect("192.168.15.250\SQLSERVER", "user_test", "test@123") or die("Couldn't connect to SQL Server");
$selected = mssql_select_db("discoverytest", $dbhandle) or die("Couldn't open database");

mssql_query('Set ANSI_WARNINGS ON');
mssql_query('Set ANSI_NULLS ON');
extract($_REQUEST);
$mamcdb="mamcdb_sd";
$chn_cond = "";
if(isset($_REQUEST['channelabbr'])!='' && $_REQUEST['channelabbr'] != 'ALL')
{
	$chn_cond = " where channelabbr='".$_REQUEST['channelabbr']."' ";
}

if($action == 'getData') 
{
	$data = array();
	
       if($table_id == "unpreviewtbl") 
        {
		$data['aaData'] = array();
			
		        
			$channel = 'Select * from openquery([MAMC-DB],"select channelabbr from mamcdb_sd.channelinfo '.$chn_cond.'")  AS RemoteTable where channelabbr in (SELECT Table_Name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE="BASE TABLE")';
			$resultch = mssql_query($channel);
			$sno = 0;

 			$unpreview ="";

			while($channelresult = mssql_fetch_array($resultch))

			{
				$channeltablename = $channelresult['channelabbr'];
				
				$unpreview = "select  $sno+ ROW_NUMBER() OVER (ORDER BY video_item) AS [SNo], b_date as EDate, time as ETime, video_item as Clipid, title as Title, duration as Duration, channelID as 'Channel' from ".$channeltablename." where $channeltablename.video_item NOT IN ( Select * from openquery([MAMC-DB],'Select clipid from mamcdb_sd.metadata where preview = 0'))";

				$sql_query = mssql_query($unpreview);
				while($indresult = mssql_fetch_assoc($sql_query))
		                {
					$data['aaData'][] = $indresult;
				
					$sno++;
				}
		                $data['aoColumns'] = array();
				while($finfo = mssql_fetch_field($sql_query)) 
		                {
					$data['aoColumns'][] = $finfo->name;
				
				}
		}
				$data['objid'] = $table_id;
				echo json_encode($data);
	}     

else if($table_id == "missingtbl") 
       {
		$data['aaData'] = array();
		
		$channel = 'Select * from openquery([MAMC-DB],"select channelabbr from mamcdb_sd.channelinfo '.$chn_cond.'")  AS RemoteTable where channelabbr in (SELECT Table_Name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE="BASE TABLE")';
			$resultch = mssql_query($channel);
			$sno = 0;
			$unpreview ="";
			while($channelresult = mssql_fetch_array($resultch))

			{
				$channeltablename = $channelresult['channelabbr'];
			
				$unpreview = "select $sno+ ROW_NUMBER() OVER (ORDER BY video_item) AS [SNo], b_date as EDate, time as ETime, video_item as Clipid, title as Title, duration as Duration, channelID as 'Channel' from ".$channeltablename." where $channeltablename.video_item NOT IN ( Select * from openquery([MAMC-DB],'Select Displayid from mamcdb_sd.vsmetadata'))";
					
				$sql_query = mssql_query($unpreview);			
				while($indresult = mssql_fetch_assoc($sql_query))
		                {
	
					$data['aaData'][] = $indresult;
					$sno++;
				}
		                $data['aoColumns'] = array();
				while($finfo = mssql_fetch_field($sql_query)) 
		                {
					$data['aoColumns'][] = $finfo->name;
				}
			}
				$data['objid'] = $table_id;
				echo json_encode($data);
			
	}     

	else if($table_id == "duration_wisetbl") 
        {
	$data['aaData'] = array();
		/*'.$chn_cond.'*/
		 $channel = 'Select * from openquery([MAMC-DB],"select channelabbr from mamcdb_sd.channelinfo '.$chn_cond.'")  AS RemoteTable where channelabbr in (SELECT Table_Name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE="BASE TABLE")';
		$resultch = mssql_query($channel);
		$sno = 0;
		$unpreview="";
		while($channelresult = mssql_fetch_array($resultch))

		{
			$channeltablename = $channelresult['channelabbr'];
		
			$unpreview = "select $sno+ ROW_NUMBER() OVER (ORDER BY video_item) AS [SNo], ct.b_date as EDate, ct.time as ETime, ct.video_item as Clipid, ct.title as Title, ct.duration as Duration, ct.channelID as 'Channel' from ".$channeltablename." ct inner join (Select * from openquery([MAMC-DB],'Select * from mamcdb_sd.vsmetadata'))  vm ON (ct.video_item = vm.Displayid) and ct.video_intime <> vm.som and ct.Duration <> vm.Duration ";

			$sql_query = mssql_query($unpreview);
			while($indresult = mssql_fetch_assoc($sql_query))
		        {

				$data['aaData'][] = $indresult;
				$sno++;
			}
		        $data['aoColumns'] = array();
			while($finfo = mssql_fetch_field($sql_query)) 
		        {
				$data['aoColumns'][] = $finfo->name;
			}
		}
		$data['objid'] = $table_id;

		echo json_encode($data);
	}   

	else if($table_id == "backtobacktbl") 
        {
		$data['aaData'] = array();
	
		 $channel = 'Select * from openquery([MAMC-DB],"select channelabbr from mamcdb_sd.channelinfo '.$chn_cond.'")  AS RemoteTable where channelabbr in (SELECT Table_Name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE="BASE TABLE")';
		$resultch = mssql_query($channel);
		$sno = 0;
		while($channelresult = mssql_fetch_array($resultch))
		{
			$channeltablename = $channelresult['channelabbr'];
		 	$unpreview = "select $sno+ ROW_NUMBER() OVER (ORDER BY s1.video_item) AS [SNo], s1.b_date as EDate, s1.time as ETime, s1.video_item as
			       Clipid, s1.title as Title, s1.duration as Duration, '$channeltablename' as Channel  FROM ( SELECT ROW_NUMBER() 
			       OVER (PARTITION BY S.video_item ORDER BY S.slno) 
			       AS SNO, * FROM ".$channeltablename." S) S1 INNER JOIN (SELECT ROW_NUMBER() OVER (PARTITION BY S.video_item ORDER BY S.slno) 
			       AS SNO, * FROM ".$channeltablename." S) S2 ON S1.video_item = S2.video_item AND S1.slno = S2.slno - 1  ";

			$sql_query = mssql_query($unpreview);
			
			
			while($indresult = mssql_fetch_assoc($sql_query))
                        {
				$data['aaData'][] = $indresult;
				$sno++;
				
			}
                        $data['aoColumns'] = array();
			while($finfo = mssql_fetch_field($sql_query)) 
                        {
				$data['aoColumns'][] = $finfo->name;
			}
		}
		$data['objid'] = $table_id;
		echo json_encode($data);
	}  
	if($table_id == "showalltbl") 
        {   
		$data['aaData'] = array();

		$channel = 'Select * from openquery([MAMC-DB],"select channelabbr from mamcdb_sd.channelinfo '.$chn_cond.'")  AS RemoteTable where channelabbr in (SELECT Table_Name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE="BASE TABLE")';
		$resultch = mssql_query($channel);
		$sno = 0;
		while($channelresult = mssql_fetch_array($resultch))

		{
			$channeltablename = $channelresult['channelabbr'];

			$unpreview = "select $sno+ ROW_NUMBER() OVER (ORDER BY video_item) AS [SNo], b_date as EDate, time as ETime, video_item as Clipid, title as
			Title, duration as Duration, channelID as 'Channel' from $channeltablename ";
		
			$sql_query = mssql_query($unpreview);


			//$data['aaData'] = array();
			while($indresult = mssql_fetch_assoc($sql_query))
			{

				$data['aaData'][] = $indresult;
				$sno++;
			}
			$data['aoColumns'] = array();
			while($finfo = mssql_fetch_field($sql_query)) 
			{
				$data['aoColumns'][] = $finfo->name;
			}

		}
		$data['objid'] = $table_id;
		echo json_encode($data);
	}  




}
?>



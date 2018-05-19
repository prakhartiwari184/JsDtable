<?php 
header("Content-Type:application/json"); 
function db()
{
     static $conn=null;
     if($conn==null)
     {
        return  $conn=  mysqli_connect("192.168.15.250", "user_test", "test@123", "discoverytest", 3306);
     }
     else
     {
        return $conn; 
     }
    
}

$conn=db();

function query($sql)
{
    global $conn;
    $result= mysqli_query($conn, $sql);
    $data=array();
    while($row=  mysqli_fetch_assoc($result))
    {
        $data[]=$row;
    }
    return $data;
}

function json($array)
{
    return json_encode($array);
}


$sql="SELECT sno, channelid, playlistdate, playlistname,  onairtime, eventtype, title,  mediaid, inventory, som,  duration,  segmentduration,    ReconKey FROM playlistupload";
$data=query($sql);
echo json($data);

?>

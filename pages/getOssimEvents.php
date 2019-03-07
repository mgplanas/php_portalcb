<?php
include("../conexionOssim.php");
$type  = $_GET['type'];


switch($type)
{
        case "tcp":
        /* Amount of attacks by port TCP */
        $events = "select layer4_dport as port, count(id) as num from alienvault_siem.acid_event where layer4_dport != 0 and ip_proto=6 group by                port order by num desc limit 10;";
        $query = mysqli_query($cono, $events) or die('Query failed: ' . mysql_error());

        $data1 = array();

        while ( $row = mysqli_fetch_assoc($query))  {
        $data1[]=$row;
        }

        echo json_encode($data1);
        break;
        
        case "udp":
        /* Amount of attacks by port UDP */
        $events = "select layer4_dport as port, count(id) as num from alienvault_siem.acid_event where layer4_dport != 0 and ip_proto=17 group by                  port order by num desc limit 10;";
        $query = mysqli_query($cono, $events) or die('Query failed: ' . mysql_error());

        $data1 = array();

        while ( $row = mysqli_fetch_assoc($query))  {
        $data1[]=$row;
        }

        echo json_encode($data1);
        break;
        
        case "unique":
        /* Top 10 Hosts by unique events*/
        $events = "select count(distinct plugin_id, plugin_sid) as num_events,ip_src as name from alienvault_siem.po_acid_event AS acid_event group             by ip_src having ip_src>0x00000000000000000000000000000000 order by num_events desc limit 10;";
        $query = mysqli_query($cono, $events) or die('Query failed: ' . mysql_error());

        $data1 = array();
        $data2 = array();
        while ( $row = mysqli_fetch_assoc($query))  {

        $data2['num_events']  = $row["num_events"];
        $data2['name'] = inet_ntop($row["name"]);    
        array_push($data1, $data2);
        }

        echo json_encode($data1, JSON_PRETTY_PRINT);
        break;
        
        case "promiscuos":
        /* Top 10 Hosts by number of events as source or destinations */
        $events = "select count(distinct(ip_dst)) as num_events,ip_src as name from alienvault_siem.po_acid_event AS acid_event WHERE 1=1 group by              ip_src having ip_src>0x00000000000000000000000000000000 order by num_events desc limit 10;";
        $query = mysqli_query($cono, $events) or die('Query failed: ' . mysql_error());

        $data1 = array();
        $data2 = array();
        
        while ( $row = mysqli_fetch_assoc($query))  {
        $data2['num_events']  = $row["num_events"];
        $data2['name'] = inet_ntop($row["name"]);    
        array_push($data1, $data2);
        }

        echo json_encode($data1);
        break;
        
        default:		
		echo 'unknown type';

}
?>
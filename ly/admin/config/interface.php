<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   require_once('_inc.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网卡列表</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<script language="javascript" type="text/javascript" src="./ipcheck.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />

<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>
<script type="text/javascript">
function submit_check()
		{
		  if(checkinput())
			{	
			  return true;
			}
			else
			{
			  return false;
			}			
		};		
jQuery(document).ready(function(){
jQuery("#ips_list").jqGrid({ 
    datatype: "local",
    height: 150, 
	width:650,
    colNames:['网卡名称','方式', 'ip地址','子网掩码','状态','mac地址'], 
    colModel:[ {name:'name',index:'name', width:60, sorttype:"int"},
               {name:'mode',index:'mode', width:90, sorttype:"date"}, 
               {name:'ip',index:'ip', width:100},
               {name:'netmask',index:'netmask', width:100},
               {name:'state',index:'state', width:100},
               {name:'mac',index:'mac', width:120},

             ], 
  
	caption: "接口列表"
});
 var mydata = <?php 
 $return_array = array();
 require_once("shell_interface.php");
 $mac = new GetMacAddr('br0');
  if (isset($mac->mac_addr[0])) 
       {
         $return_array[0]['name']='br0';
         $return_array[0]['mode']='网桥(eth0,eth1)';
         $ipi = preg_match_all('/(\d+)\.(\d+)\.(\d+)\.(\d+)/',$mac->mac_addr[1],$ip);    
           if ($ipi == 0)  
            {       
             $return_array[0]['ip']='';
             $return_array[0]['netmask']='';
            }         
           else
            {
              $return_array[0]['ip']=$ip[0][0];
              $return_array[0]['netmask']=$ip[0][2];
             }
         $return_array[0]['state']='建立网桥';
         $maci = preg_match('/[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}/ ',$mac->mac_addr[0],$mac);     
         $return_array[0]['mac']=$mac[0];
         
        }
      
      else
        {
         
          $return_array[0]['name']='br0';
          $return_array[0]['mode']='网桥(eth0,eth1)';
          $return_array[0]['ip']='';
          $return_array[0]['netmask']='';
          $return_array[0]['state']='没有建立网桥';
          $return_array[0]['mac']='';
        
        } 
      $mac = new GetMacAddr('eth0');
      if (isset($mac->mac_addr[0])) 
        {
          $return_array[1]['name']='eth0';
          $return_array[1]['mode']='外网网卡';
          $ipi = preg_match_all('/(\d+)\.(\d+)\.(\d+)\.(\d+)/',$mac->mac_addr[1],$ip);    
          if ($ipi == 0)  
            {       
              $return_array[1]['ip']='';
              $return_array[1]['netmask']='';
              if ( strstr($mac->mac_addr[2],'RUNNING') ||strstr($mac->mac_addr[1],'RUNNING') ||strstr($mac->mac_addr[3],'RUNNING') )
               $return_array[1]['state']='连线';
              else
                $return_array[1]['state']='断线';
  
              }         
           else
              {
               $return_array[1]['ip']=$ip[0][0];
               $return_array[1]['netmask']=$ip[0][2];
               if ( strstr($mac->mac_addr[2],'RUNNING') ||strstr($mac->mac_addr[1],'RUNNING')||strstr($mac->mac_addr[3],'RUNNING')  )
                 $return_array[1]['state']='连线';
               else
                 $return_array[1]['state']='断线';
               }
          
           $maci = preg_match('/[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}/ ',$mac->mac_addr[0],$mac);     
           $return_array[1]['mac']=$mac[0];
         
         }
      
      else
         {
         
         $return_array[1]['name']='eth0';
         $return_array[1]['mode']='外网网卡';
         $return_array[1]['ip']='';
         $return_array[1]['netmask']='';
         $return_array[1]['state']='不存在';
         $return_array[1]['mac']='';
        
         } 
                     
       $mac = new GetMacAddr('eth1');
      if (isset($mac->mac_addr[0])) 
        {
          $return_array[2]['name']='eth1';
          $return_array[2]['mode']='内网网卡';
          $ipi = preg_match_all('/(\d+)\.(\d+)\.(\d+)\.(\d+)/',$mac->mac_addr[1],$ip);    
          if ($ipi == 0)  
            {       
              $return_array[2]['ip']='';
              $return_array[2]['netmask']='';
             if ( strstr($mac->mac_addr[2],'RUNNING') ||strstr($mac->mac_addr[1],'RUNNING') ||strstr($mac->mac_addr[3],'RUNNING')  )
               $return_array[2]['state']='连线';
              else
                $return_array[2]['state']='断线';
  
              }         
           else
              {
               $return_array[2]['ip']=$ip[0][0];
               $return_array[2]['netmask']=$ip[0][2];
               if ( strstr($mac->mac_addr[2],'RUNNING') ||strstr($mac->mac_addr[1],'RUNNING') ||strstr($mac->mac_addr[3],'RUNNING')  )
                 $return_array[2]['state']='连线';
                else
                 $return_array[2]['state']='断线';
               }
          
           $maci = preg_match('/[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}/ ',$mac->mac_addr[0],$mac);     
           $return_array[2]['mac']=$mac[0];
         
         }
      
      else
         {
         
         $return_array[2]['name']='eth0';
         $return_array[2]['mode']='内网网卡';
         $return_array[2]['ip']='';
         $return_array[2]['netmask']='';
         $return_array[2]['state']='不存在';
         $return_array[2]['mac']='';
        
         } 
        $mac = new GetMacAddr('eth2');
      if (isset($mac->mac_addr[0])) 
        {
          $return_array[3]['name']='eth2';
          $return_array[3]['mode']='管理网卡';
          $ipi = preg_match_all('/(\d+)\.(\d+)\.(\d+)\.(\d+)/',$mac->mac_addr[1],$ip);    
          if ($ipi == 0)  
            {       
              $return_array[3]['ip']='';
              $return_array[3]['netmask']='';
              if ( strstr($mac->mac_addr[2],'RUNNING') ||strstr($mac->mac_addr[1],'RUNNING') ||strstr($mac->mac_addr[3],'RUNNING')  )
               $return_array[3]['state']='连线';
              else
                $return_array[3]['state']='断线';
  
              }         
           else
              {
               $return_array[3]['ip']=$ip[0][0];
               $return_array[3]['netmask']=$ip[0][2];
               if ( strstr($mac->mac_addr[2],'RUNNING') ||strstr($mac->mac_addr[1],'RUNNING') ||strstr($mac->mac_addr[3],'RUNNING')  )
                 $return_array[3]['state']='连线';
                else
                 $return_array[3]['state']='断线';
               }
          
           $maci = preg_match('/[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}/ ',$mac->mac_addr[0],$mac);     
           $return_array[3]['mac']=$mac[0];
         
         }
      
      else
         {
         
         $return_array[3]['name']='eth2';
         $return_array[3]['mode']='管理网卡';
         $return_array[3]['ip']='';
         $return_array[3]['netmask']='';
         $return_array[3]['state']='不存在';
         $return_array[3]['mac']='';
        
         } 
                     
	 $mydata = json_encode($return_array);
	 echo $mydata;
	
	?>;
for(var i=0;i<=mydata.length;i++) 
{ 
 jQuery("#ips_list").jqGrid('addRowData',i+1,mydata[i]);    
}

});
</script>
</head>
<body>
<?php
 $command = "cat /bri/ipset";
 exec($command,$ipshow);
 preg_match_all('/(\d+)\.(\d+)\.(\d+)\.(\d+)/',$ipshow[0],$ipsh);   
 $ip = $ipsh[0][0];
 $netmask = $ipsh[0][1];
 $command = "cat /bri/gateway";
 exec($command,$gateways);
 preg_match_all('/(\d+)\.(\d+)\.(\d+)\.(\d+)/',$gateways[0],$gatewaysh);   
 $gateway = $gatewaysh[0][0];
 //echo $gateway;
 //preg_match('/(\d+)\.(\d+)\.(\d+)\.(\d+)/',$ipshow[1],$gate);
 //$gateway  = $gate[0];
 $command = "cat /etc/resolv.conf";
 exec($command,$dnss);
 preg_match('/(\d+)\.(\d+)\.(\d+)\.(\d+)/', $dnss[0],$dnse);
 $dns = $dnse[0];
 if (isset($_POST['ip']))
 {
  $ip = $_POST['ip'];
  $netmask = $_POST['netmask'];
  $gateway = $_POST['gateway'];
  $dns = $_POST['dns'];  
  $command = "echo 'ifconfig eth2 $ip netmask $netmask up'  > /bri/ipset";
  exec($command);
  $command = "echo 'route add default gw $gateway' > /bri/gateway";
  //echo $command;
  exec($command);
  $command = "echo 'nameserver $dns' > /etc/resolv.conf";
  exec($command);
  $command = "sudo ifconfig eth2 $ip netmask $netmask up";
  //$command ="";
  exec($command);
  @exec($gCmd." 13");
 }
?>
<h1>所有网卡列表</h1>
<table id="ips_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
 <form action="interface.php" method="post" enctype="multipart/form-data" name="form1" >
 <div >
  <h1>修改服务器Ip地址</h1>
<div >
  <table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td>IP地址：</td>
    <td>
      <input name="ip" type="text" id="ip" value=<?php echo $ip ?> maxlength="20"> 
     
    </td>
  </tr>
  <tr>
    <td>子网掩码： </td>
    <td>
      <input name="netmask" type="text" id="netmask"  value=<?php echo $netmask ?> maxlength="20">
   </td>
  </tr>
  <tr>
    <td>网关： </td>
    <td>
      <input name="gateway" type="text" id="gateway"  value=<?php echo $gateway ?>  maxlength="20">
    </td>
  </tr>
  <tr>
    <td>DNS： </td>
    <td>
      <input name="dns" type="text" id="dns"  value=<?php echo $dns ?>  maxlength="20">
    </td>
  </tr>
</table>
</div>
<br>

<input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 提交 " onclick="return submit_check()">
</form>


</body>
</html>

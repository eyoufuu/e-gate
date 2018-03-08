<?php


class GetMacAddr   
{   
        var $return_array = array(); // 返回带有MAC地址的字串数组   
        var $mac_addr=array();   
           
        function GetMacAddr($interface)   
        {   
             switch (strtolower(PHP_OS) )   
                {   
                        case "linux":$this->forLinux($interface);break;   
                        case "solaris":break;   
                        case "unix":break;   
                        case "aix":break;   
                        default:$this->forWindows();break;   
                }   
       
               
                $temp_array = array();

                foreach ( $this->return_array as $value )   
                {
                       /* if ( preg_match( "/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i", $value, $temp_array ) )   
                        {   
                                $this->mac_addr[] = $temp_array[0];   
                        }*/
                       	$this->mac_addr[] = $value;					
                }   
                unset($temp_array);   
                return $this->mac_addr;   
        }   
  
        function forWindows()   
        {   
                @exec("ipconfig /all", $this->return_array);   
                if ( $this->return_array )   
                        return $this->return_array;   
                else{   
                        $ipconfig = $_SERVER["WINDIR"]."\system32\ipconfig.exe";   
                        if ( is_file($ipconfig) )   
                           @exec($ipconfig." /all", $this->return_array);   
                        else  
                           @exec($_SERVER["WINDIR"]."\system\ipconfig.exe /all", $this->return_array);   
                        return $this->return_array;   
                }   
        }   
  
        function forLinux($interface)   
        {   
                @exec("ifconfig $interface", $this->return_array);   
                return $this->return_array;   
        }   
}



   
?>


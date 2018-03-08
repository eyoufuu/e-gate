function checkip(ip_addr)   
{  
	var scount=0;    
	var iplength = ip_addr.length;   
	var Letters = "1234567890.";   
	for (i=0; i < iplength; i++)   
	{ 
		var CheckChar = ip_addr.charAt(i);   
		if (Letters.indexOf(CheckChar) == -1)   
		{
			return false;   
		}   
	}
	for (var i = 0;i<iplength;i++)   
		(ip_addr.substr(i,1)==".")?scount++:scount;   
	
	if(scount!=3)   
	{  
		return false;   
	}   
	first = ip_addr.indexOf(".");   
	last = ip_addr.lastIndexOf("."); 
	str1 = ip_addr.substring(0,first);  
	subip = ip_addr.substring(0,last);   
	sublength = subip.length;   
	second = subip.lastIndexOf(".");  
	str2 = subip.substring(first+1,second);  
	str3 = subip.substring(second+1,sublength);   
	str4 = ip_addr.substring(last+1,iplength);   
	
	if (str1==""||str2==""||str3== ""||str4 == "")   
	{  
		return false;   
	}   
	if (str1< 0||str1 >255)   
	{  
		return false;   
	}   
	else if (str2< 0||str2 >255)   
	{  
		return false;   
	}   
	else if (str3< 0||str3 >255)   
	{ 
		return false;   
	}   
	else if (str4< 0||str4 >255)   
	{ 
		return false;   
	} 
	return true;
}
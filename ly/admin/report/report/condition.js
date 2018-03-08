function onmark(obj)
{
	var container_lock1=document.getElementById("lock1");
	var container_lock2=document.getElementById("lock2");
	var container_lock3=document.getElementById("lock3");
	var container_lock4=document.getElementById("lock4");
	
        container_lock1.disabled=!obj.checked;
	container_lock2.disabled=!obj.checked;
        container_lock3.disabled=!obj.checked;
	container_lock4.disabled=!obj.checked;
 
}
function checkinput()
{
//	if(document.getElementById('haveip').checked==false)
//	{
//		return true;
//	}
	var ips=document.getElementById('ips').value;
	var ipe=document.getElementById('ipe').value;

	if(ips=="" || ipe=="")
	{
		alert("ip为空");
		return false;
	}
	if(checkip(ips) & checkip(ipe))
	{
		return true;
	}
	alert("ip输入错误");
	return false;
}
function checkip(ipaddr)
{
	var re=/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/;//正则表达式   
	if(re.test(ipaddr))
	{
		if(RegExp.$1<256 && RegExp.$2<256 && RegExp.$3<256 && RegExp.$4<256)
			return true;
	}	
	return false;
}		

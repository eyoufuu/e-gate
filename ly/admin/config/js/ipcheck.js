function onmark(obj)
{
	var searchtxt = document.getElementById("search");
//	var container_ipe=document.getElementById("ipe");
	searchtxt.disabled=!obj.checked;
//	container_ipe.disabled=!obj.checked;
}
function checkinput()
{
//	if(document.getElementById('haveip').checked==false)
//	{
//		return true;
//	}
   alert('check');
	var ips=document.getElementById('ip').value;
	var ipe=document.getElementById('netmask').value;

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
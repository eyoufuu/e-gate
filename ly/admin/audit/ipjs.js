function  mask(obj)
{   
	obj.value=obj.value.replace(/[^\d]/g,''); 
	key1=event.keyCode;
	if(obj.value.length>=3)
	{
		if(parseInt(obj.value)>=256  ||  parseInt(obj.value)<=0)   
		{   
			alert(parseInt(obj.value)+"地址错误");   
			obj.value="";  
			obj.focus();   
			return  false;   
		}
	}
	if(key1==37  ||  key1==39 || key1 ==110)  
	{
		if(key1==110)
		{
			if(obj.value=="")
			{				
				return false;
			}
		}
		obj.blur();   
		nextip=parseInt(obj.id.substr(3,1));
		if(key1==37)
		{
			nextip = nextip-1;
		}
		else
		{
			nextip = nextip+1;
		}
		if(nextip>=5)
		{
			nextip = 4;
		}
		if(nextip<=0)
		{
			nextip = 1;
		}
		head = obj.id.substr(0,3);
		eval(document.getElementById(head+nextip).focus());
//		eval("obj1.focus()");
//		eval("ips"+nextip+".focus()");   
	}	
}   
function  mask_c(obj)   
{   
	clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''));   
}   
function position(obj)
{
	
//	obj.focus();
 //    var   rng=document.selection.createRange();
   //  rng.moveStart("character", obj.value.length);     
    // rng.select();     
}
function getCaret(textbox)
{
    var control = document.activeElement;
    textbox.focus();
    var rang = document.selection.createRange();
    rang.setEndPoint("StartToStart",textbox.createTextRange())
    control.focus();
    return rang.text.length;
}

function setCaret(textbox,pos)
{
	var r = textbox.createTextRange();　　
	　　r.moveStart('character',pos);
		r.collapse(true);
	　　r.select();

}
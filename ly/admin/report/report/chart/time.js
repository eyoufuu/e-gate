
function getDate()
{
  var d,s,t;
  d=new Date();
  s=d.getFullYear().toString(10)+"年"+"-";
  t=d.getMonth()+1;
  s+=(t>9?"":"0")+t+"月"+"-";
  t=d.getDate();
  s+=(t>9?"":"0")+t+"日"+" ";
  t=d.getHours();
  s+=(t>9?"":"0")+t+":";
  t=d.getMinutes();
  s+=(t>9?"":"0")+t+":";
  t=d.getSeconds();
  s+=(t>9?"":"0")+t;
  return s;
}





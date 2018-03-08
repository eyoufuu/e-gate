<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>菜单</title>
<link href="common/menu.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base target="mcMainFrame" />
</head>
<script language="javascript">
<!--
function $(objectId) 
{
	 return document.getElementById(objectId);
}

function showHide(objname)
{
    var obj = $(objname);
    if(obj.style.display == "none")
    {
        obj.style.display = "block";
    }
    else
    {
        obj.style.display = "none";
    }
    
    return false;
}

function refreshMainFrame(url)
{
    parent.mcMainFrame.document.location = url;
}
-->
</script>
<base target="mcMainFrame">
<body>
<div class="menu">


{--if "product" == $module--}
     <dl>
        <dt><a href="" onclick="return showHide('items0');" target="_self">流量展示</a></dt>
        <dd id="items0" style="display:block;">
            <ul>
				<li><a href='tc/water_total.php'>总流量</a></li>
				<li><a href='tc/water_ip_top10.php'>TOP10-IP流量</a></li>
				<li><a href='tc/water_pro_top10.php'>TOP10-网络软件流量</a></li>
            </ul>
        </dd>
    </dl>    
    <dl>
        <dt><a href="" onclick="return showHide('items1');" target="_self">流量控制</a></dt>
        <dd id="items1" style="display:block;">
            <ul>
				<li><a href='tc/tc_condition.php'>条件设置</a></li>
				<li><a href='tc/tc_simple.php'>简单流控</a></li>
				<li><a href='tc/tc_advance.php'>高级流控</a></li>
            </ul>
        </dd>
    </dl>	
	<dl>
        <dt><a href="" onclick="return showHide('items2');" target="_self">员工观察通道</a></dt>
        <dd id="items2" style="display:block;">
        	<ul>
				<li><a href='tc/tc_p_traffic.php'>个人流量展示</a></li>
				<li><a href='tc/tc_p_everyday.php'>每日记录</a></li>
         	</ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('tc/water_total.php');</script>
{--/if--} 

{--if "usermanager" == $module--}
    <dl>
        <dt><a href="" onclick="return showHide('items3');" target="_self">用户管理</a></dt>
        <dd id="items3" style="display:block;">
            <ul>
				<li><a href='user_manager/user_manager2.php'>用户网段管理</a></li>
		    </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('user_manager/user_manager2.php');</script>
{--/if--} 


{--if "guestbook" == $module--}
    <dl>
        <dt><a href="" onclick="return showHide('items4');" target="_self">策略管理</a></dt>
        <dd id="items4" style="display:block;">
            <ul>
				<li><a href = 'policy2/policysimple4.php?policyid=0'>网页和网络软件控制</a></li>
				<li><a href='policy/policy_name2.php'>策略制定</a></li>
				<li><a href='policy/black_web2.php'>黑白IP和网址</a></li>
		    </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('policy2/policysimple4.php?policyid=0');</script>
{--/if--} 

{--if "job" == $module--}
	<dl>        
        <dt><a href="" onclick="return showHide('items5');" target="_self">审计设置</a></dt>
        <dd id="items5" style="display:block;">
            <ul>
				<li><a href='audit/setting.php'>设置</a></li>
            </ul>
        </dd>
     </dl>
    <dl>        
        <dt><a href="" onclick="return showHide('items6');" target="_self">发帖管理</a></dt>
        <dd id="items6" style="display:block;">
            <ul>
				<li><a href='audit/post.php'>发帖列表</a></li>
            </ul>
        </dd>
     </dl>
     <dl>        
        <dt><a href="" onclick="return showHide('items7');" target="_self">Web邮件管理</a></dt>
        <dd id="items7" style="display:block;">
            <ul>
				<li><a href='audit/webmail.php'>Web邮件列表</a></li>
            </ul>
        </dd>
     </dl>
     <dl>   
        <dt><a href="" onclick="return showHide('items8');" target="_self">邮件smtp管理</a></dt>
        <dd id="items8" style="display:block;">
            <ul>
				<li><a href='audit/smtp.php'>邮件smtp列表</a></li>
            </ul>
        </dd>
     </dl>
     <dl>
        <dt><a href="" onclick="return showHide('items9');" target="_self">邮件POP3管理</a></dt>
        <dd id="items9" style="display:block;">
            <ul>
				<li><a href='audit/pop3.php'>邮件POP3列表</a></li>
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('audit/setting.php');</script>
{--/if--} 
{--if "other" == $module--}
   <dl>
        <dt><a href="" onclick="return showHide('items10');" target="_self">报表</a></dt>
        <dd id="items10" style="display:block;">
            <ul>
			<li><a href='report/condition.php'>设置</a></li>
				 <li><a href='report2/updownflow.php'>上下行流量</a></li>
                 <li><a href='report2/top10ipflow.php'>Top10IP流量</a></li>
				 <li><a href='report2/topblockip.php'>Top10阻挡IP</a></li>
                 <li><a href='report2/topproflow.php'>Top10协议流量</a></li>
                 <li><a href='report2/topblockpro.php'>Top10阻挡协议</a></li>
				   <li><a href='report2/takejobweb.php'>访问招聘网站</a></li>
				 <li><a href='report2/toppassweb.php'>访问最多的网站</a></li>
                 <li><a href='report2/topblockweb.php'>被阻挡最多的网站</a></li>  
                 <li><a href='report2/toppasswebcat.php'>访问最多的网站分类</a></li>
                 <li><a href='report2/topblockwebcat.php'>被阻挡最多的网站分类</a></li>
            </ul>
        </dd>
     </dl>
     <dl>
        <dt><a href="" onclick="return showHide('items11');" target="_self">日志</a></dt>
        <dd id="items11" style="display:block;">
            <ul>
                 <li><a href='report2/keyword.php'>关键词</a></li>
				 <li><a href='report2/filetype.php'>文件类型</a></li>
				 <li><a href='report2/webcat.php'>网站分类</a></li>
                 <li><a href='report2/logoper.php'>操作日志</a></li>
                
           </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('report/condition.php');</script>
{--/if--} 

{--if "news" == $module--}
    <dl>
        <dt><a href="" onclick="return showHide('items12');" target="_self">网卡接口</a></dt>
        <dd id="items12" style="display:block;">
            <ul>
				<li><a href='config/interface.php'>网卡列表</a></li>
		      <li><a href='config/ip_mac2.php'>MAC-IP绑定</a></li>
            </ul>
        </dd>
    </dl>
     <dl>
        <dt><a href="" onclick="return showHide('items13');" target="_self">管理</a></dt>
        <dd id="items13" style="display:block;">
             <ul>
				<li><a href='sysuser/admin_groups.php'>管理员组</a></li>
				<li><a href='sysuser/admin_group_add.php'>添加管理员组</a></li>
				<li><a href='sysuser/admin_admins.php'>管理员</a></li>
				<li><a href='sysuser/admin_admin_add.php'>添加管理员</a></li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt><a href="" onclick="return showHide('items14');" target="_self">全局设置</a></dt>
        <dd id="items14" style="display:block;">
             <ul>
				<li><a href='global/global.php'>系统设置</a></li>
				<li><a href='tools/sysinfo.php'>系统信息</a></li>
            </ul>
        </dd>
    </dl>       
  <!-- <dl>
        <dt><a href="" onclick="return showHide('items15');" target="_self">邮件服务</a></dt>
        <dd id="items15" style="display:block;">
            <ul>
				<li><a href='config/mail_config.php'>smtp服务</a></li>
				<li><a href='news/category_add.php'>发送管理</a></li>
            </ul>
        </dd>
    </dl> -->  
     <dl>
        <dt><a href="" onclick="return showHide('items16');" target="_self">数据备份与恢复</a></dt>
        <dd id="items16" style="display:block;">
            
            <ul>
				<li><a href='system/db_import.php'>数据备份与恢复</a></li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt><a href="" onclick="return showHide('items17');" target="_self">系统升级</a></dt>
        <dd id="items17" style="display:block;">
            <ul>
				<li><a href='system/update.php'>系统升级</a></li>
            </ul>
        </dd>
    </dl>     
     <dl>
        <dt><a href="" onclick="return showHide('items18');" target="_self">企业信息</a></dt>
        <dd id="items18" style="display:block;">
            <ul>
				<li><a href='company/cominfo.php'>企业信息列表</a></li>
				<li><a href='company/infoadd.php'>添加企业信息</a></li>
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('company/cominfo.php');</script>
{--/if--} 

{--if "tools" == $module--}
   <dl>
        <dt><a href="" onclick="return showHide('items19');" target="_self">核心服务器防护</a></dt>
        <dd id="items19" style="display:block;">
            <ul>
				<li><a href='serverprotect/index2.php'>网关防护</a></li>
				<li><a href='serverprotect/webprotect.php'>WEB服务器防护</a></li>
				<li><a href='serverprotect/dbprotect.php'>数据库防护</a></li>				
            </ul>
        </dd>
    </dl>
     <dl>
        <dt><a href="" onclick="return showHide('items20');" target="_self">文件外发防护</a></dt>
        <dd id="items20" style="display:block;">
            <ul>
				<li><a href='fileoutmanager/filetype_protect.php'>文件类型检测</a></li>
				<li><a href='fileoutmanager/mail_protect.php'>邮件外发检测</a></li>
				<li><a href='fileoutmanager/im_protect.php'>即时通讯检测</a></li>
				<li><a href='fileoutmanager/bbs_protect.php'>论坛外发检测</a></li>
				<li><a href='fileoutmanager/netdisk_protect.php'>网盘外发检测</a></li>
				<li><a href='fileoutmanager/blog_protect.php'>博客外发检测</a></li>
				<li><a href='fileoutmanager/ftp_protect.php'>FTP外发检测</a></li>
				<li><a href='fileoutmanager/tftp_protect.php'>TFTP外发检测</a></li>							
            </ul>
        </dd>
    </dl>
    <dl>
        <dt><a href="" onclick="return showHide('items21');" target="_self">核心流量保障</a></dt>
        <dd id="items21" style="display:block;">
            <ul>
				<li><a href='coreflow/vpnflow.php'>VPN流量保障</a></li>
				<li><a href='coreflow/voipflow.php'>VOIP流量保障</a></li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt><a href="" onclick="return showHide('items22');" target="_self">节能提醒</a></dt>
        <dd id="items22" style="display:block;">
            <ul>
				<li><a href='energeremind/pconline.php'>PC在线</a></li>
				<li><a href='energeremind/onlinetime.php'>在线时间统计</a></li>
				<li><a href='energeremind/eleccacu.php'>电力成本计算</a></li>					
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('tools/mail_protect.php');</script>
{--/if--} 
{--if "netscan" == $module--}
    <dl>
        <dt><a href="" onclick="return showHide('items23');" target="_self">网络检测</a></dt>
        <dd id="items23" style="display:block;">
            <ul>
				<li><a href='tools/arpdetect.php'>ARP扫描</a></li>
		        <li><a href='tools/index2.php'>NMAP扫描</a></li>
		    </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('tools/arpdetect.php');</script>
{--/if--} 
</div>
</body>
</html>

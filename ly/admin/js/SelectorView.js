/**
 * 开源代码, 有兴趣的可以在保留声明的前提下免费使用.
 *********** 声明开始 ***********
 * @author ideawu@163.com
 * @link http://www.ideawu.net
 *********** 声明结束 ***********
 *
 * 用于显示两栏(备选框, 已选框)选择器控件的JavaScript控件.
 * 需要 jQuery, TableView.
 *
 * @example:
 *
 * <code>
 * HTML代码:
 * <div id="sel_div"></div>
 * 
 * JavaScript代码:
 * var sel = new SelectorView('sel_div'); // sel_div 是 HTML 节点 id
 * sel.src.header = {
 * 	id			: 'Id',
 * 	name		: 'Name',
 * 	text		: 'Text'
 * };
 * sel.dst.header = {
 * 	id			: 'Id',
 * 	name		: 'Name',
 * };
 * sel.src.dataKey = 'id';
 * sel.dst.dataKey = 'id';
 * sel.src.title = '可选';
 * sel.dst.title = '已选';
 *
 * sel.src.add({id: 1, name: 'Tom', text: 'Tomcat'});
 * sel.src.add({id: 2, name: 'Jerry', text: 'Jerrimy'});
 *
 * sel.render();
 * </code>
 *
 * @param string id: HTML节点的id.
 */
function SelectorView(id){
	this.id = id;
	this.title = '';
	this.container = null;
	this._rendered = false;

	// 为了在函数中引用.
	var self = this;

	var div = document.getElementById(this.id);
	div.view = this;
	this.container = div;

	var id_prefix = 'asdfsafokmlv';
	var src_id = this.id + '_' + id_prefix + '_src';
	var dst_id = this.id + '_' + id_prefix + '_dst';
	var str = '';
	str += '<table class="selector_table">';
	str += '<tr>';
	str += '<td valign="top" class="src">';
		str += '<div id="' + src_id + '" class="src_div"></div>';
		str += '<input type="button" value="选择" onclick="document.getElementById(\'' + this.id + '\').view.select()" />';
	str += "</td>\n";
	str += '<td valign="top" class="dst">';
		str += '<div id="' + dst_id + '" class="dst_div"></div>';
		str += '<input type="button" value="取消选择" onclick="document.getElementById(\'' + this.id + '\').view.unselect()" />';
	str += "</td>\n";
	str += "</tr>\n";
	str += "</table>\n";
	this.container.innerHTML = str;

	this.src = new TableView(src_id);
	this.dst = new TableView(dst_id);

	// 重写数据表格的行双击方法.
	this.src.dblclick = function(id){
		var rows = self.src.rows;
		self.dst.add(rows[id]);
		self.src.del(rows[id]);
	};

	// 重写数据表格的行双击方法.
	this.dst.dblclick = function(id){
		var rows = self.dst.rows;
		self.src.add(rows[id]);
		self.dst.del(rows[id]);
	};

	/**
	 * 渲染整个选择控件.
	 */
	this.render = function(){
		this.src.render();
		this.dst.render();
		this._rendered = true;
	};

	/**
	 * 将备选框中已选的数据移动到已选框中.
	 */
	this.select = function(){
		var rows = this.src.getSelected();
		this.dst.addRange(rows);
		this.src.delRange(rows);
	};

	/**
	 * 将已选框中已选的数据移动到备选框中.
	 */
	this.unselect = function(){
		var rows = this.dst.getSelected();
		this.src.addRange(rows);
		this.dst.delRange(rows);
	};

	/**
	 * 获取已选择的的数据对象列表.
	 */
	this.getSelected = function(){
		return this.dst.rows;
	};

	/**
	 * 获取所有已选择的数据对象键值列表.
	 */
	this.getSelectedKeys = function(){
		var keys = [];
		var rows = this.dst.rows;
		for(var i in rows){
			keys.push(rows[i][this.dst.dataKey]);
		}
		return keys;
	};
}

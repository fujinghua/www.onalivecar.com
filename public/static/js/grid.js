

/*
 创建表格
 如果页面需要创建多个表格，则使用该语句：var Table_Layui1=Table_Layui,然后使用对象Table_Layui1去操作
 */
var Table_Layui = {
    //Table的模块ID--建议使用Div
    TablePanel: "",
    //是否需要序号
    CountNumberBool: true,
    //表格的列标题参数：
    //{ txtName: "列标题的名称",
    //ValueCode: "列值的取值编码",
    //width: 列的长度,
    //Style: "自定义列的样式"
    //ValueDeal: 自定义的处理值得方法 }
    Column: [
        { txtName: "", ValueCode: "", width: 0, Style: "", ValueDeal: function (value) { return value; } }
    ],
    //编辑方法
    Edit: "",
    //删除方法
    Delete: "",
    data: [],
    //当前页
    PageIndex: 1,
    //每页展示的数据量
    PageSize: 10,
    //总数据量，假如该值为0，则表示不需要创建页码
    SumDateCounte: 0,
    //根据页码查询指定数据的方法
    SelectDataByPageIndex:function(){},
    //获取指定行数据
    GetRowData: function (index) {
        return this.data[index];
    },
    //页码
    Page_Layui: Page_Layui,
    //创建Layui的表格框架
    CreateTableFrame: function () {
        var width = 0;
        var colgroupHtml = "";
        var theadHtml = "";
        //序号字段
        if (this.CountNumberBool) {
            width = 50;
            colgroupHtml = "<col style='width:50;'>";
            theadHtml = "<th>序号</th>";
        }
        //列标题
        var Column = this.Column;
        for (var i = 0; i < Column.length; i++) {
            width += Column[i].width;
            colgroupHtml += "<col  width='" + Column.width + "'>";
            theadHtml += "<th style='" + (Column[i].Style == undefined ? "" : Column[i].Style) + "'>" + Column[i].txtName + "</th>";
        }
        //控制按钮 的列标题
        if (this.Edit != "" || this.Delete != "") {
            width += 120;
            colgroupHtml += "<col  width='120'>";
            theadHtml += "<th></th>";
        }
        //数据
        var tbody = "";
        if (this.data != undefined && this.data != null){
            if (this.data.length > 0) {
                for (var i = 0; i < this.data.length; i++) {
                    tbody += "<tr>";
                    //序号
                    if (this.CountNumberBool) {
                        tbody += "<td style='text-align: center;'>" + ((parseFloat(this.PageIndex) - 1) * this.PageSize + i + 1) + "</td>";
                    }
                    for (var j = 0; j < Column.length; j++) {
                        //单元格的样式
                        tbody += "<td style=\'" + (Column[j].Style == undefined ? "" : Column[j].Style) + "\'>";
                        var value = "";
                        //单元格的数据
                        if (Column[j].ValueDeal != undefined) {
                            value = Column[j].ValueDeal(this.data[i][Column[j].ValueCode]);
                        }
                        else {
                            value = this.data[i][Column[j].ValueCode];
                        }
                        tbody += (value == null ? "" : value) + "</td>";
                    }
                    //控制按钮
                    if (this.Edit != "" || this.Delete != "") {
                        tbody += "<td>";
                        //编辑按钮功能
                        if (this.Edit != "") {
                            tbody += "<button class='layui-btn layui-btn-normal layui-btn-mini' onclick='" + this.Edit + "(" + i + ")' > <i class='layui-icon'>&#xe642;</i></button>";
                        }
                        //删除按钮功能
                        if (this.Delete != "") {
                            tbody += "<button class='layui-btn layui-btn-normal layui-btn-mini' onclick='" + this.Delete + "(" + i + ")' > <i class='layui-icon'>&#xe640;</i></button>";
                        }
                        tbody += "</td>";
                    }
                    tbody += "</tr>";
                }
            }
        }
        //初始化页码对象
        this.Page_Layui = Page_Layui;
        //初始化分页容器的ID
        if (this.Page_Layui.PagePlaneID == "") {
            this.Page_Layui.PagePlaneID = new Date().getTime();
        }

        //需要创建页码
        if (this.SumDateCounte > 0) {
            width = width < 530 ? 530 : width;//控制容器的最小长度需要适配页码
        }


        var html = "<div style='width:" + width + "px;'>"
            + " <table class='layui-table' lay-skin='line'>"
            + "    <colgroup>"
            + colgroupHtml
            + "    </colgroup>"
            + "    <thead> "
            + "        <tr>"
            + theadHtml
            + "        </tr>"
            + "    </thead>"
            + "    <tbody >" + tbody + "</tbody>"
            + " </table>"
            + " <div id=\"" + this.Page_Layui.PagePlaneID + "\"></div>"
            + "</div>";
        $("#" + this.TablePanel).html(html);
        //创建页码
        if (this.SumDateCounte > 0) {
            this.Page_Layui.PageIndex = this.PageIndex;
            this.Page_Layui.PageSize = this.PageSize;
            this.Page_Layui.SumDateCounte = this.SumDateCounte;
            this.Page_Layui.SelectDataByPageIndex = this.SelectDataByPageIndex;
            this.Page_Layui.CreatePage();//创建页码
        }
    }
};


/*
 创建页码
 如果页面需要创建多个页码，则使用该语句：var Page_Layui1=Page_Layui,然后使用对象Page_Layui1去操作
 */
var Page_Layui = {
    //存放页码的容器
    PagePlaneID: "",
    //当前页
    PageIndex:1,
    //每页展示的数据量
    PageSize: 10,
    //数据的总数量
    SumDateCounte:0,
    //根据页码查询数据的方法
    SelectDataByPageIndex: function (PageIndex) { },
    //刷新当前页数据
    Refresh: function () { this.SelectDataByPageIndex(this.PageIndex); },
    CreatePage: function () {
        $("#"+this.PagePlaneID).css("width","100%");
        $("#"+this.PagePlaneID).css("text-align","right");
        var Page_Layui_PageSelect = this.SelectDataByPageIndex;
        var Page_Layui_PageID = this.PagePlaneID;
        var Page_Layui_PageIndex = this.PageIndex;
        var SumPage = Math.ceil(this.SumDateCounte / this.PageSize);
        layui.use(['laypage', 'layer'], function () {
            var laypage = layui.laypage
                , layer = layui.layer;
            laypage({
                cont: Page_Layui_PageID
                , curr: Page_Layui_PageIndex
                , pages: SumPage //总页数
                , groups: 5 //连续显示分页数
                , jump: function (obj, first) {
                    //得到了当前页，用于向服务端请求对应数据
                    if (!first) {
                        //$("#" + thisPageIndexLabelID).val(obj.curr);
                        Page_Layui_PageSelect(obj.curr);
                    }
                }
            });
        });
    }


};


//实例化一个表格控件对象
var thisTable = Table_Layui;
function SelectDataByPageIndex(PageIndex) {
    //每次查询获得的数据集--当前页的数据
    var Data = [
        { Name: "张三", Sex: "1", Age: "15" },
        { Name: "李四", Sex: "1", Age: "18" },
        { Name: "王五", Sex: "1", Age: "26" },
        { Name: "韩梅梅", Sex: "0", Age: "16" }
    ];
    thisTable.SumDateCounte = 100;//总数据的行数
    thisTable.data = Data;
    thisTable.PageIndex = PageIndex;//当前页码
    thisTable.CreateTableFrame();//创建table

}

//编辑方法
function Edit(index) {
    var json = Table_Layui.GetRowData(index);
    alert("有数据集了，自己处理编辑事件哦");

}

//删除方法
function Delete(index) {
    var json = Table_Layui.GetRowData(index);
    alert("我要放大招了，小心点！");
}




//表格控件初始化设置
function TableOnit() {
    thisTable.TablePanel = "Table";
    thisTable.Edit = Edit;
    thisTable.Delete = Delete;
    thisTable.SelectDataByPageIndex = SelectDataByPageIndex;
    thisTable.Column = [
        { txtName: "姓名", ValueCode: "Name", width: 80 },
        { txtName: "性别",
            ValueCode: "Sex",
            width: 80,
            ValueDeal: function (value) {
                if (value == 0) {
                    return "女";
                } else {
                    return "男";
                }
                return value;
            }
        },
        { txtName: "年龄", ValueCode: "Age", width: 80 }
    ];
}





$(function () {
    TableOnit(); //初始化表格
    SelectDataByPageIndex(1);//初始化查询
})



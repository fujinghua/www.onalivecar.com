1.把资源文件放入Corethink库目录中
在Public/libs目录下新建fakeloader/js、fakeloader/css目录，把资源文件拷贝到对应的目录

2.在Home/View/Public/layout.html中引入fakeloader的js和css

<link rel="stylesheet" href="__PUBLIC__/libs/fakeloader/css/fakeloader.css">
<script src="__PUBLIC__/libs/fakeloader/js/fakeloader.min.js"></script>
<script>
    $(document).ready(function(){
        $(".fakeloader").fakeLoader({
            timeToHide:1200,
            bgColor:"#3498db",
            spinner:"spinner3"
        });
    });
</script>

此处的layout之前已经引入了jquery库
注意js代码会依赖jquery,一定要出现在jquery的下面

3.增加显示加载动画的div
我是加在了

</body>的前边了

<div class="fakeloader"></div>

这样就OK了.
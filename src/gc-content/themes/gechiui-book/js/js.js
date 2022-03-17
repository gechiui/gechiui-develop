//给li加个id
$(function(){ var obj = $('.summary li'); obj.each(function(i){ $(this).attr('id',"wzlist"+i); }) })
//上一页下一页
$(function(){
//获取当前页面li的id值，并截取到纯数字
var numb = $('.navbox li.current-menu-item').attr('id');
	numb = numb.split("wzlist");
	numb = numb[1];
//获取下一个id的值，通过重组id 获取此id对应的href；	
var nextnumb = parseInt(numb)+1;
	nextnumb = "wzlist"+nextnumb;
var nextlink = $('.navbox li#'+nextnumb+' a').attr('href');
//替换下一页的链接
$("#xiayige").attr("href",nextlink);

//获取上一个的链接，操作同上
if(numb>0){
	var prevnumb = parseInt(numb)-1;
		prevnumb = "wzlist"+prevnumb;
	
	var prevlink = $('.navbox li#'+prevnumb+' a').attr('href');	
	$("#shangyige").attr("href",prevlink);
}
});
//默认配置
var gitbook = gitbook || [];
gitbook.push(function() {
    gitbook.page.hasChanged({
        "page": {
        },
        "config": {
            "pluginsConfig": {
                "fontsettings":{"theme":"white","family":"sans","size":2},
                "sharing": {
                    "all": []
                },
            },
        },
        "file": {
        },
        "gitbook": {
        },
        "basePath": ".",
        "book": {}
    });
});



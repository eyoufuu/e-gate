$(function() {
    $("#explore-nav li a").click(function() {
        var curList = $("#explore-nav li a.current").attr("rel");
        var $newList = $(this);
        var curListHeight = $("#all-list-wrap").height();
        $("#all-list-wrap").height(curListHeight);
        $("#explore-nav li a").removeClass("current");
        $newList.addClass("current");	
        var listID = $newList.attr("rel");
        if (listID != curList) {
            $("#"+curList).fadeOut(300, function() {
                $("#"+listID).fadeIn();
                var newHeight = $("#"+listID).height();
                $("#all-list-wrap").animate({
                    height: newHeight
                });
            });
        }        
        return false;
    });
});
$(document).ready(function(){
    var fileNum = 3;
    var picNum = 1;

    setInterval(function(){
        if(picNum == fileNum){
            picNum = 1;
        }// ENDS if(picNum == fileNum){
        else {
            picNum ++;
        }// ENDS else {
        var imgSrc = "images/sample_0" + picNum + ".jpg";
        $("#images_fade").fadeOut(3000, function(){
            $(this).attr("src", imgSrc).fadeIn(3000);
        });// ENDS $("#images_fade").fadeOut(3000, function(){
    }, 9000);//ENDS setInterval(function(){
//↑TOP画像切替***********************************************
/*

    //$("#search_btn").submit(function(){
    $('#search_btn').on('click', function() {
        $(".search_hide").css("display","none");
        var priceNum = parseInt($("select[name= 'budget']").val());
        var idName = "";

        if(priceNum == 5000){
            idName = $("#rank01").attr("id");//5000
        }//ENDS if(priceNum >= 4500){}
        else if(priceNum == 4000){
            idName = $("#rank02").attr("id");//4000
        }//ENDS else if(priceNum >= 3300){}
        else if(priceNum == 1350){
            idName = $("#rankA").attr("id");//1350
        }//ENDS else if(priceNum >= 1100){}
        else if(priceNum == 980){
            idName = $("#rankB").attr("id");//980
        }//ENDS else if(priceNum >= 900){}
        else if(priceNum == 780){
            idName = $("#rankC").attr("id");//780
        }//ENDS else if(priceNum >= 700){}
        else if(priceNum == 600){
            idName = $("#rankD").attr("id");//600
        }//ENDS else if(priceNum >=600){}
        else if(priceNum == 380){
            idName = $("#rankE").attr("id");//380
        }//ENDS else{}
        else if(priceNum == 0){
            idName = $("#rank00").attr("id");//380
        }//ENDS else{}
        $("section" + "#" + idName).css("display","block");
        return false;
    });//ENDS $("#myform").submit(function(){});

*/
//↑MENU表示切替***********************************************




//↑Contact Page Submit***********************************************



/*
    var navHeight = $('nav').height();
    // console.log(navHeight);
    // スクロール
    $('nav a').click(function(){
        var target = $(this).attr('href');
        // スクロールする
        $('html, body').animate(
            {scrollTop: $(target).offset().top - navHeight}
        );
        return false; //a要素本来の働きを無効化
    });

    $(window).scroll(function(){
        var scrollTop = $(window).scrollTop();

        //現在位置の表示
        $('.nav-pills > li.active').removeClass('active');
        if(scrollTop > $('#page-4').offset().top - navHeight){
            $('li[data-attribute="contact"]').addClass('active');
        }
        else if(scrollTop > $('#page-3').offset().top - navHeight){
            $('li[data-attribute="search"]').addClass('active');
        }
        else if(scrollTop > $('#page-2').offset().top - navHeight){
            $('li[data-attribute="new"]').addClass('active');
        }
        else if(scrollTop > $('#page-1').offset().top - navHeight){
            $('li[data-attribute="about"]').addClass('active');
        }
        else {
            $('li[data-attribute="top"]').addClass('active');
        }
    });


    $(".container-fluid .nav li").click(function(){
        $(".active").removeClass();
        $(this).addClass("active");
        var className = $(this).attr("class");
        console.log(className);
    });
*/

//↑Fixed Navigation Revise*******************************************
});// ENDS $(document).ready(function(){
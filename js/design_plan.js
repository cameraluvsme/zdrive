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
    $("#myform").submit(function(){
        $("section").css("display","none");
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
//↑MENU表示切替***********************************************

    var userName = "";
    $("#contact").submit(function(){
        // submitの選択範囲はフォーム全体です！！
        $("#send_btn").css({
            display:"none"
            });
        var userName = $("#user").val();
        $("#shopping_done span").text(userName);
        $("#shopping_done").css({
            display:"block"
            });
        return false;
    });// ENDS $("").submit(function(){

//↑Contact Page Submit***********************************************
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



//↑Fixed Navigation Revise*******************************************
});// ENDS $(document).ready(function(){

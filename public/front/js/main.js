$(document).ready(function () {
//owl carousel
    $("#owl-example").owlCarousel({
        navigation: true,
        navigationText: ["prev", "next"],
    });


    $("a.down").click(function () {
        $(this).next('ul').slideToggle();
        $(this).children().toggleClass("fa-toggle-up");
    });

//price range slider 
    $(function () {
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 500,
            values: [75, 300],
            slide: function (event, ui) {
                $("#amount").val("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
            }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) +
                " - $" + $("#slider-range").slider("values", 1));
    });
    //
    $("article.preview nav").show();
    var previewImg = $("img#main");
    $("a:first").addClass("active");
    $(".preview nav a img").click(function () {
        var link = $(this).parent();
        var linkHref = link.attr("href");
        var linkAlt = link.attr("alt");
        if ($(link).hasClass("active") == false)
        {
            $("a").removeClass("active");
            link.addClass("active");
            $(previewImg).animate({
                opacity: 0.8,
            }, 300, function () {
                previewImg.attr("src", linkHref);
                previewImg.attr("alt", linkAlt);
                $(this).animate({
                    opacity: 1,
                }, 300
                        );
            });
        }
        return false;
    });
    $("input").click(function () {
        $("p.more").fadeIn("slow");
    });

    //spinner 
    $('.productQuantity span a.decrease').click(function () {
        var txt = $('.productQuantity input').val();

        if (txt <= 1) {
            $('.productQuantity input').val(1);
        } else {
            txt--;
            $('.productQuantity input').val(txt);
        }

    });

    $('.productQuantity span a.increase').click(function () {
        var txt = $('.productQuantity input').val();
        txt++;
        $('.productQuantity input').val(txt);

    });

    //tabulator 
    $(function () {
        $("#tabs").tabs();
    });

    //toggle menuHide
    $('span.fa-cog').click(function(){
        $('.menuHide').toggle();
    });


    //change color on click
    $('.fa-heart-o').click(function () {
        $(this).toggleClass('fa-heart-o fa-heart');
    });
    
    

    //picture view
    $('.listView .fa-th-list').click(function () {
        $('.twelve-p').show();
        $('.twelve').hide();
    });
    $('.listView .fa-th').click(function () {
        $('.twelve-p').hide();
        $('.twelve').show();
    });
    
    //prikazivanje sadrzaja korpe, drugi zadatak

    $('.shopingCart').click(function () {
        $('.cartProducts').toggle();
    });

    $('.remove').click(function () {
        $(this).parent().parent().hide();

    });
    
    //show-hide password
    $('.showPass').click(function () {
        $(this).toggleClass('fa-eye fa-eye-slash');
        
        var type = $(this).parent().siblings().attr('type');
        
        
        if(type == 'password'){
            $(this).parent().siblings().attr('type', 'text');
        }else{
            $(this).parent().siblings().attr('type', 'password');
        }
        
     });
    
    //form
     $('#customer form').validator();
        
    $('a.companyForm').click(function (){
        setTimeout(function(){ $('#company form').validator(); }, 10);
        
    });
});

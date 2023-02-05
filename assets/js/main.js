(function ($) {
    "use strict";
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }

        if($(this).scrollTop() > 240) {
            $('.header-nav').addClass('header-nav-fixed');
        } else {
            $('.header-nav').removeClass('header-nav-fixed');
        }

    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    //Show password
    $('.show-password').click(function () {
        const parent = $(this).parent();
        if($(this).hasClass('hide'))  {

            $(this).removeClass('hide');
            $(this).addClass('show');

            $(parent).children('input').attr('type', 'text');

            $(this).children('i').removeClass('fa-eye');
            $(this).children('i').addClass('fa-eye-slash');
        } else {
            $(this).removeClass('show');
            $(this).addClass('hide');

            $(parent).children('input').attr('type', 'password');

            $(this).children('i').removeClass('fa-eye-slash');
            $(this).children('i').addClass('fa-eye');
        }
    });

    //Show nav sub
    $('.btn-show-nav-sub').click(function() {
        console.log('sdsjhdsh')
        if($('.nav-sub').hasClass('show')) {
            $('.nav-sub').removeClass('show');
        } else {
            $('.nav-sub').addClass('show');
        }
    })


    // Product Quantity
    $('.quantity button').on('click', function () {
        var button = $(this);
        var oldValue = button.parent().parent().find('input#soluong').val();
        var input = button.parent().parent().find('input#soluong');
        var btnAddCart = $('.btn-add-cart');

        var id = btnAddCart.attr('data-id');
        if (button.hasClass('btn-plus')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $(input).attr('value', newVal);

        $(btnAddCart).attr('href', `./insert-cart.php?id=${id}&soluong=${newVal}`);
    });

    // Silder main
    $(".slider-inner").slick({
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        cssEase: "ease-in-out",
        prevArrow: '<a type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></a>',
        nextArrow: '<a type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></a>'
    })


    //Product detail slider
    $(".product-slick-inner").slick({
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        easing: "linner",
        prevArrow: '<a type="button" class="slick-product-prev"><i class="fa-solid fa-chevron-left"></i></a>',
        nextArrow: '<a type="button" class="slick-product-next"><i class="fa-solid fa-chevron-right"></i></a>',
        asNavFor: "#slick-slide-navfor"
    });

    $("#slick-slide-navfor").slick({
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrow: false,
        focusOnSelect: true,
        asNavFor: ".product-slick-inner"
    });


    // Silder main
    $(".related-slick").slick({
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        cssEase: "ease-in-out",
        
    })
    
})(jQuery);

//upload file image
function uploadFile(inputFile, grid) {
    // Khởi tạo đối tượng FileReader
    const reader = new FileReader();

    // Lắng nghe trạng thái đăng tải tệp
    inputFile.addEventListener("change", (event) => {
        // Lấy thông tin tập tin được đăng tải
        const files  = event.target.files;
        
        // Đọc thông tin tập tin đã được đăng tải
        reader.readAsDataURL(files[0]);

        const getSizeImage = files[0].size;
        
        if(getSizeImage > 1024 * 800) {
            alert("Chỉ cho phép tải tệp tin nhỏ hơn 800KB");
        } 
            
        else{
            alert("Đăng tải tệp thành công");
            // Lắng nghe quá trình đọc tập tin hoàn thành
            reader.addEventListener("load", (event) => {
                // Lấy chuỗi Binary thông tin hình ảnh
                const img = grid.querySelector('img');

                img.setAttribute("src", event.target.result);
            })
        }
    })
}

const formSearch = $('.form-search');
const list = $('.search-history-list');

$('input[name="search"]').click(function(e) {
    $('.search-history').addClass('open');
})

$('input[name="search"]').keyup(function(e) {
    e.stopPropagation();
    let val = $(this).val();

    $.ajax({
        url: 'search.php',
        type: 'POST',
        dataType: 'html',
        data: {content: val}
    }).done(function(data) {
        $(list).html(data);
    })
});

$('.search-history').click(function(e) {
    e.stopPropagation();
});
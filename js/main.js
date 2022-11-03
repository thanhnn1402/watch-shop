(function ($) {
    "use strict";
    
    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Vendor carousel
    $('.vendor-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:2
            },
            576:{
                items:3
            },
            768:{
                items:4
            },
            992:{
                items:5
            },
            1200:{
                items:6
            }
        }
    });


    // Related carousel
    $('.related-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:2
            },
            768:{
                items:3
            },
            992:{
                items:4
            }
        }
    });


    // Product Quantity
    $('.quantity button').on('click', function () {
        var button = $(this);
        var oldValue = button.parent().parent().find('input').val();
        if (button.hasClass('btn-plus')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        button.parent().parent().find('input').val(newVal);
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
    })
    
})(jQuery);


// add data cart

function getParent(Element, Selector) {
    while (Element.parentElement) {
        if(Element.parentElement.matches(Selector)) {
            return Element.parentElement;
        } else {
            Element = Element.parentElement;
        }
    }
}

let dataCartStorage = JSON.parse(localStorage.getItem('cartData')) || [];

function addDataCart() {
    const btnAddCart = document.querySelectorAll('.btn-add-cart');

    btnAddCart.forEach((item, index) => {
        item.onclick = () => {
            let parent = getParent(item, '.product-item');
            let data = {};
            data.id = parent.getAttribute('data-index');
            data.name = parent.querySelector('.product-name').innerText;
            data.price = parent.querySelector('.product-price').innerText;
            data.quantity = parent.querySelector('input[name="quantity"]') || 1;
            data.thumbnail = parent.querySelector('.product-thumbnail').src;


            // nếu giỏ hàng không rỗng
            if(dataCartStorage.length > 0) {

                // tạo id lưu trữ sản phẩm
                let id;

                // lặp qua mảng giỏ hàng
                let check = dataCartStorage.some((item, index) => {
                    if(item.id === data.id) {
                        id = index;
                    }

                    return item.id === data.id;
                })

                // nếu sản phẩm đã tồn tại trong giỏ hàng, tăng số lượng sản phẩm thêm 1
                // ngược lại sẽ thêm sản phẩm vào giỏ hàng
                if(check) {
                    dataCartStorage[id].quantity += 1; 
                } else {
                    dataCartStorage.push(data);
                }

            } else {
                dataCartStorage.push(data);
            }

            // Lưu dữ liệu vào biến localStorage
            localStorage.setItem('cartData', JSON.stringify(dataCartStorage));
        }

        
    })
}

// get cart data
function getDataCart() {
    if(document.getElementById('table-cart')) {
        const tableCart = document.querySelector('#table-cart tbody');
        let htmls;
        if(dataCartStorage.length > 0) {
            htmls = dataCartStorage.map((item, index) => {
                return `
                    <tr>
                        <td class="align-middle">${index + 1}</td>
                        <td class="align-middle"><img src="${item.thumbnail}" alt="" style="width: 50px;"> ${item.name}</td>
                        <td class="align-middle">${item.price}</td>
                        <td class="align-middle">
                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-minus" >
                                    <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm bg-gray-200 text-center" value="${item.quantity}">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-plus">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">$${(item.price).replace('$', '') * item.quantity}</td>
                        <td class="align-middle"><button class="btn btn-sm btn-primary"><i class="fa fa-times"></i></button></td>
                    </tr>
                `;
            })
        }
    
        tableCart.innerHTML = htmls.join('');
    
    }
}

addDataCart();
getDataCart();



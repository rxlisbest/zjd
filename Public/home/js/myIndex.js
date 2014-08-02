/* SVN.committedRevision=1060897 */

var _indexObject= {
        goodsCollectionFun:function() {
            var a=$(".mod_vs_one");
            a.delegate(".box_filter","mouseenter",function() {
                    var b=$(this);
                    b.find(".filter").stop().animate( {
                            height:"94px",opacity:0.9
                        }
                    );
                    b.find(".font_lay").stop().animate( {
                            height:"94px",opacity:0.9
                        }
                    )
                }
            );
            a.delegate(".box_filter","mouseleave",function() {
                    var b=$(this);
                    b.find(".filter").stop().animate( {
                            height:"0px",opacity:0
                        }
                    );
                    b.find(".font_lay").stop().animate( {
                            height:"0px",opacity:0
                        }
                    )
                }
            )
        },
        booksCollectionFun:function() {
            var a=$(".book_vs_one");
            a.delegate(".book_filter","mouseenter",function() {
                    var b=$(this);
                    b.find(".bfilter").stop().animate( {
                            height:"150px",opacity:0.9
                        }
                    );
                    b.find(".bfont_lay").stop().animate( {
                            height:"150px",opacity:0.9
                        }
                    )
                }
            );
            a.delegate(".book_filter","mouseleave",function() {
                    var b=$(this);
                    b.find(".bfilter").stop().animate( {
                            height:"0px",opacity:0
                        }
                    );
                    b.find(".bfont_lay").stop().animate( {
                            height:"0px",opacity:0
                        }
                    )
                }
            )
        }
        ,initFun:function() {
            this.goodsCollectionFun();
            this.booksCollectionFun();
        }
    }
    ;
function initPointDiv() {
    var js_element=document.createElement("script");
    var js_element_url=$("#index_pointIndexDivJs").val();
    js_element.setAttribute("type","text/javascript");
    js_element.setAttribute("src",js_element_url);
    document.getElementsByTagName("HEAD").item(0).appendChild(js_element);
    var css_element=document.createElement("link");
    var css_element_url=$("#index_pointIndexDivCss").val();
    css_element.setAttribute("type","text/css");
    css_element.setAttribute("href",css_element_url);
    css_element.setAttribute("rel","stylesheet");
    document.getElementsByTagName("HEAD").item(0).appendChild(css_element);
    var url=URLPrefix.pointShop+"/pointshop/initMyIndexPoint.do?callback=?";
    jQuery.getJSON(url,function(data) {
            jQuery("#point_exchange").html(data.value);
            $("ul#point_exchange").easyPaginate( {
                    step:1,loop:true,clickstop:false,pause: 2000
                }
            );
            eval("pointInit()")
        }
    )
}
$(document).ready(function() {
        _indexObject.initFun();
        initPointDiv();
        $(".getCouponMyyhdIdx").click(function(b) {
                var a=$(this).attr("getCouponUrl");
                location.href=a;
                b.stopPropagation();
                b.preventDefault()
            }
        );
        $(".getGiftCardMyyhdIdx").click(function(b) {
                var a=$(this).attr("getGiftCardUrl");
                location.href=a;
                b.stopPropagation();
                b.preventDefault()
            }
        )
    }
);
(function ($) {
  "use strict";
  couponZenCopy();
  couponzenPagination();

  // Ajax All Search request
  $(document).ready(function () {
    $(".couponzen__search-input").keyup(function (e) {
      var $this = $(this);
      clearTimeout($.data(this, "timer"));
      if (e.keyCode == 13) {
        doSearch($this);
      } else {
        doSearch($this);
        $(this).data("timer", setTimeout(doSearch, 100));
      }
    });
    
    // Ajax Catagory Search request
    $(".couponzen__nav-tabs li").on("click", function () {
      document.getElementById("couponzen__search-input-field").value = "";
      // Coupon Event
      var couponzenevent = $(this).find("button").data("couponevent");
      // Coupon Category
      var couponzenCategorie = $(this).find("button").data("category");
      //Shortcode attributes
      var options = $(this).closest('.couponzen_options').data('couponzenoptions');
      
      $.ajax({
        url: couponzen.ajaxurl,
        data: {
          action: "couponzen_category_search",
          couponzenCategorie: couponzenCategorie,
          couponzenevent: couponzenevent,
          options: options,
          nonce: couponzen.ajaxnonce,
        },
        beforeSend: function () {
          $(".couponzen__body").addClass("beforeLoader");
          $(".couponzen-loader").addClass("couponzen-after-loader");
        },
        success: function (response) {
          $(".couponzen-ajax-search").html(response);
          couponZenCopy();
          couponzenPagination();
          $(".couponzen__body").removeClass("beforeLoader");
          $(".couponzen-loader").removeClass("couponzen-after-loader");
        },
        error: function (errorThrown) {
          console.log(errorThrown);
        },
      });
    });

    // Cuponzen Pagination
    $(document).on("click", ".cuponzen-pagination a", function () {
      // Coupon Category
      var couponzenkey = $(this).data().couponzenkey;
      // Coupon Event
      var couponzenevent = $(this).data().couponevent ?? '';
      // Shortcode Attributes
      var options = $(this).closest('.couponzen_options').data('couponzenoptions');
      
      var page = $(this).data().page;
      $.ajax({
        url: couponzen.ajaxurl,
        data: {
          action: "couponzen_category_search",
          couponzenCategorie: couponzenkey,
          couponzenevent: couponzenevent,
          page: page,
          options: options,
          nonce: couponzen.ajaxnonce,
        },
        beforeSend: function () {
          $(".couponzen__body").addClass("beforeLoader");
          $(".couponzen-loader").addClass("couponzen-after-loader");
        },
        success: function (response) {
          $(".couponzen-ajax-search").html(response);
          couponZenCopy();
          $(".couponzen-ajax-search").addClass("couponzen__row");
          couponzenPagination();

          $(".couponzen-loader").removeClass("couponzen-after-loader");
          $(".couponzen__body").removeClass("beforeLoader");
        },
        error: function (errorThrown) {
          console.log(errorThrown);
        },
      });
    });

    //Cuponzen Search Pagination
    $(document).on("click", ".cuponzen-search-pagination a", function () {
      // Coupon Event
      var couponzenevent = $('.couponzen__nav-link')[0].dataset.couponevent;
      // Coupon category
      var couponzenkey = $(this).data().couponzenkey;
      var page = $(this).data().page;
      $.ajax({
        url: couponzen.ajaxurl,
        data: {
          action: "couponzen_search",
          page: page,
          s: couponzenkey,
          couponzenevent : couponzenevent,
          nonce: couponzen.ajaxnonce,
        },
        beforeSend: function () {
          $(".couponzen__body").addClass("beforeLoader");
          $(".couponzen-loader").addClass("couponzen-after-loader");
        },
        success: function (response) {
          $(".couponzen-ajax-search").html(response);
          couponZenCopy();
          $(".couponzen-ajax-search").addClass("couponzen__row");
          couponzenPagination();
          $(".couponzen-loader").removeClass("couponzen-after-loader");
          $(".couponzen__body").removeClass("beforeLoader");
        },
        error: function (errorThrown) {
          console.log(errorThrown);
        },
      });
    });
  });

  //Coupon Code copy
  function couponZenCopy() {
    $(".couponzen-copy-code").on("click", function (e) {
      couponZenCopyToClipboard(
        $(this).closest(".couponzen-shareable-code").find(".coupon-copy-code"),
        this
      );
    });
  }

  //couponzen Pagination
  function couponzenPagination() {
    // Ajax Pagination
    $(".couponzen-ajax-search").on(
      "click",
      ".ajaxpagination a",
      function (event) {
        event.preventDefault();

        var link = $(this).attr("href");
        $(".couponzen-ajax-search").addClass("loading");

        $(".couponzen-ajax-search").load(link + " .ajaxcontent", function () {
          $(".couponzen-ajax-search").removeClass("loading");
        });
      }
    );
  }

  // Click to Copy
  function couponZenCopyToClipboard(element, button) {
    var $tempdata = $("<input>");
    $("body").append($tempdata);
    $tempdata.val($(element).text()).select();
    document.execCommand("copy");
    $tempdata.remove();
    $(button).addClass("copied");
    $(button).attr("data-copytext", couponzen.couponBtnClick);
    setTimeout(function () {
      $(button).removeClass("copied");
      $(button).attr("data-copytext", couponzen.couponBtnHover);
    }, 1000);
  }

  //Coupon Search
  function doSearch($this = "") {
    if ($this.length > 0) {
      var searchString = $this.val();
      
      $(".couponzen__nav-link").removeClass("active");
      $(".scarch-item").addClass("active");

      if(searchString == ''){
        var couponzenevent = $('.couponzen__nav-link')[0].dataset.couponevent;
        allCoupon_search(searchString, couponzenevent);
      }

      //wasn't enter, not < 2 char
      if ( searchString.length >= 2 ){
        var couponzenevent = $('.couponzen__nav-link')[0].dataset.couponevent;
        allCoupon_search(searchString, couponzenevent);
      }
    }
  }

  function allCoupon_search(searchString, couponzenevent){
    $.ajax({
      url: couponzen.ajaxurl,
      data: {
        action: "couponzen_search",
        s: searchString,
        couponzenevent: couponzenevent,
        nonce: couponzen.ajaxnonce,
      },
      beforeSend: function () {
        $(".couponzen__body").addClass("beforeLoader");
        $(".couponzen-loader").addClass("couponzen-after-loader");
      },
      success: function (response) {
        $(".couponzen-ajax-search").html(response);
        $(".couponzen-ajax-search").addClass("couponzen__row");

        couponZenCopy();
        couponzenPagination();
        $(".couponzen-loader").removeClass("couponzen-after-loader");
        $(".couponzen__body").removeClass("beforeLoader");
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    }).done(function (response) {});
  }

  $('.couponzen_options').each(function(){
    let couponzenevent = ''
    if($('.couponzen__nav-link').length) {
      couponzenevent = $('.couponzen__nav-link')[0].dataset.couponevent && $('.couponzen__nav-link')[0].dataset.couponevent;
    }
    //Shortcode attributes
    var options = $(this).data('couponzenoptions');
    $.ajax({
      url: couponzen.ajaxurl,
      data: {
        action: "couponzen_category_search",
        couponzenCategorie: "all",
        couponzenevent: couponzenevent,
        options: options,
        nonce: couponzen.ajaxnonce,
      },
      beforeSend: function () {},
      success: function (response) {
        $(".couponzen-ajax-search").html(response);
        couponZenCopy();
        couponzenPagination();
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  });
})(jQuery);
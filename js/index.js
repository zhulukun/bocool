$(function() {
  var scroll = $(".scroll_page");
  scroll.find(".content").eq(0).show();
  scroll.scrollpage({
    scroll_child: '.page',
    easing: '',
    next: ".next",
    prev: ".prev",
    time: 500,
    complete: function() {
      var curr = $(".curr");
      /*console.log(curr.index());*/
      curr.find(".content").slideDown(500, function() {
        curr.siblings().find(".content").slideUp(500);
      });
    }
  });

  $('#li-bottom li').click(function(){ 
      
      var index = $(this).index();
      $('.news-list li').css('display','none');
      $('.news-list li').eq(index).css('display','block');
      $('#li-bottom li').removeClass("active");
      $(this).addClass("active");

     });

});

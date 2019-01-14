jQuery(document).ready(function($){
  $(".drupaletexp-animate").each(function(){
    var $this = $(this);
    var animate_class = $this.data('animate');
    $this.appear(function(){
      $this.addClass(animate_class);
      $this.addClass('animated');
    },{
      accX: 0,
      accY: 0,
      one:false
    });	
  });
})
<style>
.modal-content {
    border: 3px solid grey;
    padding: 15px;
    text-align: center;
}
</style>
<div class="modal" id="questionmodal">
  <div class="modal-dialog" style="top: 35%;">
    <div class="modal-content">
      <div class="modal-body modalcarousel active first">
        Will you be selling feed at this event?
        <div align="center" class="mt-3">
          <button type="button" class="btn questionmodal_feed model_btn questionmodal_btn" value="1">Yes</button>
          <button type="button" class="btn questionmodal_feed model_btn questionmodal_btn" value="2">No</button>
       </div>
      </div>
      <div class="modal-body modalcarousel displaynone">
        Will you be selling shavings at this event? 
        <div align="center"  class="mt-3">
          <button type="button" class="btn questionmodal_shaving model_btn questionmodal_btn" value="1" >Yes</button>
          <button type="button" class="btn questionmodal_shaving model_btn questionmodal_btn" value="2">No</button>
        </div>
      </div>
      <div class="modal-body modalcarousel displaynone">
        Will you have RV Hookups at this event?
        <div align="center" class="mt-3">
          <button type="button" class="btn questionmodal_rv model_btn questionmodal_btn" value="1">Yes</button>
          <button type="button" class="btn questionmodal_rv model_btn questionmodal_btn" value="2">No</button>
        </div>
      </div>
      <div class="modal-body modalcarousel displaynone">
        How will you be charging for your stalls?
        <div class="d-flex flex-wrap flex-column align-items-center">
          <button type="button" class="btn questionmodal_charging model_btn m-2 w-50 questionmodal_btn" value="1">Per Week</button>
          <button type="button" class="btn questionmodal_charging model_btn m-2 w-50 questionmodal_btn" value="2">Per Month</button>
          <button type="button" class="btn questionmodal_charging model_btn m-2 w-50 questionmodal_btn" value="3">Flat Rate</button>
        </div>
      </div>
      <div class="modal-body modalcarousel displaynone">
        Send a text message to users when their
        stall is unlocked and ready for use?
        <div align="center" class="mt-3">
          <button type="button" class="btn questionmodal_notification model_btn questionmodal_btn" value="1">Yes</button>
          <button type="button" class="btn questionmodal_notification model_btn questionmodal_btn" value="2" >No</button>
        </div>
      </div>
      <div class="modal-body modalcarousel displaynone last">
        Thank You Fill out your custom event form with your stalls,
          and let your customers rest EZ!
          <div align="center" class="mt-3">
            <button type="button" class="btn questionmodalsubmit model_btn" data-bs-dismiss="modal">Go</button>
          </div>
      </div>
      <div class="d-flex">
        <a href="javascript:void(0);" class="model_arrow_left modalcarousel_prev displaynone"><i class="fas fa-chevron-left"></i></a>
        <a href="javascript:void(0);" class="model_arrow_right modalcarousel_next" align="right"><i class="fas fa-chevron-right"></i></a>
      </div>
    </div>
  </div>
</div>

<script>
  $('.modalcarousel_next').click(function(){
    var nextsibling = $('.modalcarousel.active').next('.modalcarousel');
    $('.modalcarousel').addClass('displaynone').removeClass('active');
    nextsibling.addClass('active').removeClass('displaynone');
    
    $('.modalcarousel_prev').removeClass('displaynone');
    if(nextsibling.hasClass('last')) $('.modalcarousel_next').addClass('displaynone');
  })
  $('.modalcarousel_prev').click(function(){
    var prevsibling = $('.modalcarousel.active').prev('.modalcarousel');
    $('.modalcarousel').addClass('displaynone').removeClass('active');
    prevsibling.addClass('active').removeClass('displaynone');
    
    $('.modalcarousel_next').removeClass('displaynone');
    if(prevsibling.hasClass('first')) $('.modalcarousel_prev').addClass('displaynone');
  })
  $('.questionmodal_btn').click(function(){
    var nextsibling = $(this).parent().parent().next('.modalcarousel');
    $('.modalcarousel').addClass('displaynone').removeClass('active');
    nextsibling.addClass('active').removeClass('displaynone');
    
    $('.modalcarousel_prev').removeClass('displaynone');
    if(nextsibling.hasClass('last')) $('.modalcarousel_next').addClass('displaynone');
  })

</script>


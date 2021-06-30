<?php
use Core\{H,FH};
 ?>
 <?php ob_start(); ?>

<div class="modal fade" id="addReviewForm" tabindex="-1" aria-labelledby="addReviewFormLabel" data-target="#addReviewForm" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Write Review</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div  id="scroll-wrap">
            <p id="dynamic-text">

            </p>
          </div>
        </div>
        <form  class="text-center" method="POST" id="reviewForm">
          <input type="hidden" id="product_id" name="product_id" value="">
          <div class="row">
            <div class="col-md-6">
              <div class="rating" id="average" data-rating="" ></div>
            </div>
            <div class="col-md-6">
              <div class="starrr"></div>
            </div>
          </div>
        </form>
          <div class="mb-3">
            <?= FH::inputBlock('text','Name','name',$this->ratings->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6']) ?>
          </div>
          <div class="mb-3">
            <?= FH::textareaBlock('Review','review',$this->ratings->review,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12']) ?>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal()" aria-label="close"><span aria-hidden="true">Close</span></button>
        <button type="submit" class="btn btn-primary" onclick="saveReview()">Submit</button>
      </div>
    </div>
  </div>
</div>
<script src="<?=PROOT?>js/jQuery-3.3.1.min.js"></script>
<script>

$(function(){
      $("#addReviewForm").on("hidden.bs.modal", function (e) {
        location.reload();
      });
  $("#addReviewForm").on("shown.bs.modal", function (e) {
  var id = document.getElementById("product_id").value;
  var rat = document.getElementsByClassName("rating");
  for (var a = 0; a < rat.length; a++)
  {
    $(rat[a]).starrr({
      readOnly: true,
      rating: rat[a].getAttribute("data-rating")
    });
  }
  console.log(id);
});
});

function saveReview(){
  var id = jQuery('#product_id').val();
  var name = jQuery('#name').val();
  var review = jQuery('#review').val();
  console.log(ratings);
  jQuery.ajax({
    url : '<?=PROOT?>categories/savereview',
    method: "POST",
    data : {
      "product_id" : id,
      "name" : name,
      "review" : review,
      "rating" : ratings
    },
    success: function(resp){
      console.log(resp);
      if(resp.success){
        alert("Review Successfully Saved");
        jQuery('#addReviewForm').modal('hide');
      }else {
         alert("Review, Name, or Star rating is missing");
        }
      }
    });
  }


function closeModal(){
  jQuery('#addReviewForm').modal('hide');

}

</script>
<?php echo ob_get_clean(); ?>

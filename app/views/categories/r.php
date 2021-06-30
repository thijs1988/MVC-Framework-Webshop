<?php
  use Core\FH;
  use Core\H;
?>

<div class="modal fade" id="" tabindex="-1" aria-labelledby="" data-target="#" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Write Review</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div id="scroll-wrap">
oiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygfoiudhfgoihsdfgoishfogihfoighoidfhgdsjhfusefusdfuydsfgyisufgyiusydgfusdygf
        </div>
        <?php
        $av = 0;
        foreach ($this->average as $average) {
          if ($product->id == $average->product_id){
            $av = $average->total / $average->amount;
          }
        }
        $av = round($av,0, PHP_ROUND_HALF_UP);
        ?>
        <form  class="text-center" method="POST" onsubmit="return saveRatings(this);">
          <input type="hidden" name="product_id" value="<?=$product->id?>">
          <div class="row">
            <div class="col-md-6">
              <div class="ratings" data-rating="<?php echo $av;?>"></div>
            </div>
            <div class="col-md-6">
              <div class="starrr"></div>
            </div>
          </div>
        </form>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Name:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">Close</span></button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
var ratings = null;

$(function () {
  $(".starrr").starrr().on("starrr:change", function (event, value){
    //alert(value);
    ratings = value;
  });

  var rat = document.getElementsByClassName("ratings");
  for (var a = 0; a < rat.length; a++)
  {
    $(rat[a]).starrr({
      readOnly: true,
      rating: rat[a].getAttribute("data-rating")
    });
  }
});

function saveRatings(form){
  var product_id = form.product_id.value;
  $.ajax({
    url: "<?=PROOT?>/categories/save",
    method: "POST",
    data:{
      "product_id": product_id,
      "ratings": ratings
    },
    success: function (response) {
      alert(response);
    }
  });
  return false;
}
</script>

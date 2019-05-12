 <!--- Bottombar Content --->
 
 <div id="bottom" class="bottombar">
 	<a class="close-button" onclick="closeNav()">&times;</a>
    <div class="col-xs-12 col-md-12 col-sn-12 col-xs-12">
    	<div class="col-lg-6 col-md-6 col-sn-12 col-xs-12">    
            <div class="checkbox"><label class="checkbox-label">
                <input type="checkbox" name="CheckAll" id="chk_all" />
                    <span class="cr"><i class="cr-icon material-icons">check</i></span>
                </label>
            </div>
            <div class="counter">
                <label class="count" id="checked-counter">1</label>
                <label>Records Selected</label>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sn-12 col-xs-12">
            <ul class="list-unstyled">     
            	<li><a href="javascript:;" class="fa fa-trash delete_all" data-url="<?= NOTIFICATION_DELETE_URL; ?>"></a></li>
                 
            </ul>
        </div>
    </div>
 </div>
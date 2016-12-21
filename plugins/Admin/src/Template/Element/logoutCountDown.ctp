    <!-- POPUP -->
    <!-- Modal -->
    <div class="modal fade" id="popup_logoutCountDown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" aria-label="Close" id="logoutCountDown_Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Session Timeout Remaining</h4>
          </div>
          <div class="modal-body">
            Your secure connection to This Website is about to time-out. Would you like to remain logged on? 
            <br/><br/>
            Time Remaining <span id='logoutCountDownLabel'></span>
          </div>
          <div class="modal-footer">
            <button id='logoutCountDown_Yes' type="button" class="btn btn-primary">Yes</button>
            <button id='logoutCountDown_No' type="button" class="btn btn-primary">No</button>
          </div>
        </div>
      </div>
    </div>
    <!-- END POPUP-->
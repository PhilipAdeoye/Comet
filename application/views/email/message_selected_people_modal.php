<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal fade" id="messageSelectedPeopleModal" 
     data-backdrop="static"
     data-get-send-message-form-url="<?php echo base_url('email/get_send_message_form') ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    Send a Message 
                    <a tabindex="0" class="left-gutter" role="button" id="hintToCopyPopover"
                       data-toggle="popover" 
                       data-trigger="focus" 
                       data-placement="bottom"
                       data-content="You can copy the email addresses and use Gmail, Umail, Yahoo, etc, if you want to 
                        allow people to reply or keep an email chain">
                        <button class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-copy" style="color:#9c27b0;font-size:medium"></span>
                        </button>
                    </a>
                </h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('email/send_message', array('class' => 'form-horizontal', 'id' => 'sendMessageForm')) ?>
                <div class="form-content">

                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-form-id="sendMessageForm" disabled>Send</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        
        var messageSelectedPeopleModal = $("#messageSelectedPeopleModal");
        
        messageSelectedPeopleModal.find("#hintToCopyPopover").popover();

        messageSelectedPeopleModal.on("click", ".modal-footer > .btn-primary", function () {
            var primaryBtn = $(this);
            var form = $("#" + primaryBtn.data("formId"));
            if (form.length > 0) {
                primaryBtn.prop("disabled", true);
                $.post(form.prop('action'), form.serialize())
                    .done(function (data) {
                        messageSelectedPeopleModal.find(".modal-body .form-content").html(data);
                        var errors = messageSelectedPeopleModal.find(".modal-body .validation-errors-alert");

                        if (!errors.is(":visible")) {
                            toastrRegularSuccess("Your message has been sent to all recipients", "Success!");
                            messageSelectedPeopleModal.modal("hide");
                        } else {
                            primaryBtn.prop("disabled", false);
                        }
                    })
                    .fail(function () {
                        toastrStickyError("We are unable to send your message", "Error!");
                    });
            }
        })
    })
</script>


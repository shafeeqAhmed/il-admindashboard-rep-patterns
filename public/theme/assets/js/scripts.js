(function (window, undefined) {
    'use strict';

    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */
    function ajaxCall(url,type,data={}) {
        $.ajax({
            url: url,
            type: type,
            data: data,
            success: function (data) {
                if (data.status) {
                    toastr.success(data.message, 'Alert Message');
                    // $("#boat_type_modal").modal('hide')
                    setTimeout(function() {
                        window.location.reload();
                    }, 500);
                } else {
                    toastr.error(data.message, 'Alert Message');

                }

            }
        });

    }

    $(document).on('click', ".deleteBoatType", function () {
        var boat_type_uuid = $(this).attr('data-uuid');
        $("#boat_type_modal").modal('show').attr('data-uuid', boat_type_uuid);
    });

    $(document).on('click', "#yesDeleteBoatType", function () {
        var boat_type_uuid = $("#boat_type_modal").attr('data-uuid');
        $.ajax({
            url: siteUrl + 'boatTypes/' + boat_type_uuid,
            type: 'DELETE',
            success: function (data) {
                if (data.status) {
                    toastr.success(data.message, 'Boat Type');
                    $("#boat_type_modal").modal('hide')
                    setTimeout(function() {
                        window.location.reload();
                    }, 500);
                } else {
                    toastr.error(data.message, 'Boat Type');

                }

            }
        });
    });

    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#boat_type_picture_preview').attr('src', e.target.result).removeClass('hidden');
            }

            reader.readAsDataURL(input.files[0]);
            $("#"+id).removeClass('hidden');
        }
    }

    // for image preview
    /*** Sub Category Related Code ***/
    $(document).on('change', "#boat_type_picture", function()
    {
        readURL(this, 'delete_boat_type_picture');
    });

    $(document).on('click', "#delete_boat_type_picture", function()
    {
        $('#boat_type_picture').val('');
        $('#boat_type_picture_preview').attr('src', '').addClass('hidden');
        $(this).addClass('hidden');

    });

    $(document).on('click', ".cancelClick", function()
    {
        $('#picture_preview').attr('src', '');
        $("#deleteCategoryPicture").addClass('hidden');
    });


//reported post

    $(document).on('click', ".reported_post", function () {
        var reported_post_uuid = $(this).attr('data-uuid');
        var type = $(this).attr('data-type');
        $("#reported_post_modal").modal('show').attr('data-uuid', reported_post_uuid);
        $("#reported_post_modal").modal('show').attr('data-type', type);
    });

    $(document).on('click', "#yes_block_reported_post", function () {
        var reported_post_uuid = $("#reported_post_modal").attr('data-uuid');
        var type = $("#reported_post_modal").attr('data-type');


        var reason = $("#reported_post_reason_for_block").val();
        $.ajax({
            url: siteUrl + 'posts/reported/' + reported_post_uuid,
            type: 'PUT',
            data: {reason,type},
            success: function (data) {
                if (data.status) {
                    toastr.success(data.message, 'Boat Type');
                    $("#boat_type_modal").modal('hide')
                    setTimeout(function() {
                        window.location.reload();
                    }, 500);
                } else {
                    toastr.error(data.message, 'Boat Type');

                }

            }
        });
    });
    // Wizard tabs with icons setup
    $(".boat-detail-tab-steps").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: 'Update'
        },
        onFinished: function (event, currentIndex) {
            console.log(event)
            let url = siteUrl+'boat/'+$('#form_wizard_boat_uuid').val()
            let is_approved = $('#form_wizard_boat_action_type').val()
            ajaxCall(url,'PUT',{is_approved})
        }
    });
})(window);

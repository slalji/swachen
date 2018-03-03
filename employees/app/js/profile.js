
$(document).ready(function() {
    $('#messages').hide();
    var expiry = $('#expiry_date').html();
    var d = new Date();
    var curr_date = d.getDate();
    var curr_month = d.getMonth() + 1;
    if (curr_month < '10')
        var new_month = '0' + curr_month;
    var curr_year = d.getFullYear();
    var today = curr_year + '-' + new_month + '-' + curr_date;

    if (expiry == today){
        $('#messages').show();
    }
     
    $('input').focus(function () {
        //$('.success').hide();
        //$('.errors').hide();
    });

        $("#submit").click(function () {
            //$('.success').hide();
            //$('.errors').hide();

            var email = $('#email').val();
            var temp = $('#temppass').val();
            var pass = $('#newpass').val();
            var conpass = $('#confirmpass').val();


            //$('body').addClass('loading');
            $.ajax({
                type: "POST",
                url: "../ajax/updatePassword.php", //Relative or absolute path to response.php file
                data: {'email':email, temppass:temp, newpass:pass, confirmpass:conpass},
                success: function (msg) {
                    //alert(msg);

                    if (msg == 'db updated') {
                        $('.error').hide();
                        $('.success').show();
                        $('.success').html('<br><span class="badge badge-success">Password changed</span>');
                        $('#temppass').val('');$('#newpass').val('');$('#confirmpass').val('');
                    }
                    else {
                        $('.error').show();
                        $('.error').html('<br><span class="badge badge-danger">' + msg + '</span>');
                        //alert('error '+JSON.stringify(msg));
                    }
                },
                error: function (msg) {
                    alert('error'.JSON.stringify(msg));
                }
            });
            return false;
        });
    $("#update").click(function () {

        $('.success').hide();
        $('.errors').hide();
        var fullname = $('#fullname').val();
        var email2 = $('#email2').val();


        //$('body').addClass('loading');
        $.ajax({
            type: "POST",
            url: "../ajax/updateFullname.php", //Relative or absolute path to response.php file
            data: {'fullname':fullname, email:email2},
            success: function (msg) {
                //alert(msg);

                if (msg !='0') {
                    $('.error').hide();
                    $('.success').show();
                    $('.success').html('<br><span class="badge badge-success">Full name changed</span>');
                    $('#fullname').val(fullname);
                    $('.fullname').html(fullname);

                }
                else {
                    $('.error').show();
                    $('.error').html('<br><span class="badge badge-danger">Error: Name was not updated!</span>');
                    //alert('error '+JSON.stringify(msg));
                }
            },
            error: function (msg) {
                alert('error'.JSON.stringify(msg));
            }
        });
        return false;
    });
    });
 
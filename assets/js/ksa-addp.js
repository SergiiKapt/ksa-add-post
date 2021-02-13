(function ($) {
    $(document).ready(function () {
        const {__, _x, _n, _nx} = wp.i18n;

        function makeRequest(data) {
            httpRequest = new XMLHttpRequest();

            if (!httpRequest) {
                $('#ksa__addp__submit').removeAttr('disabled');
                var message = {
                    status: 'error',
                    msg: __('Giving up :( Cannot create an XMLHTTP instance')
                }
                alertMsg(message);
                return false;
            }
            httpRequest.onreadystatechange = alertContents;
            httpRequest.open('POST', ksa_addp_data.url);
            httpRequest.send(data);
        }

        function alertContents() {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                $('#ksa__addp__submit').removeAttr('disabled');
                if (httpRequest.status === 200) {
                    var response = JSON.parse(httpRequest.response);
                    alertMsg(response)
                } else {
                    var message = {
                        status: 'error',
                        msg: __('There was a problem with the request')
                    }
                    alertMsg(response);
                }
            }
        }

        $('#ksa__addp__submit').on('click', function () {
            if (!$('#ksa__addp__title').val().length) {
                var message = {
                    status: 'error',
                    msg: __('Title is empty')
                }
                alertMsg(message);
                return;
            }
            $(this).attr('disabled', 'disabled');
            var formData = new FormData();
            formData.append("action", 'ksa_addp_submit');
            formData.append("_wpnonce", $('#_wpnonce').val());
            formData.append("title", $('#ksa__addp__title').val());
            formData.append("text", $('#ksa__addp__text').val());

            makeRequest(formData);
        });

        function alertMsg(data) {
            $('#alert__msg').remove();
            if (data.status == 'success') {
                $('#ksa__addp__title').val('');
                $('#ksa__addp__text').val('');
            }
            var blockMsg = '<p id="alert__msg" class="alert__msg ' + data.status + '">' + data.msg + '</p>';
            $('#ksa__addp').prepend(blockMsg);
        }
    });
})(jQuery);

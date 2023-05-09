$(document).on('rex:ready', function (event, container) {
    initStatusToggle(container);
});


function initStatusToggle(container) {
    // status select
    if (container.find('[data-status="choice"]').length) {
        var statusChange = function () {
            var $this = $(this);

            updateDatasetStatus($this, $this.val(), function (resp) {
            });
        };
        container.find('[data-status="choice"]').change(statusChange);
    }
}

function updateDatasetStatus($this, status, callback) {

    $('#rex-js-ajax-loader').addClass('rex-visible');
    if (confirm(' Akkreditierung ändern?')) {

        $.get(document.URL + '&rex-api-call=choice_status', {
            data_id: $this.data('id'),
            table: $this.data('table'),
            token: $this.data('token'),
            status: status
        }, function (resp) {
            callback(resp);
            $('#rex-js-ajax-loader').removeClass('rex-visible');
        });
    } else {
        window.location.reload();
    }
}

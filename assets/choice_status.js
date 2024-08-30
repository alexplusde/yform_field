$(document).on('rex:ready', function (event, container) {
    initStatusToggle(container);
});


function initStatusToggle(container) {
    // status select
    if (container.find('[data-status="choice_status_select"]').length) {

        var statusChangeSelect = function () {
            var $this = $(this);

            updateDatasetStatus($this, $this.val(), function (resp) {
            });
        };
        container.find('select[data-status="choice_status_select"]').change(statusChangeSelect);
        
        /*
        var statusChangeButtons = function () {
            var $this = $(this);

            updateDatasetStatus($this, $this.val(), function (resp) {
                var $parent = $this.parent();
                $parent.html(resp.message.element);
                $parent.children('select:first').change(statusChange);
            });
        };
        container.find('div[data-status="choice_status"] button').click(statusChangeButtons);
        */
    }
}

function updateDatasetStatus($this, status, callback) {

    $('#rex-js-ajax-loader').addClass('rex-visible');
    if (confirm('Ändern?')) {
        url = window.location.origin;
        path = window.location.pathname;
        $.get(url + path + '?page=content&rex-api-call=choice_status', {
            data_id: $this.data('id'),
            table: $this.data('table'),
            field: $this.data('field'),
            token: $this.data('token'),
            value: status
        }, function (resp) {
            callback(resp);
            $('#rex-js-ajax-loader').removeClass('rex-visible');
        });

    } else {
        window.location.reload();
    }
}

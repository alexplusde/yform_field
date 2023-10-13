let mform_custom_url = '.rex-js-widget-customurl';

$(document).on('rex:ready', function (e, container) {
    if (container.find(mform_custom_url).length) {
        container.find(mform_custom_url).each(function () {
            customurl_init_widget($(this).find('.input-group.custom-url'));
        });
    }
});

function customurl_init_widget(element) {
    let id = 'cl' + randId(),
        clang = element.data('clang'),
        media_types = element.data('types'),
        media_Category = element.data('media_category'),
        extern_url_prefix = (element.data('extern-url-prefix') === undefined) ? 'https://' : element.data('extern-url-prefix'),
        url_category = element.data('category'),
        hidden_input = element.find('input[type=hidden]'),
        showed_input = element.find('input[type=text]'),
        value, text, args, timer;

    element.data('id', id)
    element.find('ul.dropdown-menu').attr('id', 'mform_yurl_' + id);

    // yurl
    element.find('.input-group-btn a.yurl').unbind().bind('click', function() {
        let id = element.data('id'),
            table = $(this).data('table'),
            column = $(this).data('column'),
            pool = newPoolWindow('index.php?page=yform/manager/data_edit&table_name=' + table + '&rex_yform_manager_opener[id]=1&rex_yform_manager_opener[field]=' + column + '&rex_yform_manager_opener[multiple]=0');

        clearInterval(timer);
        closeDropDown(id);

        $(pool).on('rex:YForm_selectData', function (event, id, label) {
            event.preventDefault();
            pool.close();

            value = hidden_input.val();
            text = showed_input.val();

            let urlUrl = table.split('_').join('-') + '://' + id;

            hidden_input.val(urlUrl);
            showed_input.val(label);
        });

        return false;
    });

    // media element
    element.find('a.media_url').unbind().bind('click', function () {
        let id = element.data('id'),
            value = hidden_input.val(),
            args = '';

        clearInterval(timer);
        closeDropDown(id);

        if (media_types !== undefined) {
            args = '&args[types]=' + media_types;
        }
        if (media_Category !== undefined) {
            args = args + '&rex_file_category=' + media_Category;
        }

        hidden_input.attr('id', 'REX_MEDIA_' + id);

        openREXMedia(id, args); // &args[preview]=1&args[types]=jpg%2Cpng

        timer = setInterval(function () {
            if (!$('#REX_MEDIA_' + id).length) {
                clearInterval(timer);
            } else {
                if (value != hidden_input.val()) {
                    clearInterval(timer);
                    showed_input.val(hidden_input.val());
                }
            }
        }, 10);

        return false;
    });

    // url element
    element.find('a.intern_url').unbind().bind('click', function () {
        let id = element.data('id'),
            url_id = randInt(),
            args = '&clang=' + clang;

        clearInterval(timer);
        closeDropDown(id);

        if (url_category !== undefined) {
            args = args + '&category_id=' + url_category;
        }

        showed_input.attr('id', 'REX_URL_' + url_id + '_NAME');
        hidden_input.attr('id', 'REX_URL_' + url_id);

        openUrlMap('REX_URL_' + url_id, args);

        return false;
    });

    // extern url
    element.find('a.external_url').unbind().bind('click', function () {
        let id = element.data('id'),
            value = hidden_input.val(),
            text = showed_input.val();

        clearInterval(timer);
        closeDropDown(id);

        if (value == '' || value.indexOf(extern_url_prefix) < 0) {
            value = extern_url_prefix;
        }

        let extern_url = prompt('Url', value);

        hidden_input.attr('id', 'REX_URL_' + id).addClass('form-control').attr('readonly', true);

        if (extern_url !== 'https://' && extern_url !== "" && extern_url !== undefined && extern_url != null) {
            hidden_input.val(extern_url);
            showed_input.val(extern_url);
        }
        if (extern_url == null) {
            hidden_input.val(value);
            showed_input.val(text);
        }
        return false;
    });

    // mail to url
    element.find('a.email_url').unbind().bind('click', function () {
        let id = element.data('id'),
            value = hidden_input.val(),
            text = showed_input.val();

        clearInterval(timer);
        closeDropDown(id);

        if (value == '' || value.indexOf("mailto:") < 0) {
            value = 'mailto:';
        }

        hidden_input.attr('id', 'REX_URL_' + id).addClass('form-control').attr('readonly', true);

        let mailto_url = prompt('Mail', value);

        if (mailto_url !== 'mailto:' && mailto_url !== "" && mailto_url !== undefined && mailto_url != null) {
            showed_input.val(mailto_url);
            hidden_input.val(mailto_url);
        }
        if (mailto_url == null) {
            hidden_input.val(value);
            showed_input.val(text);
        }
        return false;
    });

    // phone url
    element.find('a.phone_url').unbind().bind('click', function () {
        let id = element.data('id'),
            value = hidden_input.val(),
            text = showed_input.val();

        clearInterval(timer);
        closeDropDown(id);

        if (value == '' || value.indexOf("tel:") < 0) {
            value = 'tel:';
        }

        hidden_input.attr('id', 'REX_URL_' + id).addClass('form-control').attr('readonly', true);

        let tel_url = prompt('Telephone', value);

        if (tel_url !== 'tel:' && tel_url !== "" && tel_url !== undefined && tel_url != null) {
            showed_input.val(tel_url);
            hidden_input.val(tel_url);
        }
        if (tel_url == null) {
            hidden_input.val(value);
            showed_input.val(text);
        }
        return false;
    });

    // delete url
    element.find('a.delete_url').unbind().bind('click', function () {
        let id = element.data('id');
        clearInterval(timer);
        closeDropDown(id);
        showed_input.val('');
        hidden_input.val('');
        return false;
    });
}

function randId() {
    return Math.random().toString(16).slice(2);
}

function randInt() {
    return parseInt((Math.random() * 1000000000000) + (Math.random()*1000000000000/Math.random()));
}

function closeDropDown(id) {
    let dropdown = $('ul#mform_yurl_' + id);
    if (dropdown.is(':visible')) {
        dropdown.dropdown('toggle');
    }
}

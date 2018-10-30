/*
 * Created by Marijan on 25.04.2016..
 */

$(function() {
    /*Blank space fix for textareas*/
    $('textarea').each(function() {
        $(this).html($(this).html().trim());
    });
    /*End Blank space fix for textareas*/

    $('#side-menu').metisMenu({
        singleTapToGo: true,
        // doubleTapToGo: false,
        toggle: false,
        // preventDefault: false,
    });

    $(".select").chosen({});


    var datePickerUrl = window.location;
    if (((datePickerUrl.href.indexOf('dokumente') != -1) || (datePickerUrl.href.indexOf('notiz') != -1)) && datePickerUrl.href.indexOf('create') != -1) {
        dateToday = new Date();
        $(".datetimepicker").datetimepicker({
            locale: 'de',
            defaultDate: dateToday,
            format: 'DD.MM.YYYY',
            showTodayButton: true,
            showClear: true,
            widgetPositioning: {
                horizontal: 'auto',
                vertical: 'bottom'
            }
        });
    }
    else {
        //normal datepicker
        $(".datetimepicker").datetimepicker({
            locale: 'de',
            format: 'DD.MM.YYYY',
            showTodayButton: true,
            showClear: true,
            widgetPositioning: {
                horizontal: 'auto',
                vertical: 'bottom'
            }
        });
    }

    $(".datetimepicker.null").datetimepicker({
        locale: 'de',
        format: 'DD.MM.YYYY',
        showTodayButton: true,
        showClear: true,
        widgetPositioning: {
            horizontal: 'auto',
            vertical: 'bottom'
        },
        setNull: true
    });

    var now = '';
    if (datePickerUrl.href.indexOf('create') != -1) {
        now = moment();
    }
    $(".timepicker").datetimepicker({
        locale: 'de',
        format: 'HH:mm',
        useCurrent: true,
        defaultDate: now,
        showClose: true,
        widgetPositioning: {
            horizontal: 'auto',
            vertical: 'auto'
        }
    });

    if ($('.tree-view').length) {
        var counter = 0; //let insted of var
        var $treeview = []; //let insted of var
        $('.tree-view').each(function() {
            $treeview[counter] = $(this).treeview({
                expandIcon: 'custom-expand-icon',
                collapseIcon: 'custom-collapse-icon',
                data: $('.' + $(this).data('selector')).html(),
                color: "#428bca",
                showTags: false,
                enableLinks: true,
                enableDelete: true,
                enableHistory: true,
                levels: 0,
            });
        });

    }

    if ($('ul.pagination').length) {
        $('ul.pagination').each(function() {
            $(this).find('li').first().addClass('pull-left').find('a').html('&lt; zurück');
            $(this).find('li').first().find('span').html('&lt; zurück');

            $(this).find('li').last().addClass('pull-right').find('a').html('weiter &gt;');
            $(this).find('li').last().find('span').html('weiter &gt;');
        });
    }

    /*If modal has comment*/
    if ($('.modal-dialog').length) {

        if ($('.modal-dialog').find('[name*="comment"]').length) {
            $('.modal-dialog').find('[name*="comment"]').each(function() {
                $(this).closest('.modal.fade').addClass('no-background');
                $(this).closest('.modal-dialog').draggable({
                    handle: ".modal-content:not(form)"
                });
                // additional options data-backdrop="static" data-keyboard="false"

                // this is to remove the modal background- but ony on modals where the modal-dialog is draggable
                var triggerId = $(this).closest('.modal.fade').attr('id');
                $("[data-target='#" + triggerId + "']").attr("data-backdrop", "false");
            });
        }

        if ($('.modal-dialog').find('[name*="comment"]').length) {
            $('.modal-dialog').find('[name*="comment"]').each(function() {
                $(this).closest('.modal.fade').addClass('no-background');
                $(this).closest('.modal-dialog').draggable({
                    handle: ".modal-content:not(form)"
                });
                // additional options data-backdrop="static" data-keyboard="false"

                // this is to remove the modal background- but ony on modals where the modal-dialog is draggable
                var triggerId = $(this).closest('.modal.fade').attr('id');
                $("[data-target='#" + triggerId + "']").attr("data-backdrop", "false");
            });
        }

        $('.modal.draggable').each(function() {
            $(this).addClass('no-background');
            $(this).find('.modal-dialog').draggable({
                handle: ".modal-content:not(form)" //".modal-content:not(form)"
            });
            // additional options data-backdrop="static" data-keyboard="false"

            // this is to remove the modal background- but ony on modals where the modal-dialog is draggable
            var triggerId = $(this).attr('id');
            $("[data-target='#" + triggerId + "']").attr("data-backdrop", "false");
        });


    }

    if ($('.data-table').length) {
        $('.data-table').each(function() {
            if ($(this).closest('.panel-body').length) {
                $(this).closest('.panel').find('.panel-title a').addClass('trigger-datatable')
            }
            else {
                $(this).DataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    // processing: true,
                    // orderClasses: false,
                    deferRender: true,
                    language: {
                        "sEmptyTable": "Keine Daten vorhanden.",
                        "sInfo": "_START_ bis _END_ von _TOTAL_ EintrÃ¤gen",
                        "sInfoEmpty": "0 bis 0 von 0 EintrÃ¤gen",
                        "sInfoFiltered": "(gefiltert von _MAX_ EintrÃ¤gen)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ EintrÃ¤ge anzeigen",
                        "sLoadingRecords": "Wird geladen...",
                        "sProcessing": "Bitte warten...",
                        "sSearch": "Suchen",
                        "sZeroRecords": "Keine EintrÃ¤ge vorhanden.",
                        "oPaginate": {
                            "sFirst": "Erste",
                            "sPrevious": "ZurÃ¼ck",
                            "sNext": "NÃ¤chste",
                            "sLast": "Letzte"
                        },
                        "oAria": {
                            "sSortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
                            "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                        }
                    },
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false
                    }, {
                        targets: 'col-hide',
                        visible: false
                    }],
                    order: [
                        [$('th.defaultSort').index(), 'asc']
                    ],
                });
            }
        });


    }
    $('.trigger-datatable').on('click touch', function() {
        var initialTrigger = $(this);
        if (!$(this).hasClass('init')) {
            $(this).append('<span class="fa fa-spin fa-circle-o-notch remove-spinner"></span>');
        }
        if (!$(this).hasClass('init')) {
            $(this).addClass('init');

            setTimeout(function() {
                initialTrigger.closest('.panel').find('.panel-body').find('.data-table').DataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    // processing: true,
                    // orderClasses: false,
                    deferRender: true,
                    language: {
                        "sEmptyTable": "Keine Daten vorhanden.",
                        "sInfo": "_START_ bis _END_ von _TOTAL_ EintrÃ¤gen",
                        "sInfoEmpty": "0 bis 0 von 0 EintrÃ¤gen",
                        "sInfoFiltered": "(gefiltert von _MAX_ EintrÃ¤gen)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ EintrÃ¤ge anzeigen",
                        "sLoadingRecords": "Wird geladen...",
                        "sProcessing": "Bitte warten...",
                        "sSearch": "Suchen",
                        "sZeroRecords": "Keine EintrÃ¤ge vorhanden.",
                        "oPaginate": {
                            "sFirst": "Erste",
                            "sPrevious": "ZurÃ¼ck",
                            "sNext": "NÃ¤chste",
                            "sLast": "Letzte"
                        },
                        "oAria": {
                            "sSortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
                            "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                        }
                    },
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false
                    }, {
                        targets: 'col-hide',
                        visible: false
                    }],
                    order: [
                        [$('th.defaultSort').index(), 'asc']
                    ],
                });
            }, 600);
            setTimeout(function() {
                initialTrigger.closest('.panel').find('.remove-spinner').remove();
            }, 300);
        }
    });

    /*Universal  panel openner*/

    if ($('.editable').length) {
        var counter = 0;
        $('.editable').each(function() {
            counter++;
            if ($(this).data('id'))
                $(this).attr('id', $(this).data('id'));
            else
                $(this).attr('id', 'editor-' + counter);
            var classes = '';

            if ($(this).data('classes'))
                classes += $(this).data('classes');

            var docWidth = 680,
                docHeight = 450; //820

            if ($('.document-orientation').length) {
                docWidth = 'auto', docHeight = 680;
            }
            if ($(this).data('height')) {
                docWidth = 'auto', docHeight = $(this).data('height');
            }
            tinymce.init({
                selector: '.editable',
                skin_url: '/css/style',
                plugins: ["table link code"],
                toolbar1: "link  code | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                body_class: classes,
                //width: docWidth,
                height: docHeight,
                removed_menuitems: 'newdocument,',
                elementpath: false,
                menubar: "edit,format,table,insert",
                removed_menuitems: 'newdocument, bold, italic,underline, copy, paste,selectall, strikethrough,superscript ',
                setup: function(editor) {

                        editor.on('click', function(e) {

                            // e.target.closest('.parent-tabs').focus();
                            // editor.focus();
                            var source = e.target.src;
                            var image = $(document).find('img[src$="' + source + '"]')
                            removeCss(image, 'height');
                            /*if( e && e.element.nodeName.toLowerCase() == 'img' ){
                             console.log('trig');

                             }*/
                        });
                        editor.on('NodeChange', function(e) {
                            if (e && e.element.nodeName.toLowerCase() == 'tr') {
                                // processTableColumn(e);

                            }


                            if (e && e.element.nodeName.toLowerCase() == 'table') {
                                // processImage(e);

                            }

                            if (e && e.element.nodeName.toLowerCase() == 'td') {
                                processTableColumn(e);
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'p') {
                                if ($(e.element).parent('td')) {
                                    var table = $(e.element).closest('table');
                                    if (table.find('li').length) {
                                        table.find('td').each(function() {
                                            tableFontCorrection($(this));
                                        });
                                        table.find('p').each(function() {
                                            tableFontCorrection($(this));
                                        });
                                    }

                                }
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'li') {
                                if ($(e.element).parent('td')) {
                                    tableRow = $(e.element).closest('tr');
                                    tableRow.find('td').each(function() {
                                        removeCss($(this), 'font-size');
                                        removeCss($(this), 'font-size', 'data-mce-style');
                                        removeCss($(this), 'line-height');
                                        removeCss($(this), 'line-height', 'data-mce-style');
                                        setNewElementAttributes($(this), 'font-size', 'style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height', 'style', '20px ; ');

                                        setNewElementAttributes($(this), 'font-size', 'data-mce-style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height', 'data-mce-style', '22px ; ');
                                    });
                                }

                            }

                        });

                        /*Image setup */
                        var inp = $('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                        $(editor.getElement()).parent().append(inp);


                        inp.on("change", function() {
                            var input = inp.get(0);
                            var file = input.files[0];
                            var fr = new FileReader();
                            fr.onload = function() {
                                var img = new Image();
                                img.src = fr.result;
                                imgWidth = img.width;
                                imgHeight = img.height;
                                img.onload = function() {
                                    imgWidth = img.width;
                                    imgHeight = img.height;
                                    var ratio = imgWidth / imgHeight;
                                    editor.insertContent('<img  style="height:' + imgHeight + 'px;" data-ratio="' + ratio + '" src="' + img.src + '"/>');

                                    inp.val('');
                                }

                            }
                            fr.readAsDataURL(file);
                        });
                        editor.addMenuItem('mybutton', {
                            // type: 'button',
                            title: 'Bilder Upload',
                            text: 'Bilder Upload',
                            context: 'insert',
                            onclick: function(e) {
                                inp.trigger('click');
                            }
                        });
                        /* End Image setup */
                    } //end setup

            });
        });
    }

    if ($('.content-editor').length) {
        var counter = 0;
        $('.content-editor').each(function() {
            counter++;
            var docHeight = 350;
            if ($(this).data('id'))
                $(this).attr('id', $(this).data('id'));
            else
                $(this).attr('id', 'content-editor-' + counter);
            var classes = '';
            if ($(this).data('classes'))
                classes += $(this).data('classes');

            tinymce.init({
                selector: '.content-editor',
                skin_url: '/css/style',
                plugins: ["table link code"],
                toolbar1: "link code |undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                body_class: classes,
                height: docHeight,
                height: 350,
                elementpath: false,
                menubar: "edit,format,table,insert",
                removed_menuitems: 'newdocument, bold, italic,underline, copy, paste,selectall, strikethrough,superscript ',
                setup: function(editor) {

                        editor.on('click', function(e) {

                            // e.target.closest('.parent-tabs').focus();
                            // editor.focus();
                            var source = e.target.src;
                            var image = $(document).find('img[src$="' + source + '"]')
                            removeCss(image, 'height');
                            /*if( e && e.element.nodeName.toLowerCase() == 'img' ){
                             console.log('trig');

                             }*/
                        });
                        editor.on('NodeChange', function(e) {
                            if (e && e.element.nodeName.toLowerCase() == 'tr') {
                                // processTableColumn(e);

                            }


                            if (e && e.element.nodeName.toLowerCase() == 'img') {
                                // processImage(e);

                            }

                            if (e && e.element.nodeName.toLowerCase() == 'td') {
                                processTableColumn(e);
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'p') {
                                if ($(e.element).parent('td')) {
                                    var table = $(e.element).closest('table');
                                    if (table.find('li').length) {
                                        table.find('td').each(function() {
                                            tableFontCorrection($(this));
                                        });
                                        table.find('p').each(function() {
                                            tableFontCorrection($(this));
                                        });
                                    }

                                }
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'li') {
                                if ($(e.element).parent('td')) {
                                    tableRow = $(e.element).closest('tr');
                                    tableRow.find('td').each(function() {
                                        removeCss($(this), 'font-size');
                                        removeCss($(this), 'font-size', 'data-mce-style');
                                        removeCss($(this), 'line-height');
                                        removeCss($(this), 'line-height', 'data-mce-style');
                                        setNewElementAttributes($(this), 'font-size', 'style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height', 'style', '20px ; ');

                                        setNewElementAttributes($(this), 'font-size', 'data-mce-style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height', 'data-mce-style', '22px ; ');
                                    });
                                }

                            }

                        });

                        /*Image setup */
                        var inp = $('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                        $(editor.getElement()).parent().append(inp);


                        inp.on("change", function() {
                            var input = inp.get(0);
                            var file = input.files[0];
                            var fr = new FileReader();
                            fr.onload = function() {
                                var img = new Image();
                                img.src = fr.result;
                                imgWidth = img.width;
                                imgHeight = img.height;
                                img.onload = function() {
                                    imgWidth = img.width;
                                    imgHeight = img.height;
                                    var ratio = imgWidth / imgHeight;
                                    editor.insertContent('<img  style="height:' + imgHeight + 'px;" data-ratio="' + ratio + '" src="' + img.src + '"/>');

                                    inp.val('');
                                }

                            }
                            fr.readAsDataURL(file);
                        });
                        editor.addMenuItem('mybutton', {
                            // type: 'button',
                            title: 'Bilder Upload',
                            text: 'Bilder Upload',
                            context: 'insert',
                            onclick: function(e) {
                                inp.trigger('click');
                            }
                        });
                        /* End Image setup */
                    } //end setup

            });
        });
    }

    if ($('.variant').length) {
        $('.variant').closest('form').addClass('.tinymce-image');
        $('.variant').closest('form').attr('enctype', 'multipart/form-data');

        var counter = 0;
        $('.variant').each(function() {

            counter++;
            if ($(this).data('id'))
                $(this).attr('id', $(this).data('id'));
            else
                $(this).attr('id', 'variant-' + counter);
            var classes = ' ';
            if ($(this).data('classes'))
                classes += $(this).data('classes');

            var docWidth = 680,
                docHeight = 450; //820
            if ($('.document-orientation').length)
                docWidth = 'auto', docHeight = 680;

            tinymce.init({
                selector: '.variant',
                skin_url: '/css/style',
                plugins: ["table link code"],
                toolbar1: "link code | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                body_class: classes,
                //width: docWidth,
                height: docHeight,
                style_formats: [{
                        title: 'Spiegelstriche',
                        selector: 'ul',
                        classes: 'list-style-dash'
                    },
                    // {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}, classes: 'red-text'}
                ],
                style_formats_merge: true,
                elementpath: false,
                menubar: "edit,format,table,insert",
                removed_menuitems: 'newdocument, bold, italic,underline, copy, paste,selectall, strikethrough,superscript ',
                setup: function(editor) {

                        editor.on('click', function(e) {

                            // e.target.closest('.parent-tabs').focus();
                            // editor.focus();
                            var source = e.target.src;
                            var image = $(document).find('img[src$="' + source + '"]')
                            removeCss(image, 'height');
                            /*if( e && e.element.nodeName.toLowerCase() == 'img' ){
                             console.log('trig');

                             }*/
                        });
                        editor.on('NodeChange', function(e) {
                            if (e && e.element.nodeName.toLowerCase() == 'tr') {
                                // processTableColumn(e);

                            }


                            if (e && e.element.nodeName.toLowerCase() == 'table') {
                                // processImage(e); 

                            }

                            if (e && e.element.nodeName.toLowerCase() == 'td') {
                                processTableColumn(e);
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'p') {
                                if ($(e.element).parent('td')) {
                                    var table = $(e.element).closest('table');
                                    if (table.find('li').length) {
                                        table.find('td').each(function() {
                                            tableFontCorrection($(this));
                                        });
                                        table.find('p').each(function() {
                                            tableFontCorrection($(this));
                                        });
                                    }

                                }
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'li') {
                                if ($(e.element).parent('td')) {
                                    tableRow = $(e.element).closest('tr');
                                    tableRow.find('td').each(function() {
                                        removeCss($(this), 'font-size');
                                        removeCss($(this), 'font-size', 'data-mce-style');
                                        removeCss($(this), 'line-height');
                                        removeCss($(this), 'line-height', 'data-mce-style');
                                        setNewElementAttributes($(this), 'font-size', 'style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height', 'style', '20px ; ');

                                        setNewElementAttributes($(this), 'font-size', 'data-mce-style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height', 'data-mce-style', '22px ; ');
                                    });
                                }

                            }

                        });

                        /*Image setup */
                        var inp = $('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                        $(editor.getElement()).parent().append(inp);


                        inp.on("change", function() {
                            var input = inp.get(0);
                            var file = input.files[0];
                            var fr = new FileReader();
                            fr.onload = function() {
                                var img = new Image();
                                img.src = fr.result;
                                imgWidth = img.width;
                                imgHeight = img.height;
                                img.onload = function() {
                                    imgWidth = img.width;
                                    imgHeight = img.height;
                                    var ratio = imgWidth / imgHeight;
                                    editor.insertContent('<img  style="height:' + imgHeight + 'px;" data-ratio="' + ratio + '" src="' + img.src + '"/>');

                                    inp.val('');
                                }

                            }
                            fr.readAsDataURL(file);
                        });
                        editor.addMenuItem('mybutton', {
                            // type: 'button',
                            title: 'Bilder Upload',
                            text: 'Bilder Upload',
                            context: 'insert',
                            onclick: function(e) {
                                inp.trigger('click');
                            }
                        });
                        /* End Image setup */
                    } //end setup

            });
        });
    }

    function processImage(e) {
        var image = $(e.element),
            width = $(e.element).attr('width'),
            height = $(e.element).attr('height'),
            ratio = image.data('ratio');
        if (ratio >= 1)
            height = width / ratio;
        else
            height = width * ratio;
        removeCss(image, 'height');
        setNewTdAttributes(image, height, 'style', true)
            // console.log('height:' + height);
    }

    function tableFontCorrection(element) {

        removeCss(element, 'font-size');
        removeCss(element, 'font-size', 'data-mce-style');
        removeCss(element, 'line-height');
        removeCss(element, 'line-height', 'data-mce-style');
        setNewElementAttributes(element, 'font-size', 'style', '18px ; ');
        setNewElementAttributes(element, 'line-height', 'style', '20px ; ');

        setNewElementAttributes(element, 'font-size', 'data-mce-style', '18px ; ');
        setNewElementAttributes(element, 'line-height', 'data-mce-style', '22px ; ');
    }

    function processTableRowsAndCells(td) {
        var table = td.closest('table');
        table.find('tr').each(function() {
            if ($(this).find('ul').length || $(this).find('ol').length) {
                var tableRow = $(this);
                var maxHeight = 0

                removeCss($(this), 'height');
                removeCss($(this), 'height', 'data-mce-style');

                tableRow.find('td').each(function() {
                    removeCss($(this), 'height');
                    removeCss($(this), 'height', 'data-mce-style');
                    maxHeight = Math.max($(this).height(), maxHeight);
                }).height(maxHeight);
                maxHeight = maxHeight + 3;
                /* Iterte again trough the loop and set height */
                tableRow.find('td').each(function() {
                    setNewTdAttributes($(this), maxHeight, 'style', true)
                    setNewTdAttributes($(this), maxHeight, attribute = 'data-mce-style', true)
                });
                /* End Iterte again trough the loop and set height */

            }
        });
    }

    function processTableColumn(e) {
        var td = $(e.element),
            table = td.closest('table'),
            maxHeight = $(e.element).height();

        if (table.find('li').length) {
            console.log('has');
            table.find('td').each(function() {
                tableFontCorrection($(this));
            });
            table.find('p').each(function() {
                tableFontCorrection($(this));
            });
        }
        // processTableRowsAndCells(td);

        /*If td has images correct the whole row */
        // if(td.find('img').length){
        /* $( e.element ).find('img').each(function() {
         var height = $(this).innerHeight(), width = $(this).innerWidth();
         // $(this).attr('style', $(this).attr('style')+'min-height: '+height+'px !important; width: '+width+'px !important;');
         // $(this).attr('data-mce-style', $(this).attr('data-mce-style')+'min-height: '+height+'px !important; width: '+width+'px !important;');
         $(this).attr('style', $(this).attr('style')+'min-height: '+height+'px !important; width: '+width+'px !important;');
         $(this).attr('data-mce-style', $(this).attr('data-mce-style')+'min-height: '+height+'px !important; width: '+width+'px !important;');
         if(height != maxHeight && height > maxHeight)
         maxHeight = height;
         });*/
        //}               


        /* Determine the biggest height in tr*/

        td.closest('tr').find('td').each(function() {
            var calc = $(this).height();
            // console.log('maxHeight:'+maxHeight);
            // console.log('currentHeight:'+calc);
            if (maxHeight < $(this).height())
                maxHeight = $(this).height();
        });
        /* End Determine the biggest height in tr*/

        /* Go to each td, clear the style height, data-mce-style and after that set height and width */
        /*td.closest('tr').attr('style',findTinyMCE(td.closest('tr'),'height')+';').find('td').each(function(){
         removeCss( $(this),'height');
         removeCss( $(this),'min-height');
         // removeCss( $(this),'width');

         removeCss( $(this),'vertical-align');
         removeCss( $(this),'height','data-mce-style');
         removeCss( $(this),'min-height','data-mce-style');
         // removeCss( $(this),'width','data-mce-style');
         removeCss( $(this),'vertical-align','data-mce-style');

         removeCss( $(this),'height');
         removeCss( $(this),'height','data-mce-style');

         setNewTdAttributes($(this), maxHeight,'style',true)
         setNewTdAttributes($(this), maxHeight, attribute='data-mce-style',true)
         });*/
        /* End Go to each td, clear the style height, data-mce-style and after that set height and width */
        // findTinyMCE(td.closest('tr'),'height');
        //  totalTableHeight = td.closest('table').height();

        /*removeCss( $(this),'height');
         removeCss( $(this),'height','data-mce-style');*/
        var cnt = 0;
        td.closest('table').find('tr').each(function() {
            cnt = cnt + $(this).height();
        });
        // td.closest('table').css('height','auto')
        /*setNewTdAttributes(td.closest('table'), cnt, attribute='style',true);
         setNewTdAttributes(td.closest('table'), cnt, attribute='data-mce-style',true);*/
    }

    /* Remove style */
    function removeCss(element, toDelete, attribute) {
        if (typeof attribute == "undefined") attribute = 'style';

        if (typeof element == 'undefined' || typeof element.attr(attribute) == 'undefined')
            var props = '';
        else
            var props = element.attr(attribute).split(';');

        var tmp = -1;
        for (var p = 0; p < props.length; p++) {
            if (props[p].indexOf(toDelete) !== -1) {
                tmp = p
            }
        };
        if (tmp !== -1) {
            delete props[tmp];
        }
        for (var key in props) {
            if (props[key].trim() == '')
                delete props[key];
        }
        var finalAttr = '';
        for (var key in props) {
            finalAttr += props[key] + '; '
        }
        return element.attr(attribute, finalAttr);

    }

    /*End remove style*/

    /* Remove style */
    function findTinyMCE(element, toDelete, attribute) {
        if (typeof attribute == "undefined") attribute = 'data-mce-style';
        if (typeof element.attr(attribute) != 'undefined')
            var props = element.attr(attribute).split(';');
        else
            return 'height:' + element.height() - 1 + 'px';
        var tmp = -1;
        for (var p = 0; p < props.length; p++) {
            if (props[p].indexOf(toDelete) !== -1) {
                tmp = p
            }
        };
        if (tmp !== -1) {
            return props[tmp];
        }
        /* var finalAttr = '';
         for(var key in props){
         finalAttr += props[key]+'; '
         }
         return element.attr(attribute,finalAttr);*/

    }

    /*End remove style*/

    function setNewTdAttributes(element, height, attribute, isRow) {
        if (typeof attribute == "undefined") attribute = 'style';
        if (typeof isRow == "undefined") isRow = false;
        var existingAttribute = element.attr(attribute);
        if (existingAttribute.indexOf('height') == -1)
            existingAttribute = existingAttribute + ' height:' + height + 'px';
        if (isRow == false) {
            if (existingAttribute.indexOf('width') == -1)
                existingAttribute = existingAttribute + ' width:' + element.width() + 'px !important; ';
        }

        return element.attr(attribute, existingAttribute);

    }

    function setNewElementAttributes(element, attribute, styleType, value) {
        if (typeof styleType == "undefined") attribute = 'style';

        var existingAttribute = element.attr(styleType);

        existingAttribute = existingAttribute + '' + attribute + ': ' + value;
        return element.attr(styleType, existingAttribute);

    }


    /* Automatic trigger to open the panel heading */
    if ($('[data-open]').length) {
        $('[data-open]').each(function() {
            $(this).click();
        });
    }
    /* End Automatic trigger to open the panel heading */

    /* colorpicker init */
    $('.colorpicker').colorpicker({
        format: 'hex'
    });
    /* End colorpicker init */

    /* fullCalendar init */
    $('#calendar').fullCalendar({
        // put your options and callbacks here
    });
    /* End fullCalendar init */

});
/*End function() wrapper*/

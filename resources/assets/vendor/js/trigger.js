/**
 * Created by Marijan on 25.04.2016..
 */
$(function() {
    /*Bind laravel security token to ajax*/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $(document).find('[name="csrf-token"]').val()
        }
    });
    /* End Bind laravel security token to ajax*/

    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        }
        else {
            $('div.navbar-collapse').removeClass('collapse');
        }
        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    /*Exapand active class*/
    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).parents("ul").not('#side-menu').addClass('in');
    // }).addClass('active').parent().parent().addClass('in').parent();



    /*Fix the problem where the */
    if ((location.protocol + "//" + location.host + '/') != url.href) {
        $('a[href="/"]').removeClass('active');
    }

    $('a.active').each(function() {
        var url = window.location,
            currentLink = window.location.href
        if ($(this).attr('href') != currentLink)
            $(this).removeClass('active');
    });

    var parts = url.href.split('/'),
        lastLinkPart = parts[parts.length - 1],
        isNumeric = false;;
    // parts.pop();

    // console.log( lastLinkPart );    
    if (Math.floor(lastLinkPart) == lastLinkPart && $.isNumeric(lastLinkPart)) {
        isNumeric = true;
    }


    if ((location.protocol + "//" + location.host + '/') == url.href) {
        $('a[href="/"]').addClass('active');
    }
    else if (url.href.indexOf('notiz') != -1 && url.href.indexOf('edit') != -1) {
        console.log('trigger notice');
        $('a[href$="notiz"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('mandanten') != -1 && url.href.indexOf('search-single') != -1) {
        $('a[href$="/benutzer"]').addClass('active');
        $('a[href$="/mandantenverwaltung"]').next('ul').removeClass('in');
    }
    else if (url.href.indexOf('mandanten') != -1 && url.href.indexOf('edit') != -1) {
        $('a[href$="mandanten/create"]').addClass('active').closest('ul').addClass('in');
    }
    else if (url.href.indexOf('kontaktanfragen') != -1) {
        $('.nav a[href$="kontaktanfragen"]').addClass('active').closest('ul').addClass('in');
    }
    else if (url.href.indexOf('mandanten') != -1 && url.href.indexOf('search') != -1) {
        $('a[href$="mandanten"]').addClass('active').closest('ul').addClass('in');
    }

    else if (url.href.indexOf('benutzer') != -1 && url.href.indexOf('create-partner') != -1) {
        $('a[href*="benutzer/create-partner"]').addClass('active').closest('ul').addClass('in');
    }
    else if (url.href.indexOf('benutzer') != -1 && url.href.indexOf('partner') != -1) {
        $('a[href$="/benutzer"]').addClass('active');
        $('a[href$="/mandantenverwaltung"]').next('ul').removeClass('in');
    }
    else if (url.href.indexOf('edit') != -1 && url.href.indexOf('benutzer') != -1) {
        $('a[href*="benutzer/create"]').addClass('active').closest('ul').addClass('in');
        $('a[href$="/beratungsportal"]').next('ul').removeClass('in');
    }
    else if (url.href.indexOf('wiki-kategorie') != -1 && isNumeric == true) {
        $('a[href$="wiki"]').addClass('active').closest('ul').addClass('in');
    }
    else if (url.href.indexOf('wiki-kategorie') != -1 && url.href.indexOf('suche') != -1) {
        $('a[href$="wiki"]').addClass('active').closest('ul').addClass('in');
    }
    else if (url.href.indexOf('wiki-kategorie') != -1) {
        $('a[href$="wiki-kategorie"]').addClass('active').closest('ul').addClass('in');
    }
    else if (url.href.indexOf('verwalten-admin') != -1) {
        $('a[href$="wiki/verwalten-admin"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('edit') != -1 && url.href.indexOf('wiki') != -1) {
        $('a[href*="wiki/create"]').addClass('active').closest('ul').addClass('in');
    }
    else if (url.href.indexOf('create') != -1 && url.href.indexOf('wiki') != -1) {
        $('a[href*="wiki/create"]').addClass('active').closest('ul').addClass('in');
    }
    else if (url.href.indexOf('suche') != -1 && url.href.indexOf('wiki') != -1) {
        $('a[href$="wiki"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('wiki') != -1 && isNumeric == true) {
        $('a[href$="wiki"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('wiki') != -1) {
        $('a[href$="wiki"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('beratungsportal/calendar') != -1) {
        $('a[href$="beratungsportal/calendar"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('suche-abrechnen-abgerechnt') != -1 && url.href.indexOf('inventarliste') != -1) {
        $('a[href$="abrechnen"]').addClass('active').closest('ul').addClass('in').next('ul').addClass('in');
    }
    else if (url.href.indexOf('inventarliste') != -1 && url.href.indexOf('suche-abrechnen') != -1) {
        $('a[href$="abrechnen"]:not(.no-margin-bottom)').addClass('active').closest('ul').addClass('in').next('ul').addClass('in');
    }
    else if (url.href.indexOf('inventarliste') != -1 && url.href.indexOf('suche') != -1) {
        $('a[href$="inventarliste"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('inventarliste') != -1 && url.href.indexOf('edit') != -1) {
        $('a[href$="inventarliste"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('inventarliste') != -1 && url.href.indexOf('historie') != -1) {
        $('a[href$="inventarliste"]').addClass('active').next('ul').addClass('in');
    }

    else if (url.href.indexOf('inventarliste') != -1 && url.href.indexOf('abrechnen-abgerechnt') != -1) {
        $('a[href$="abrechnen"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('suche') != -1 && typeof slug != 'undefined') {
        if (slug == 'aktuelle-meldungen')
            slug = 'dokumente/news';
        $('a[href$="/' + slug + '"]').addClass('active').parents("ul").not('#side-menu').addClass('in');

        if ($('a[href$="/' + slug + '"]').addClass('active').next('ul').length)
            $('a[href$="/' + slug + '"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('suche') != -1 && url.href.indexOf('telefonliste') != -1) {
        $('a[href*="telefonliste"]').addClass('active').parent('li').find('ul').addClass('in');
    }
    else if (url.href.indexOf('suche') != -1) {
        $('a[href*="suche"]').addClass('active').parent('li').find('ul').addClass('in');
    }
    else if (url.href.indexOf('notiz') != -1 && url.href.indexOf('edit') == -1) {
        console.log('trigger notice');
        $('a[href$="notiz"]').addClass('active').next('ul').addClass('in');
    }
    else if (url.href.indexOf('beratungsportal') != -1 && url.href.indexOf('aktenart') != -1) {
        $('a[href$="/beratungsportal"]').removeClass('active').parent('ul').removeClass('in');
        $('a[href$="' + window.location.pathname.substr(1) + '"]').addClass('active').next('ul').addClass('in');

    }
    else if (url.href.indexOf('beratungsportal') != -1 && url.href.indexOf('meta-info') != -1) {
        $('a[href$="/beratungsportal"]').removeClass('active').parent('ul').removeClass('in');
        $('a[href$="' + window.location.pathname.substr(1) + '"]').addClass('active').next('ul').addClass('in');

    }
    else if (url.href.indexOf('beratungsportal') != -1 && url.href.indexOf('kategorien') == -1) {
        setTimeout(function() {
            $('a[href$="/beratungsportal"]').closest('li').find('.nav-second-level li.active').removeClass('active');
            $('a[href$="/beratungsportal"]').closest('li').find('ul').removeClass('in');
            $('a[href$="/beratungsportal"]').closest('li').find('.nav-second-level').addClass('in');

        }, 500);


        $('a[href$="/beratungsportal"]').closest('li').find('.nav-second-level li.active').removeClass('active');
        $('a[href$="/beratungsportal"]').addClass('active').next('ul').addClass('in');
        $('a[href$="/beratungsportal"]').closest('li').find('li.active').removeClass('active');
        $('a[href$="/juristenportal-kategorien/alle"]').closest('li').removeClass('active');

    }

    else if (url.href.indexOf('juristenportal-kategorien') != -1 && url.href.indexOf('alle') != -1) {

        $('a[href$="' + window.location.pathname.substr(1) + '"]').addClass('active').next('ul').addClass('in');
        $('a[href$="' + window.location.pathname.substr(1) + '"]').next('ul').find('li.active').each(function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            }
        });

        $('a[href$="' + window.location.pathname.substr(1) + '"]').next('ul.in li.active').removeClass('active');

    }
    else if (url.href.indexOf('beratung-kategorien') != -1 && url.href.indexOf('alle') != -1) {

        $('a[href$="' + window.location.pathname.substr(1) + '"]').addClass('active').next('ul').addClass('in');
        // $('a[href$="' + window.location.pathname.substr(1) + '"]').next('ul').find('li.active').each(function() {
        //     if ($(this).hasClass('active')) {
        //         $(this).removeClass('active');
        //     }
        // });

        $('a[href$="' + window.location.pathname.substr(1) + '"]').next('ul.in li.active').removeClass('active');

    }
    else if (url.href.indexOf('juristenportal-kategorien') != -1 && url.href.indexOf('alle') == -1) {
        $('a[href$="' + window.location.pathname.substr(1) + '"]').addClass('active').next('ul').addClass('in');
        $('a[href$="' + window.location.pathname.substr(1) + '"]').closest('li.active').find('ul.in li.active').removeClass('active');
        $('a[href$="' + window.location.pathname.substr(1) + '"]').next('ul.in li.active').removeClass('active');
    }

    else if (typeof documentType !== 'undefined' && documentType.length) {
        var detectHref = '';
        var locker = false;
        if (documentType == "Rundschreiben") {
            detectHref = '/dokumente/rundschreiben';
            locker = true;
        }
        if (documentType == "Formulare") {
            detectHref = '/dokumente/vorlagedokumente';
            locker = true;
        }
        else if (documentType == "QM-Rundschreiben") {
            detectHref = '/dokumente/rundschreiben-qmr';
            locker = true;
        }

        else if (documentType.toLowerCase() == "aktuelle Meldungen" && typeof(documentSlug) !== 'undefined') {
            detectHref = documentSlug;
            detectHref = 'news'
            locker = true;

        }
        else if (documentType.toLowerCase() == "aktuelle meldungen") {
            detectHref = 'news'
            locker = true;

        }

        else if (documentType == "ISO-Dokumente") {
            if (typeof isoCategoryName != 'undefined') {
                detectHref = $('#side-menu').find('a:contains("' + isoCategoryName + '")').attr('href');
                if ($('a[href$="' + detectHref + '"]').addClass('active').attr('class', 'active').parent("li").find('ul').length) {
                    $('a[href$="' + detectHref + '"]').addClass('active').attr('class', 'active').next('ul').addClass('in');

                }
            }
            else
                detectHref = '/iso-dokumente';
            locker = true;
        }

        else if (typeof documentSlug !== 'undefined' && documentSlug.length && locker == false) {

            detectHref = '/dokumente/typ/' + documentSlug;
        }

        $('a[href$="' + detectHref + '"]').addClass('active').parents("ul").not('#side-menu').addClass('in');
    }
    else if (url.href.indexOf('dokumente') != -1 && window.location.pathname == '/dokumente') {
        $('a.main-doc-url').addClass('active').next('ul').addClass('in');
    }
    else {
        $('a[href="' + url.href + '"]').addClass('active');
        if ($('a[href="' + url.href + '"]').next('ul').length)
            $('a[href="' + url.href + '"]').next('ul').addClass('in').hide().show("fast");
    }
    if (element.is('li')) {
        element.addClass('active');
    }

    var activeLink = $('#side-menu li a.active');
    activeLink.parents('li').addClass('active');

    var activeLinkSubnavs = activeLink.parents('li').first().children('ul').children('li').children('ul');
    activeLinkSubnavs.each(function() {
        $(this).parent('li').addClass('active');
    });
    activeLink.closest('li').next('ul').addClass('in');
    // activeLink.closest('li').children('ul').addClass('in');

    /*End Exapand active class*/

    /*fix when adding a active to button*/
    $('a.no-margin-bottom.active').removeClass('active');
    /* end fix when adding a active to button*/

    /* Remove required if checkbox is not checked*/
    $('.trigger-required').on('click touch change', function() {
        var checkbox = $(this).find('input:checkbox');
        var checkboxChecked = $(this).find(':checked');
        var removeRequiredInput = $('.remove-required').find('input');
        var removeRequiredLabel = $('.remove-required').find('label');
        if (checkbox.attr('id') == checkboxChecked.attr('id')) {
            removeRequiredInput.removeAttr('required');
            removeRequiredLabel.html(removeRequiredLabel.html().replace('*', ''));
        }
        else {
            removeRequiredInput.attr('required', true);
            if (removeRequiredLabel.html().indexOf('*') == -1) {
                removeRequiredLabel.html(removeRequiredLabel.html() + '*');
            }

        }
    });
    /* End Remove required if checkbox is not checked*/


    /* If clicked on arrow expand DD*/
    $('a[href="#"]:contains("fa arrow")').on('click touch', function() {
        $(this).parent('li').addClass('in').children('ul').addClass('in');
    });
    /*End If clicked on arrow expand DD*/


    /* Page content sidebar treeview */

    var elementNew = $('.content-nav ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).parents("ul").not('.parent-ul').addClass('in');
    /* End Page content sidebar treeview */

    /* Simulate tree view */
    if ($('.tree').length) {
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
        $('.tree li.parent_li > span').on('click', function(e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
            }
            else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
            }
            e.stopPropagation();
        });
    }
    /* End Simulate tree view */

    /* Simulate tree view2 */
    $('label.tree-toggler').click(function() {
        $(this).parent().children('ul.tree').toggle(300);
    });
    /* End Simulate tree view2 */

    /* Prevent a.href=# from exec. Becouse of the nav.active script */
    $('a').on('click touch', function(e) {
        if ($(this).attr('href') == "#")
            e.preventDefault();
    });
    /* End Prevent a.href=# from exec. Becouse of the nav.active script */

    /* Image preview before upload */
    if ($('#image-upload').length) {
        document.getElementById("image-upload").onchange = function() {
            var reader = new FileReader();

            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("image-preview").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };
    }
    /* End Image preview before upload */

    /*Copy new line*/
    var addRow = function(e) {
        var form = $(this);
        var method = form.find('input[name="_method"]').val() || 'POST';
        $.ajax({
            type: method,
            url: form.attr('action'),
            data: form.serialize(),
            success: function(data) {
                // console.log(data);
                form.closest('.bind-before').before(data);
                $('.select.mandant-roles').chosen();
            },
            error: function(data) {
                // console.log(data);
            },
        });

        e.preventDefault();
    };

    /*End copy new line*/

    /*Universal  panel openner*/
    if ($('.panel-group').length) {
        if (window.location.hash) {
            $(".container-fluid a[href='" + window.location.hash + "']").click();
        }
    }

    /*if link has hash navigation fix*/
    if (window.location.hash) {
        $('a[href$="' + window.location.pathname + '"]').addClass('active').parent('li').find('ul').addClass('in');

    }

    /*
     *Prevent accordion collapse trigger from adding hashtags at the address bar. 
     * This will prevent metisMenu (sidebar) from expanding
     */
    $('[data-toggle="collapse"]').on('click touch', function(e) {
        e.preventDefault();
    });

    $('.list-group').on('click touch', function() {
        $(this).find('li.node-selected').find('.glyphicon').trigger('click');

    });

    // Show elements if checkbox is checked
    $('.trigger-inputs').on('change', function(e) {
        if ($(this).is(':checked')) {
            $('[data-hide=' + $(this).data('second') + ']').removeClass('hide').attr('required');
        }
        else {
            $('[data-hide=' + $(this).data('second') + ']').addClass('hide').removeAttr('required');
        }

    });

    $('.add-single-field').on('click touch', function(e) {
        e.preventDefault();
        var html = '<div class="row input-row"><div class="col-md-10"><input name="meta-names[]" required class="form-control" placeholder="Meta info name" /></div>' +
            '<div class="col-xs-2"><a href="#" class="btn btn-xs btn-danger remove-row"> entfernen</a></ div ><br/>  </ div > <br/>';
        var parent = $(this).closest('.parent-div');
        parent.find('.add-wrapper').append(html);
        if (parent.find('.submit-div').hasClass('hidden')) {
            parent.find('.submit-div').removeClass('hidden');
        }
    });

    $('body').on('click', '.remove-row', function(e) {
        e.preventDefault();
        console.log('triggered');
        $(this).closest('.input-row').remove();
    });

    // Hide elements if checkbox is checked
    $('.hide-input').on('change', function(e) {
        if ($(this).is(':checked')) {
            $('[data-hide=' + $(this).data('hide-target') + ']').addClass('hide').removeAttr('required');
            $('[data-disable=' + $(this).data('disable-target') + ']').attr('disabled', true);
        }
        else {
            $('[data-hide=' + $(this).data('hide-target') + ']').removeClass('hide').attr('required');
            $('[data-disable=' + $(this).data('disable-target') + ']').attr('disabled', false);
        }

    });


    /* Trigger tab switch*/
    /*$('.nav-tabs li a').click(function (e) {
     e.preventDefault()
     $(this).tab('show')
     })*/
    /* End Trigger tab switch*/

    /* Trigger tab destroy*/
    $(document).on('click touch', '[data-delete-variant]', function() {
        var tabsNumber = $('#tabs li').size() - 1;
        if (tabsNumber >= 1) {
            var variantId = $(this).data('delete-variant');
            tinymce.execCommand('mceRemoveControl', true, 'variant-' + variantId);
            $('#variant' + variantId).remove();
            $('#variation' + variantId).remove();
            $(this).closest('li').remove();
            $('.nav-tabs li.active').removeClass('active');
            $('.tab-content .tab-pane').removeClass('active');
            $('.nav-tabs li').first().addClass('active');
            $('.tab-content .tab-pane').first().addClass('active');
        }

    });
    /* End Trigger tab destroy*/

    /* Trigger tab creation*/
    $('.add-tab').on('click touch', function() {
        var parent = $(this),
            prevNumber = $(this).closest('.parent-tabs').find('.nav-tabs li').size(),
            nextTab = $(this).closest('.parent-tabs').find('.nav-tabs li').size() + 1,
            prevHTML = '';
        if ($(this).closest('.parent-tabs').find('.nav-tabs li').last().length) {
            var last = $(this).closest('.parent-tabs').find('.nav-tabs li').last().data('variant');

            if (isNaN(parseInt(last)) == true)
                nextTab = $(this).closest('.parent-tabs').find('.nav-tabs li').size() + 1;
            else {
                prevNumber = parseInt(last);
                nextTab = parseInt(last) + 1;
            }
        }
        //Check if content exists to prevent undefined error
        if ($('#variant-' + prevNumber).length) {
            prevHTML = tinymce.get('variant-' + prevNumber).getContent();
        }
        if ($('#editor-' + prevNumber).length) {
            prevHTML = tinymce.get('editor-' + prevNumber).getContent();
        }
        // create the tab
        $('<li data-variant="' + nextTab + '"><a href="#variation' + nextTab + '" data-toggle="tab">Variante ' + nextTab + ' <span class="fa fa-close remove-editor" data-delete-variant="' + nextTab + '"></span></a></li>')
            .appendTo('#tabs');

        // create the tab content
        $('<div class="tab-pane" id="variation' + nextTab + '" data-id="' + nextTab + '"><div data-id="' + nextTab + '" id="variant-' + nextTab + '" class="editable variant-' + nextTab + '" >' + prevHTML + '</div></div>').appendTo('.tab-content');
        var counter = 0;

        /*

         */
        $('.editable').each(function() {
            counter++;

            if ($(this).data('id'))
                $(this).attr('id', 'variant-' + $(this).data('id'));
            else
                $(this).attr('id', 'variant-' + counter);
            tinymce.init({
                selector: '.editable',
                skin_url: '/css/style',
                plugins: ["table link code"],
                toolbar1: "link  code | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                //width: 680,
                // height: 820,
                height: 450,
                style_formats: [{
                    title: 'Spiegelstriche',
                    selector: 'ul',
                    classes: 'list-style-dash'
                }, ],
                style_formats_merge: true,
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
                                processTableColumn(e);

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

        if ($('.nav-tabs li.active').length < 1) {
            $('.nav-tabs li').first().addClass('active');
            $('.tab-content .tab-pane').first().addClass('active');
        }
        else {
            $('.nav-tabs li.active').removeClass('active');
            $('.tab-content .tab-pane').removeClass('active');
            $(document).find('a[href="#variation' + nextTab + '"]').closest('li').addClass('active');
            $('#variation' + nextTab).addClass('active');
            $(document).find('a[href="#variation' + nextTab + '"]').click(); //for preview hidden input change

        }
    });
    /* End trigger tab creation*/

    /* Simulate submit button*/
    $('.simulate-submit').on('click touch', function(e) {
        e.preventDefault();
        $(this).closest('form').submit();
    });
    /* End Simulate submit button*/


    $('#return-to-top').click(function() { // When arrow is clicked
        $('body,html,#wrapper').animate({
            scrollTop: 0 // Scroll to top of body
        }, 500);
    });

    /* Trigger scroll to top display on scroll */
    $('#wrapper').on('scroll', function() {
        if ($(this).scrollTop() >= 50) { // If page is scrolled more than 50px
            $('#return-to-top').fadeIn(200); // Fade in the arrow
        }
        else {
            $('#return-to-top').fadeOut(200); // Else fade out the arrow
        }
    });
    /* End Trigger scroll to top display on scroll */

    /* Go to top */
    $('#return-to-top').click(function() { // When arrow is clicked
        $('body,html,#wrapper').animate({
            scrollTop: 0 // Scroll to top of body
        }, 500);
    });
    /* End Go to top */

    /* On click check if form is empty and submit or go to URL*/
    $('[data-link]').on('click touch', function(e) {
        window.location = $(this).data('link');
    });
    /* End On click check if form is empty and submit or go to URL*/

    /* Attachment option 2 - if file selected and  option dosent' have title, and isset 3 hidden fields*/
    $('input[type=file]').on('change', function() {
        var fileName = $('input[type=file]')[0].files[0].name,
            title = $(this).closest('form').find("input[name='name']").val(),
            fileNameWithoutExtension = fileName.replace(/\.[^/.]+$/, "");

        if ($("input[name='user_id']").length && $("input[name='document_id']") && $("input[name='variant_id']") && title == '') {
            $(this).closest('form').find("input[name='name']").val(fileNameWithoutExtension);
        }
    });
    /* EndAttachment option 2 - if file selected and  option dosent' have title, and isset 3 hidden fields*/


    /*Rechte and Freigabe form on click check is it a slow freigabe and add the required field */
    // $('.freigabe-process').submit(function(e){
    //     e.preventDefault();
    //     console.log('prevented');
    //     var allPost = $(this).serialize();
    //     console.log(allPost); 
    // })
    $('.freigabe-process .no-validate').on('click touch', function(e) {
        e.preventDefault();
        var input = $("<input>").attr("type", "hidden").attr("name", $(this).attr('name')).val($(this).val());
        $('.freigabe-process').append($(input));
        $('.freigabe-process').find('.approval-users').removeAttr('required');
        $('.freigabe-process').submit();
    });

    $('.freigabe-process .validate').on('click touch', function(e) {
        var input = $("<input>").attr("type", "hidden").attr("name", $(this).attr('name')).val($(this).val());
        $('.freigabe-process').append($(input));
        if ($('.approval-users')[0].checkValidity() == true) {
            $('.freigabe-process').submit();
        }
    });
    /*End Rechte and Freigabe form on click check is it a slow freigabe and add the required field */

    /* Change the hidden input value on sites with .preview */
    if ($('.preview').length) {
        $(document).on('click touch', '[data-variant] a', function() {
            if ($(this).closest('li').hasClass('active')) {
                $('input[name="current_variant"]').val($(this).closest('li').data('variant'));
            }

        });
    }
    /* End Change the hidden input value on sites with .preview */ //.freigabe-mandant


    /* Check if option has alle */

    $('.alle-switch').on('change', function(e) {
        var count = $(this).find('option:selected').length;
        if (count > 1) {
            $(this).find('option:contains("Alle")').prop('selected', false).trigger('chosen:updated');
        }
        // $(this).attr('selected', true).parent().trigger('chosen:updated');  

    });



    /* End check all options in select


     /*$('.chosen-results').on('click touch', function(){
     if( $('.freigabe-mandant').length > 1)
     $('.freigabe-mandant').each(function(){
     var result$(this).find('.chosen-results');
     });
     });*/
    /* Check if variants*/


    $('.roles-td').on('click touch', function() {
        $(this).find('select option:selected').each(function() {
            if ($(this).val() == 'Alle') {
                $(this).parent().find('option').each(function() {
                    if ($(this).val() != 'Alle')
                        $(this).removeAttr('selected').parent().trigger('chosen:updated');
                });
            }
        });

    });

    $("input[type=number]").on("keyup blur scroll change", function(e) {
        var attr = $(this).attr('max'),
            value = $(this).val();
        if (value.toString().length < 2)
            value = "0" + value;
        if (typeof attr !== typeof undefined && attr !== false) {
            if (value > attr) {
                console.log(value + 'is greater than ' + attr);

                $(this).val(attr);
            }
        }
    });

    /* Prevent letterss from input type number */
    $("input[type=number]").on("keyup blur scroll change", function(e) {
        var inputValue = $(this).val();
        //8 backspace,37 left arrow,39 right arrow,46 delete
        if (e.which < 46 || e.which > 59 && (e.which !== 8 && e.which !== 37 && e.which !== 39 && e.which !== 46 && e.keyCode !== 188)) {
            e.preventDefault();
        } // prevent if not number/dot 
        var valString = $(this).val().toString();

        if (e.keyCode == 188 && $(this).val().toString().indexOf('.') != -1) {
            e.preventDefault();
        } // prevent if already dot
        var countMinus = inputValue.match(/-/g);
        if (countMinus != null) {
            $(this).val(Math.abs($(this).val()))
        }
    });

    /*Float fix*/
    $("input[type=text].float").on("keyup blur", function(e) {
        var str = $(this).val();
        var regex = /^(?!0+\.00)(?=.{1,9}(\.|$))(?!0(?!\.))\d{1,3}(,\d{3})*(\.\d+)?$/;
        var count = str.match(/,/g);
        var cleanCount = !count ? false : count.length;
        var i = 0;
        if (cleanCount > 1) {
            str = str.replace(/[\,\%]/g, function(match) {
                return match === "," ? (i++ === 0 ? ',' : '') : '';
            });
        }
        if (regex.test(str) == false) {
            str = str.replace('.', '');
        }

        var countMinus = str.match(/-/g);
        if (countMinus != null) {
            str = str.replace('-', '');
        }
        $(this).val(str);
    });

    $(document).on('change', '.jurist-switch ', function() {
        var beratungSwitchVal = $(this).find(':selected').val(),
            beratungSwitchSelector = $('.jurist-switch option[value="' + beratungSwitchVal + '"]'),
            beratungSwitch = beratungSwitchSelector.data('beratung-category');
        console.log('switch:' + beratungSwitch);
        if (typeof beratungSwitch !== typeof undefined) {
            $('.jurist-switch-triggered > option').each(function() {
                console.log($(this).data('jurist-type') != beratungSwitch);
                if ($(this).data('jurist-type') != beratungSwitch) {
                    $(this).hide();
                }
                else {
                    $(this).show();
                }
            });
            $('.jurist-switch-triggered').trigger('chosen:updated');
        }

    });

    /* end Prevent letterss from input type number */

    /* Multiple chosen with required fix */
    //   $('.freigabe-process').on('submit',function(e){
    //       e.preventDefault();
    //       var check = false;
    //       $('.select.freigabe-mandant').each(function() {
    //           $(this).focus().trigger("click");
    //       });
    //   });


    /* End multiple chosen with required fix */

});

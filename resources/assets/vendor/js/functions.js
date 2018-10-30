/**
 * Created by Marijan on 27.04.2016..
 */
$(function() {
    // Delete prompt for buttons and anchors
    $('.delete-prompt').on('click touch', function(e) {
        // e.preventDefault();
        var text = "Wollen Sie diesen Eintrag wirklich entfernen?";
        if ($(this).data('text') != undefined) {
            if ($(this).data('text').length)
                text = $(this).data('text');
        }
        if (confirm(text))
            return true;
        else
            return false;
    });

    // Select all trash items (mark checkboxes)
    $('.trashed-documents a.select-all-checkboxes').on('click touch', function(e) {
        $('.item-trash input:checkbox').prop('checked', true);
    });

    // Unselect all trash items (mark checkboxes)
    $('.trashed-documents a.unselect-all-checkboxes').on('click touch', function(e) {
        $('.item-trash input:checkbox').prop('checked', false);
    });


    // $('[data-hideswitch]').on('click', function(e) {
    //     $(this).toggle();
    //     console.log('trig');
    //     console.log($(this).attr('checked'));
    //     var $this = $(this),
    //         yesClass = $('.' + $this.data('yes')),
    //         noClass = $('.' + $this.data('no'));
    //     if ($this.attr('checked', true) || $this.attr('checked', 'checked')) {
    //         yesClass.removeClass('hidden');
    //         noClass.addClass('hidden');
    //     }
    //     else {
    //         noClass.removeClass('hidden');
    //         yesClass.addClass('hidden');
    //     }
    // });

    // Toggle legende btn in sidebar navigation
    var position = 'expanded';
    $('span#btn-legend').on('click', function(e) {
        $('.legend-wrapper').slideToggle();
        var menuHeight = $('#side-menu').height();
        if (menuHeight < 321) {
            $('.legend').toggleClass("legend-shadow legend-absolute");
        }
        else {
            $('.legend').toggleClass("legend-shadow");
        }


        if (position == 'expanded' && menuHeight < 480) {
            $('#side-menu').animate({
                "min-height": "480px"
            });
            $('.legend').addClass("legend-absolute");
            position = 'collapsed';
        }
        else {
            $('#side-menu').animate({
                "min-height": "320px"
            });
            $('.legend').removeClass("legend-absolute");
            position = 'expanded';
        }

    });

    // Toggle sidebar navigation
    $('button.navbar-toggle.big').on('click', function(e) {

        var navSidebar = $('.sidebar-nav.navbar-collapse');
        var pageWrapper = $('#page-wrapper');
        var navbarToggle = $('#nav-btn');
        var fixedTitle = $('.fixed-position');
        var legendBox = $('.legend-wrapper');
        if (navSidebar.hasClass('hidden')) {

            $.when(navSidebar.removeClass('hidden')).done(

                legendBox.removeClass('hidden'),
                pageWrapper.removeAttr('style'),
                fixedTitle.css('left', '315px'),
                navbarToggle.removeClass('pull-left')
            );

        }
        else {

            $.when(navSidebar.addClass('hidden')).done(
                function() {
                    if (legendBox.is(':visible')) legendBox.addClass('hidden')
                },
                // function(){ if(legendBox.is(':visible')) $('span#btn-legend').trigger('click') },
                pageWrapper.css('margin-left', '65px'),
                fixedTitle.css('left', '131px'),
                navbarToggle.addClass('pull-left')
            );
        }

    });

    // Hide or show mandant selection if checkbox is checked

    var mandantHauptstelleCheckbox = $('input[type="checkbox"]#hauptstelle');
    var mandantHauptstelleSelect = $('.select-mandants');

    if (mandantHauptstelleCheckbox.prop('checked') == true) {
        $('select[name="mandant_id_hauptstelle"]').prop('required', false);
        mandantHauptstelleSelect.hide(400);
    }
    else {
        $('select[name="mandant_id_hauptstelle"]').prop('required', true)
        mandantHauptstelleSelect.show(400);
    }


    mandantHauptstelleCheckbox.change(function(e) {
        // console.log(mandantHauptstelleSelect.prop('required'));
        if (mandantHauptstelleCheckbox.prop('checked') == true) {
            $('select[name="mandant_id_hauptstelle"]').prop('required', false);
            $('select[name="mandant_id_hauptstelle"]').val('').trigger('chosen:updated');
            mandantHauptstelleSelect.hide(400);
        }
        else {
            $('select[name="mandant_id_hauptstelle"]').prop('required', true);
            mandantHauptstelleSelect.show(400);
        }
    });

    // Hide or show advanced search options if checkbox is checked

    var advSearchCheckbox = $('input[type="checkbox"]#adv-search');
    var advSearchContainer = $('.advanced-search');

    if (advSearchCheckbox.prop('checked') == false) {
        // $('input.adv-parameter').prop('disabled', false);
        advSearchContainer.hide(400);
        $(".advanced-search input:text").val("");
        $('.advanced-search input:checkbox').removeAttr('checked');
        $('.advanced-search select').prop('selectedIndex', 0).trigger('chosen:updated');
    }
    else {
        // $('input.adv-parameter').prop('disabled', true)
        advSearchContainer.show(400);
    }


    advSearchCheckbox.change(function(e) {
        // console.log(mandantHauptstelleSelect.prop('required'));
        if (advSearchCheckbox.prop('checked') == false) {
            // $('input.adv-parameter').prop('disabled', false);
            advSearchContainer.hide(400);
            $(".advanced-search input:text").val("");
            $('.advanced-search input:checkbox').removeAttr('checked');
            $('.advanced-search select').prop('selectedIndex', 0).trigger('chosen:updated');

        }
        else {
            // $('input.adv-parameter').prop('disabled', true);
            advSearchContainer.show(400);
        }
    });

    // Mandant history add button

    $('.history-add').on('click touch', function(e) {

        var gfHistory = $('textarea[name="geschaftsfuhrer_history"]');
        // var gfSelect = $('select[name="geschaftsfuhrer"] option:selected').html().trim();
        var gfSelect = $('input[name="geschaftsfuhrer"]').val().trim();
        var gfInfo = $('input[name="geschaftsfuhrer_infos"]').val().trim();
        var gfVon = $('input[name="geschaftsfuhrer_von"]').val().trim();
        var gfBis = $('input[name="geschaftsfuhrer_bis"]').val().trim();

        // console.log("\n" + gfSelect + " [" + gfVon + " - " + gfBis + "]: " + gfInfo + ";");
        // gfHistory.val(gfHistory.val() + "\n" + gfSelect + " [" + gfVon + " - " + gfBis + "]: " + gfInfo + ";");
        gfHistory.val(gfSelect + " [" + gfVon + " - " + gfBis + "]: " + gfInfo + ";" + "\n" + gfHistory.val());

    });

    // Mandant Gewerbeanmeldung history add button

    $('.history-gewerbeanmeldung-add').on('click touch', function(e) {

        var gwHistory = $('textarea[name="gewerbeanmeldung_history"]');
        var gwInfoAngemeldet = $('input[name="angemeldet_am"]').val().trim();
        var gwInfoUmgemeldet = $('input[name="umgemeldet_am"]').val().trim();
        var gwInfoAbgemeldet = $('input[name="abgemeldet_am"]').val().trim();

        // console.log("\n" + "Angemeldet am: " + gwInfoAngemeldet + "; " +  "Umgemeldet am: " + gwInfoUmgemeldet + "; " + "Abgemeldet am: " + gwInfoAbgemeldet + "; " );
        // gwHistory.val(gwHistory.val() + "\n" + "Angemeldet am: " + gwInfoAngemeldet + "; " +  "Umgemeldet am: " + gwInfoUmgemeldet + "; " + "Abgemeldet am: " + gwInfoAbgemeldet + "; " );
        gwHistory.val("Angemeldet am: " + gwInfoAngemeldet + "; " + "Umgemeldet am: " + gwInfoUmgemeldet + "; " + "Abgemeldet am: " + gwInfoAbgemeldet + "; " + "\n" + gwHistory.val());

    });


    // Mandant Bankinfos add button

    $('.bankverbindung-add').on('click touch', function(e) {

        var bankInfo = $('textarea[name="bankverbindungen"]');
        var bankName = $('input[name="bank_name"]').val().trim();
        var bankIban = $('input[name="bank_iban"]').val().trim();
        var bankBic = $('input[name="bank_bic"]').val().trim();
        var bankMemo = $('input[name="bank_memo"]').val().trim();

        // console.log("[" + bankName + "; " + bankIban + "; " + bankBic + "; " + bankMemo + "] " + "\n" + bankInfo.val());
        bankInfo.val(bankInfo.val() + "\n" + "[" + bankName + "; " + bankIban + "; " + bankBic + "; " + bankMemo + "] ");

    });


    // Hide or show PDF upload checkbox 

    if ($(".document-type-select .select").val() == 1 || $(".document-type-select .select").val() == 2 ||
        $(".document-type-select .select").val() == 3 || $(".document-type-select .select").val() == 4) {
        $('.pdf-checkbox').show(400);
        $('.pdf-checkbox').find('input[name="pdf_upload"]').val(1);
    }
    else {
        $('.pdf-checkbox').hide(400);
        $('.pdf-checkbox').find('input[name="pdf_upload"]').removeAttr('value');

    }

    $('.document-type-select .select').chosen().change(function(event) {
        if (event.target == this) {
            //  console.log($(this).val());
            if ($(this).val() == 4 || $(this).val() == 3 || $(this).val() == 2 || $(this).val() == 1)
                $('.pdf-checkbox').show(400);
            else
                $('.pdf-checkbox').hide(400);
        }
    });

    // Hide or show Document landscape selection based on selected value

    if ($(".document-type-select .select").val() == 5)
        $('#landscape').closest('.checkbox').hide(400);
    else
        $('#landscape').closest('.checkbox').show(400);


    $('.document-type-select .select').chosen().change(function(event) {
        if (event.target == this) {
            // console.log($(this).val());
            if ($(this).val() == 5)
                $('#landscape').closest('.checkbox').hide(400);
            else
                $('#landscape').closest('.checkbox').show(400);
        }
    });

    // Hide or show ISO category selection based on selected value

    if ($(".document-type-select .select").val() == 4) {
        $('.iso-category-select').show(400);
        // console.log('trigger');
        $('#landscape').closest('.checkbox').addClass('no-margin-top');
    }
    else {
        $('.iso-category-select').hide(400);
        $('#landscape').closest('.checkbox').removeClass('no-margin-top');
    }

    $('.document-type-select .select').chosen().change(function(event) {
        if (event.target == this) {
            // console.log($(this).val());
            if ($(this).val() == 4) {
                $('.iso-category-select').show(400);
                $('#landscape').closest('.checkbox').addClass('no-margin-top');

            }
            else {
                $('.iso-category-select').hide(400);
                $('#landscape').closest('.checkbox').removeClass('no-margin-top');
            }
        }
    });

    // Hide or show QMR field selection based on selected value

    if ($(".document-type-select .select").val() == 3) {
        $('.qmr-select').show(400);
        $('#landscape').closest('.checkbox').removeClass('no-margin-top');
        $('#pdf_upload').closest('.checkbox').removeClass('no-margin-top');
    }
    else {
        $('.qmr-select').hide(400);
        $('#landscape').closest('.checkbox').addClass('no-margin-top');
        $('#pdf_upload').closest('.checkbox').addClass('no-margin-top');
    }

    $('.document-type-select .select').chosen().change(function(event) {
        if (event.target == this) {
            // console.log($(this).val());
            if ($(this).val() == 3) {
                $('.qmr-select').show(400);
                $('#landscape').closest('.checkbox').removeClass('no-margin-top');
                $('#pdf_upload').closest('.checkbox').removeClass('no-margin-top');
            }
            else {
                $('.qmr-select').hide(400);
                $('#landscape').closest('.checkbox').addClass('no-margin-top');
                $('#pdf_upload').closest('.checkbox').addClass('no-margin-top');
            }
        }
    });

    /* Hide or show additional letter field for QMR or ISO category */

    if ($(".document-type-select .select").val() == 3 || $(".document-type-select .select").val() == 4)
        $('.additional-letter').show(400);

    else
        $('.additional-letter').hide(400);

    $('.document-type-select .select').chosen().change(function(event) {
        if (event.target == this) {
            // console.log($(this).val());
            if ($(this).val() == 3 || $(this).val() == 4)
                $('.additional-letter').show(400);
            else
                $('.additional-letter').hide(400);
        }
    });

    /* End Hide or show additional letter field for QMR or ISO category */


    // Hide or show new favorite input field

    var categorySelect = $('select#favorite_category_id').chosen();
    var categoryNew = $('input#favorite_category_new');

    categorySelect.change(function(event) {
        if (event.target == this) {
            // console.log($(this).val());
            if ($(this).val() == 'new') {
                categoryNew.val('');
                categoryNew.show(400);
            }
            else categoryNew.hide(400);
        }
    });

    // Define sending settings variables (user profile page)

    var settingsSendMethod = $('.email-settings-form select[name="settings_sending_method"]');
    var settingsEmail = $('.email-settings-form .settings-email');
    var settingsFax = $('.email-settings-form .settings-fax-custom');
    var settingsMandant = $('.email-settings-form .settings-mandant');
    var settingsDocTypes = $('.settings-document-type select[name="settings_document_type"]');

    // Hide or show sending methods according to the document type selection

    function checkDocType() {
        if (settingsDocTypes.val() == 'all') {
            resetDocTypeOptions();
            settingsSendMethod.find('option').each(function() {
                if (($(this).val() == 3) || ($(this).val() == 4)) {
                    $(this).prop('disabled', true);
                }
            });
        }
        else if (settingsDocTypes.val() == '1') {
            resetDocTypeOptions();
            settingsSendMethod.find('option').each(function() {
                if (($(this).val() == 4)) {
                    $(this).prop('disabled', true);
                }
            });
        }
        else if (settingsDocTypes.val() == '4') {
            resetDocTypeOptions();
            settingsSendMethod.find('option').each(function() {
                if (($(this).val() == 3) || ($(this).val() == 4)) {
                    $(this).prop('disabled', true);
                }
            });
        }
        else resetDocTypeOptions();

        settingsSendMethod.val('').trigger('chosen:updated');
    }

    checkDocType();
    settingsDocTypes.chosen().change(function(event) {
        checkDocType();
        checkSendMethod();
    });

    function resetDocTypeOptions() {
        settingsSendMethod.find('option').each(function() {
            $(this).prop('disabled', false);
        });
    }

    // Hide or show profile email preferences fields

    function checkSendMethod() {
        if (settingsSendMethod.val() == 1 || settingsSendMethod.val() == 2) {
            settingsEmail.find('select').prop('required', true).val('').trigger('chosen:updated');
            settingsEmail.show(400);
            settingsFax.hide(400);
            settingsFax.find('input').prop('required', false).val('');
            settingsMandant.hide(400);
            settingsMandant.find('select').prop('required', false).val('').trigger('chosen:updated');
        }
        else if (settingsSendMethod.val() == 3) {
            settingsFax.find('input').prop('required', true).val('');
            settingsFax.show(400);
            settingsEmail.find('select').prop('required', false).val('').trigger('chosen:updated');
            settingsEmail.hide(400);
        }
        else if (settingsSendMethod.val() == 4) {
            settingsMandant.find('select').prop('required', true).val('').trigger('chosen:updated');
            settingsMandant.show(400);
            settingsEmail.hide(400);
            settingsEmail.find('select').prop('required', false).val('').trigger('chosen:updated');
            settingsFax.hide(400);
            settingsFax.find('input').prop('required', false).val('');
        }
        else {
            settingsEmail.hide(400);
            settingsEmail.find('select').prop('required', false).val('').trigger('chosen:updated');
            settingsFax.hide(400);
            settingsFax.find('input').prop('required', false).val('');
            settingsMandant.hide(400);
            settingsMandant.find('select').prop('required', false).val('').trigger('chosen:updated');
        }
    }

    checkSendMethod();
    settingsSendMethod.chosen().change(function(event) {
        checkSendMethod();
    });

});

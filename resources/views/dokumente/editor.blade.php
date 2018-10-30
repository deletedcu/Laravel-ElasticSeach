@section('page-title') {{ trans('controller.create') }} @stop
<h3 class="title">{{ trans('controller.editor') }}</h3>


<input type="hidden" name="model_id" value="{{$data->id}}"/>
@if($data->landscape == true)
    <input type="hidden" class="document-orientation" name="document-orientation" value="landscape"/>
    @endif
            <!--<div class="box-wrapper">-->
    <!--    <div class="box">-->

    <div class="row">
        <!-- input box-->
        <div class="col-lg-5">
            <div class="form-group">
                {!! ViewHelper::setSelect($adressats,'adressat_id',$data,old('adressat_id'),
                        trans('documentForm.adressat'), trans('documentForm.adressat') ) !!}
            </div>
        </div><!--End input box-->

        <!-- input box-->
       <!-- <div class="col-lg-5">
            <div class="form-group">
                {!! ViewHelper::setArea('betreff',$data,old('betreff'),trans('documentForm.subject'), trans('documentForm.subject'), false,
                array(), array(), false, true ) !!}
            </div>
        </div><!--End input box-->

        <div class="clearfix"></div>

        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group checkbox-form-group">
                {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName') ) !!}
            </div>
        </div><!--End input box-->

        <!--    </div>-->
        <!--</div>-->
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="parent-tabs col-xs-12 col-md-12">
            <hr/>
            <!-- Tab panes -->
            <a href="#" class="btn btn-primary add-tab"><span class="fa fa-plus"></span> Neue Variante</a>

            <div class="pull-right">
                <button href="#" class="btn btn-primary preview" name="preview" value="preview" type="submit">Seiten
                    Vorschau
                </button>
                <button href="#" class="btn btn-primary preview" name="pdf_preview" name="preview" value="pdf_preview"
                        type="submit">PDF Vorschau
                </button>
                <input type="hidden" name="current_variant" value="1"/>
            </div>

            <ul class="nav nav-tabs" id="tabs">
                @if( count($data->editorVariantOrderBy) )
                    @foreach( $data->editorVariantOrderBy as $variant)
                        <li data-variant="{{$variant->variant_number}}"><a href="#variation{{$variant->variant_number}}"
                                                                           data-toggle="tab">Variante {{$variant->variant_number}}
                                <span class="fa fa-close remove-editor"
                                      data-delete-variant="{{ $variant->variant_number }}"></span> </a></li>
                    @endforeach
                @endif
            </ul>

            <div class="tab-content">
                @if( count($data->editorVariant) )
                    @foreach( $data->editorVariant as $variant)
                        <div class="tab-pane " id="variation{{$variant->variant_number}}"
                             data-id="{{$variant->variant_number}}">
                            <div class="variant" id="variant-{{$variant->variant_number}}"  
                            data-classes="@if( $data->landscape == true) landscape @else portrait @endif @if( $data->document_type_id == 4) iso-document @else rundschreiben @endif">
                                {!!($variant->inhalt)!!}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>



    <div class="clearfix"></div>
    @if( count($data->editorVariant) )
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
                    @if( isset($previewUrl) && $previewUrl != '')
            var url = "{{$previewUrl}}", win = window.open(url, '_blank');
            win.focus();
            @endif
            if ($('.editable').length) {
                var counter = 0;
                $('.editable').each(function () {
                    counter++;
                    if ($(this).data('id'))
                        $(this).attr('id', 'variant-' + $(this).data('id'));
                    else
                        $(this).attr('id', 'variant-' + counter);
                        
                 
                    tinymce.init({
                        selector: '.editable',
                        skin_url: '/css/style',
                        // width: 680,
                        // height: 820,
                        height: 450,
                        style_formats: [
                            {title: 'Spiegelstriche', selector: 'ul', classes: 'list-style-dash'},
                        ],
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


                            if (e && e.element.nodeName.toLowerCase() == 'img') {
                                // processImage(e);

                            }

                            if (e && e.element.nodeName.toLowerCase() == 'td') {
                                processTableColumn(e);
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'p') {
                                if($(e.element).parent('td')){
                                    var table = $(e.element).closest('table');
                                    if( table.find('li').length ){
                                        table.find('td').each(function(){
                                           tableFontCorrection($(this)); 
                                        });
                                        table.find('p').each(function(){
                                           tableFontCorrection($(this)); 
                                        });
                                    }
                                    
                                }
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'li') {
                                if($(e.element).parent('td')){
                                    tableRow = $(e.element).closest('tr');
                                      tableRow.find('td').each(function(){
                                        removeCss($(this), 'font-size');
                                        removeCss($(this), 'font-size', 'data-mce-style');
                                        removeCss($(this), 'line-height');
                                        removeCss($(this), 'line-height', 'data-mce-style');
                                        setNewElementAttributes($(this), 'font-size' , 'style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height' , 'style', '20px ; ');
                                        
                                        setNewElementAttributes($(this), 'font-size' , 'data-mce-style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height' , 'data-mce-style', '22px ; ');
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
            if ($('.nav-tabs li.active').length < 1) {
                $('.nav-tabs li').first().addClass('active');
                $('.tab-content .tab-pane').first().addClass('active');
            }
        });//end document ready
    </script>
@stop
@else
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
                    @if( isset($previewUrl) && $previewUrl != '')
            var url = "{{$previewUrl}}", win = window.open(url, '_blank');
            win.focus();
            @endif
            if ($('.editable').length) {
                var counter = 0;
                $('.editable').each(function () {

                    counter++;
                    if ($(this).data('id'))
                        $(this).attr('id', 'variant-' + $(this).data('id'));
                    else
                        $(this).attr('id', 'variant-' + counter);

                    // var docWidth = 680, docHeight = 820;
                    var docWidth = 680, docHeight = 450;

                    if ($('.document-orientation').length) {
                        docWidth = 'auto', docHeight = 680;
                    }
                    if ($(this).data('height')) {
                        docWidth = 'auto', docHeight = $(this).data('height');
                    }
                    //not found where this is triggered
                    tinymce.init({
                        selector: '.editable',
                        skin_url: '/css/style',
                        width: docWidth,
                        height: docHeight, 
                        plugins: ["image table link"],
                        toolbar1: "link | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                        style_formats: [
                            {title: 'Spiegelstriche', selector: 'ul', classes: 'list-style-dash'},
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
                                processTableColumn(e);

                            }


                            if (e && e.element.nodeName.toLowerCase() == 'img') {
                                // processImage(e);

                            }

                            if (e && e.element.nodeName.toLowerCase() == 'td') {
                                processTableColumn(e);
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'p') {
                                if($(e.element).parent('td')){
                                    var table = $(e.element).closest('table');
                                    if( table.find('li').length ){
                                        table.find('td').each(function(){
                                           tableFontCorrection($(this)); 
                                        });
                                        table.find('p').each(function(){
                                           tableFontCorrection($(this)); 
                                        });
                                    }
                                    
                                }
                            }
                            if (e && e.element.nodeName.toLowerCase() == 'li') {
                                if($(e.element).parent('td')){
                                    tableRow = $(e.element).closest('tr');
                                      tableRow.find('td').each(function(){
                                        removeCss($(this), 'font-size');
                                        removeCss($(this), 'font-size', 'data-mce-style');
                                        removeCss($(this), 'line-height');
                                        removeCss($(this), 'line-height', 'data-mce-style');
                                        setNewElementAttributes($(this), 'font-size' , 'style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height' , 'style', '20px ; ');
                                        
                                        setNewElementAttributes($(this), 'font-size' , 'data-mce-style', '18px ; ');
                                        setNewElementAttributes($(this), 'line-height' , 'data-mce-style', '22px ; ');
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

            if ($('#variant-1').length == 0) {
                $('.add-tab').click();
            }

            if ($('.nav-tabs li.active').length < 1) {
                $('.nav-tabs li').first().addClass('active');
                $('.tab-content .tab-pane').first().addClass('active');
            }
        });//end document ready

    </script>
    @stop
    @endif
    @if( isset( $data->document_type_id ) )
    @section('preScript')
            <!-- variable for expanding document sidebar-->
    <script type="text/javascript">
        var documentType = "{{ $data->documentType->name}}";
        var documentSlug = "{{ str_slug($data->documentType->name)}}";
    </script>

    <!--patch for checking iso category document-->
    @if( isset($data->isoCategories->name) )
        <script type="text/javascript">
            if (documentType == 'ISO-Dokumente')
                var isoCategoryName = '{{ $data->isoCategories->name}}';
        </script>
        @endif
                <!-- End variable for expanding document sidebar-->
@stop
@endif
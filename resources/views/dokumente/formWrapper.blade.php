@extends('master')

@section('content') 
   
    <div class="clearfix"></div>
    

    <div class="col-xs-12 box-wrapper">
        @if( $data->name != null)   
            <div class="row">
               <div class="col-md-12"><h3 class="title doc-title">{{ $data->name }}</h3></div>
            </div>
        @endif
        <div class="box">
            {!! Form::open([
                   'url' => $url,
                   'method' => 'POST',
                   'enctype' => 'multipart/form-data',
                   'class' => 'horizontal-form']) !!}
                   
                    @if( view()->exists('dokumente.'.$form) )
                        @include('dokumente.'.$form)
                    @else
                        <div class="alert alert-warning">
                            <p> There is no form defined</p>      
                        </div>
                    @endif
                    @if( view()->exists('dokumente.'.$form) )
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-xs-12 form-buttons">
                                @if( isset($backButton) )
                                    <a href="{{$backButton}}" class="btn btn-info">
                                        <!--<span class="fa fa-chevron-left"></span> -->
                                        Zurück</a>
                                @endif
                                <button class="btn btn-primary" type="submit" name="save" value="save">
                                    <!--<span class="fa fa-floppy-o"></span> -->
                                    Speichern
                                </button>
                                
                                @if( $data->documentType->document_art != 1 )
                                    <button class="btn btn-primary" type="submit" name="attachment" value="attachment"> 
                                        <!--<span class="fa fa-file-text-o"></span>-->
                                        Anlage hinzufügen
                                    </button>
                                @endif
                                
                                {{-- NEPTUN-815, NEPTUN-817 --}}
                                {{-- @if(in_array($data->document_status_id, [2, 6]) == false) --}}
                                    <button class="btn btn-primary" type="submit" name="next" value="next"> 
                                        <!--<span class="fa fa-chevron-right"></span>-->
                                        Freigabe & Verteiler
                                    </button>
                                {{-- @endif --}}
                                
                                @yield('buttons')
                            </div>
                        </div>
                      
                    @endif
            </form>
        </div>
    </div>

    <div class="clearfix"></div>
      
    @stop
   
        @if( isset( $data->document_type_id ) )
           @section('preScript')
               <!-- variable for expanding document sidebar-->
               <script type="text/javascript">
                    var documentType = "{{ $data->documentType->name}}";
                    var documentSlug = "{{ str_slug($data->documentType->name)}}";
                    $(document).ready(function(){
                        console.log(documentType);
                    });
              
               </script>
               
               <!--patch for checking iso category document-->
                @if( isset($data->isoCategories->name) )
                    <script type="text/javascript">   
                        if( documentType == 'ISO Dokument')
                            var isoCategoryName = '{{ $data->isoCategories->name}}';
                    </script>
                @endif
               <!-- End variable for expanding document sidebar-->
           @stop
           
            @section('afterScript')
                <script type="text/javascript">
                $(document).ready(function(){
                    console.log('ty:'+ documentType);
                    
                });
                </script>          
            @stop
       @endif
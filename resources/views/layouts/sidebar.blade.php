<div class="navbar-default sidebar" role="navigation">
    <div class="">
        <button type="button" id="nav-btn" class="navbar-toggle big hidden-xs" title="Navi Ein-/Ausblenden"></button>
    </div>
    
    <div class="sidebar-nav navbar-collapse" id="nav-collapse">
        <ul class="nav" id="side-menu">
            <!--startseite-->
            <li>
                <a href="/" class="home-class">Startseite</a>
            </li>
            <!--end startseite-->
            <!--favorites-->
            <li>
                <a href="{{ url('favoriten') }}">{{ ucfirst( trans('navigation.favorites') ) }}
                    <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                         <li>
                            <a href="{{ url('favoriten/kategorieverwaltung') }}">{{ ucfirst( trans('navigation.kategorieverwaltung') ) }}</a>
                        </li>
                    </ul>
            </li>
            <!--end favorites-->
            <!--erweiterte suche-->
            <li>
                <a href="{{ url('suche') }}">{{ ucfirst( trans('navigation.advanced_search') ) }}
                    <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                         <li>
                            <a href="{{ url('tipps-und-tricks') }}">{{ ucfirst( trans('navigation.tipsAndTricks') ) }}</a>
                        </li>
                    </ul>
            </li>
            <!--end erweiterte suche-->
            <!--dokumente-->
            @if(!empty($documentTypesMenu))
                        
                @foreach($documentTypesMenu as $documentTypeMenu)
                
                    @if($documentTypeMenu->active)
                    
                        @if($documentTypeMenu->visible_navigation)
                    
                            @if($documentTypeMenu->id == 1)
                                <li> <a href="{{ url('dokumente/news') }}">{{ $documentTypeMenu->name }}</a> </li>
                            @elseif($documentTypeMenu->id == 2)
                                <li> <a href="{{ url('dokumente/rundschreiben') }}">{{ $documentTypeMenu->name }}</a> </li>
                            @elseif($documentTypeMenu->id == 3)
                                <li> <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ $documentTypeMenu->name }}</a> </li>
                            @elseif($documentTypeMenu->id == 4)
                                <li>
                                    <a href="{{url('iso-dokumente')}}">{{ $documentTypeMenu->name }}
                                        @if(!empty($isoCategories)) <span class="fa arrow"></span> @endif
                                    </a>
                                    
                                    @if(!empty($isoCategories))
                                    <ul class="nav nav-third-level">
                                        @foreach($isoCategories as $isoCategory)
                                            @if($isoCategory->parent)
                                            <li>
                                                <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }} 
                                                @if( count($isoCategory->isIsoCategoryParent) )<span class="fa arrow"></span>@endif</a>
                                                <ul class="nav nav-fourth-level">
                                                @foreach($isoCategories as $isoCategoryChild)
                                                    @if($isoCategoryChild->iso_category_parent_id == $isoCategory->id)
                                                        <li><a href="{{ url('iso-dokumente/'. $isoCategoryChild->slug ) }}">{{$isoCategoryChild->name}}</a></li>
                                                    @endif
                                                @endforeach
                                                </ul>
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                            @elseif($documentTypeMenu->id == 5)
                                <li> <a href="{{ url('dokumente/vorlagedokumente') }}">{{ $documentTypeMenu->name }}</a> </li>
                            @elseif(in_array($documentTypeMenu->id, [7,8]))
                                {{-- DONT SHOW JURIST DOKUS --}}
                            @elseif($documentTypeMenu->id != 1 )
                                <li> <a href="{{ url('dokumente/typ/' . str_slug($documentTypeMenu->name)) }}">{{ $documentTypeMenu->name }}</a> </li>
                            @endif
                    
                        @endif
                    
                    @endif
                    
                @endforeach
                
            @endif
                
            <li>
                <a class="main-doc-url" href="{{ url('dokumente') }}">{{ ucfirst( trans('navigation.documents') ) }} <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    
                    @if(!empty($documentTypesSubmenu))
                        
                        @foreach($documentTypesSubmenu as $documentTypeSubmenu)
                        
                            @if($documentTypeSubmenu->active)
                            
                                @if($documentTypeSubmenu->visible_navigation)
                            
                                    @if($documentTypeSubmenu->id == 1)
                                        <li> <a href="{{ url('dokumente/news') }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @elseif($documentTypeSubmenu->id == 2)
                                        <li> <a href="{{ url('dokumente/rundschreiben') }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @elseif($documentTypeSubmenu->id == 3)
                                        <li> <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @elseif($documentTypeSubmenu->id == 4)
                                        <li>
                                            <a href="{{url('iso-dokumente')}}">{{ $documentTypeSubmenu->name }}
                                                @if(!empty($isoCategories)) <span class="fa arrow"></span> @endif
                                            </a>
                                            
                                            @if(!empty($isoCategories))
                                            <ul class="nav nav-third-level">
                                                @foreach($isoCategories as $isoCategory)
                                                    @if($isoCategory->parent)
                                                    <li>
                                                        <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }}
                                                        @if( count($isoCategory->isIsoCategoryParent) )<span class="fa arrow"></span>@endif</a>
                                                        <ul class="nav nav-fourth-level">
                                                        @foreach($isoCategories as $isoCategoryChild)
                                                            @if($isoCategoryChild->iso_category_parent_id == $isoCategory->id)
                                                                <li><a href="{{ url('iso-dokumente/'. $isoCategoryChild->slug ) }}">{{$isoCategoryChild->name}}</a></li>
                                                            @endif
                                                        @endforeach
                                                        </ul>
                                                    </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                    @elseif($documentTypeSubmenu->id == 5)
                                        <li> <a href="{{ url('dokumente/vorlagedokumente') }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @elseif(in_array($documentTypeSubmenu->id, [7,8]))
                                        {{-- DONT SHOW JURIST DOKUS --}}
                                    @elseif($documentTypeSubmenu->id != 1 )
                                        <li> <a href="{{ url('dokumente/typ/' . str_slug($documentTypeSubmenu->name)) }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @endif
                            
                                @endif
                            
                            @endif
                            
                        @endforeach
                        
                    @endif
                   
                    
                   
                    @if( ViewHelper::canCreateEditDoc() == true ) {{-- 11,13 --}}
                        <li>
                            <a href="{{ url('dokumente/create') }}">{{ ucfirst( trans('navigation.document') ) }} {{ trans('navigation.anlegen') }}</a>
                        </li>
                    @endif
                </ul>

            </li>
            
            <!--telefonliste-->
           <li>
                <a href="{{ url('telefonliste') }}">{{ ucfirst( trans('navigation.phonebook') ) }}</a>
            </li>
            <!--end telefonliste-->
            
            {{--
            <li>
                <a href="#">{{ ucfirst( trans('navigation.wiki') ) }}</a>
            </li>
            --}}
            
            <!--materialien-->  
            @if( ViewHelper::universalHasPermission( array(7,34) ) == true )
                
                <li>
                    <a href="{{ url('inventarliste') }}">
                        {{ ucfirst( trans('navigation.inventoryList') )}}
                        <span class="fa arrow"></span>
                    </a>
                    @if( ViewHelper::universalHasPermission( array(34) ) == true )
                        <ul class="nav nav-second-level collapse">
                            <li>
                                <a href="{{ url('inventarliste/kategorien') }}">{{ ucfirst( trans('navigation.inventarCategory') )}}</a>
                            </li>
                            <li>
                                <a href="{{ url('inventarliste/groessen') }}">{{ ucfirst( trans('navigation.inventarSizes') )}}</a>
                            </li>
                            <li>
                                <a href="{{ url('inventarliste/create') }}">{{ ucfirst( trans('navigation.newInventory') )}}</a>
                            </li>
                            <li>
                                <a href="{{ url('inventarliste/abrechnen') }}">{{ ucfirst( trans('navigation.deduct') )}}</a>
                            </li>
                           
                        </ul>
                    @endif
                </li>
            @endif
            <!--end materialien-->
            
            <!--wiki-->
            @if( ViewHelper::universalHasPermission( array(15,16) ) == true ) 
                <li class="">
                    <a href="{{ url('wiki') }}">{{ ucfirst(trans('navigation.wiki')) }}
                        <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
    
                        {{--
                        <li>
                            <a href="{{ url('wiki') }}">{{ ucfirst(trans('wiki.wikiList')) }}</a>
                        </li>
                        --}}
                        
                        @if( ViewHelper::canViewWikiManagmentAdmin() == true ) {{-- 15 --}}
                            <li>
                                <a href="{{ url('wiki/verwalten-admin') }}">{{ ucfirst( trans('wiki.verwalten') ) }} </a>
                            </li>
                        @else
                            {{--
                            <li>
                                <a href="{{ url('wiki/verwalten') }}">{{ ucfirst( trans('wiki.verwalten') ) }} </a>
                            </li>
                            --}}
                        @endif  
                        
                        @if( ViewHelper::universalHasPermission( array(15) ) == true ) 
                            <li>
                                <a href="{{ url('wiki-kategorie') }}">{{ ucfirst( trans('wiki.wikiCategory') ) }} </a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(15) ) == true ) 
                            <li>
                                <a href="{{ url('wiki/create') }}">{{ ucfirst( trans('wiki.wikiCreate') ) }} </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <!--end wiki-->
            
            <!--notiz-->
            @if( ViewHelper::universalHasPermission( array(35, 36), false ) == true ) 
                <li class="">
                    <a href="{{ url('notiz/create') }}">{{ ucfirst(trans('juristenPortal.createNotes')) }}</a>
                    <!--<a href="{{ url('beratungsportal/notiz') }}">{{ ucfirst(trans('navigation.notes')) }}</a>-->
                </li>
            @endif
            <!-- end notiz -->
            
             <!--beratungsportal-->
            @if( ViewHelper::universalHasPermission( array(35, 36), false ) == true ) 
                <li class="">
                    <a href="{{ url('beratungsportal') }}">{{ ucfirst(trans('juristenPortal.juristenportal')) }}
                        <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li>
                                <a href="{{ url('beratungsportal/calendar') }}"> @lang('juristenPortal.juristTemplates')
                                <!--<span class="fa arrow"></span>-->
                                </a>
                                
                                <!--<ul class="nav nav-third-level">-->
                                <!--    <li>-->
                                <!--        <a href="{{ url('#') }}">@lang('juristenPortal.juristTemplatesCreate')</a>-->
                                <!--    </li>-->
                                    
                                <!--</ul>    -->
                            </li><!--end Wiedervorlage -->
                            <li>
                                <a href="{{ url('notiz') }}">@lang('juristenPortal.notes')<span class="fa arrow"></span></a>
                                
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="{{ url('notiz/create') }}">@lang('juristenPortal.notesCreate')</a>
                                    </li>
                                </ul>    
                            </li><!--end notiz -->
                            <li>
                                <a href="{{ url('#') }}">@lang('juristenPortal.akten')<span class="fa arrow"></span></a>
                                
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="{{ url('#') }}">@lang('juristenPortal.createAkten')</a>
                                    </li>
                                </ul>    
                                
                                
                            </li><!--end akten -->
                            
                            <!--Beratung Documents-->
                            <li>
                                <a href="{{ url('beratung-kategorien/alle') }}">@lang('juristenPortal.beratungDocuments')
                                <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">{{ ucfirst( trans('juristenPortal.createBeratung') ) }} </a>
                                        </li>
                                        @if($juristenCategoriesBeratung)
                                            @foreach( $juristenCategoriesBeratung as $jueristenCategory)
                                                <li>
                                                <a href="{{url('beratung-kategorien/'.$jueristenCategory->id)}}">{{ $jueristenCategory->name }}
                                                    @if( count($jueristenCategory->juristCategoriesBeratungActive) ) <span class="fa arrow"></span> @endif
                                                </a>
                                                    @if( count($jueristenCategory->juristCategoriesBeratungActive) )
                                                    <ul class="nav nav-fourth-level">
                                                        @foreach( $jueristenCategory->juristCategoriesBeratungActive as $subLevel1)
                                                        <li>
                                                            <a href="{{url('beratung-kategorien/'.$subLevel1->id)}}">{{ $subLevel1->name }}
                                                                @if( count($subLevel1->juristCategoriesBeratungActive) ) <span class="fa arrow"></span> @endif
                                                            </a>
                                                            @if( count( $subLevel1->juristCategoriesBeratungActive ) )
                                                            <ul class="nav nav-five-level">
                                                                @foreach( $subLevel1->juristCategoriesBeratungActive as $subLevel2)
                                                                    <li>
                                                                        <a href="{{url('beratung-kategorien/'.$subLevel2->id)}}">{{ $subLevel2->name }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                            @endif
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                            @endforeach {{-- first level subcategory --}}
                                        
                                        @endif
                                </ul>
                               
                            </li><!-- End Beratung Documents-->
                            
                            <!-- Rechtsablage Documents -->
                            <li>
                                <a href="{{ url('rechtsablage-kategorien/alle') }}">@lang('juristenPortal.documentsRechtsablage')
                                    <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">{{ ucfirst( trans('juristenPortal.createRechtsablage') ) }} </a>
                                        </li>
                                       
                                    @if(count($juristenCategories))
                                      
                                        @foreach( $juristenCategories as $jueristenCategory)
                                            <li>
                                            <a href="{{url('rechtsablage-kategorien/'.$jueristenCategory->id)}}">{{ $jueristenCategory->name }}
                                                @if( count($jueristenCategory->juristCategoriesActive) ) <span class="fa arrow"></span> @endif
                                            </a>
                                            @if( count($jueristenCategory->juristCategoriesActive) )
                                            <ul class="nav nav-fourth-level">
                                                @foreach( $jueristenCategory->juristCategoriesActive as $subLevel1)
                                                <li>
                                                    <a href="{{url('rechtsablage-kategorien/'.$subLevel1->id)}}">{{ $subLevel1->name }}
                                                        @if( count($subLevel1->juristCategoriesActive) ) <span class="fa arrow"></span> @endif
                                                    </a>
                                                    @if( count( $subLevel1->juristCategoriesActive ) )
                                                    <ul class="nav nav-five-level">
                                                        @foreach( $subLevel1->juristCategoriesActive as $subLevel2)
                                                            <li>
                                                                <a href="{{url('rechtsablage-kategorien/'.$subLevel2->id)}}">{{ $subLevel2->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                            </li>
                                        @endforeach {{-- first level subcategory --}}
                                        
                                          
                                    @endif
                                </ul><!-- end .nav-second-level -->
                            </li>
                            <!-- End Rechtsablage Documents-->
                            
                        <li>
                            <a href="{{ url('beratungsportal/upload') }}">{{ ucfirst( trans('juristenPortal.upload') ) }} </a>
                        </li>
                            
                            
                    </ul>
                </li>
            @endif
            <!--end beratungsportal-->
            
            <!--mandantenverwaltung-->
            @if( ViewHelper::universalHasPermission( array(17, 18, 20) ) == true )
                {{-- removed neptun Verwalter NEPTUN-610 --}}
                <li class="">
                    <a href="{{ url('mandantenverwaltung') }}">{{ ucfirst(trans('navigation.mandantenverwaltung')) }}
                        <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        @if( ViewHelper::universalHasPermission( array(17, 18) ) == true ) 
                            <li>
                                <a href="{{ url('mandanten') }}">{{ ucfirst(trans('navigation.ubersicht')) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(20) ) == true ) 
                            <li>
                                <a href="{{ url('mandanten/export') }}">{{ ucfirst( trans('navigation.mandanten') ) }} {{ trans('navigation.export') }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(18) ) == true )
                            <li>
                                <a href="{{ url('mandanten/create') }}">{{ ucfirst( trans('navigation.mandanten') ) }} {{ trans('navigation.anlegen') }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(17) ) == true )
                            <li>
                                <a href="{{ url('benutzer/create') }}">{{ ucfirst( trans('navigation.benutzer') ) }} {{ trans('navigation.anlegen') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <!--end mandantenverwaltung-->
            
            <!--benutzer-->
            @if( (ViewHelper::universalHasPermission() == false) && (ViewHelper::universalHasPermission( array(2,4)) == true) ) {{-- 2,4 NEPTUN-605 --}}
                <li>
                    <a href="{{ url('benutzer') }}">{{ ucfirst( trans('navigation.benutzer') ) }}<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li>
                            <a href="{{ url('benutzer/create-partner') }}">{{ ucfirst( trans('navigation.benutzer') ) }} {{ trans('navigation.anlegen') }}</a>
                        </li>
                    </ul>
                    
                </li>
              
            @endif
            <!--end benutzer-->
            
            <!--neptun verwalten-->
            @if( ViewHelper::universalHasPermission( array(6, 35) ) == true )
                <li class="">
                    <a href="{{ url('neptun-verwaltung') }}">
                        NEPTUN-{{ ucfirst( trans('navigation.verwaltung') )}}
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        
                        @if( ViewHelper::universalHasPermission( array(6) ) == true )
                            <li>
                                <a href="{{ url('benutzer/standard-benutzer') }}">{{ ucfirst( trans('contactForm.defaultUser') ) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(6) ) == true )
                            <li>
                                <a href="{{ url('kontaktanfragen') }}">{{ ucfirst( trans('contactForm.kontaktanfragen') ) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(6) ) == true )
                            <li>
                                <a href="{{ url('empfangerkreis') }}">{{ ucfirst( trans('navigation.adressate') ) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(6) ) == true )
                            <li>
                                <a href="{{ url('dokument-typen') }}">{{ ucfirst( trans('navigation.document') ) }}-{{ ucfirst( trans('navigation.types') ) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(6) ) == true )
                            <li>
                                <a href="{{ url('iso-kategorien') }}">{{ ucfirst( trans('navigation.iso') ) }}-{{ trans('navigation.kategorien') }} </a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(35), false ) == true ) 
                            <li>
                                <a href="{{ url('rechtsablage-kategorien') }}">{{ ( trans('navigation.juristenPortalRechtsablage') ) }}-{{ trans('navigation.kategorien') }} </a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(35), false ) == true ) 
                            <li>
                                <a href="{{ url('rechtsablage/meta-info') }}">{{ ( trans('navigation.juristenPortalRechtsablageMeta') ) }}-{{ trans('navigation.kategorien') }} </a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(35), false ) == true ) 
                            <li>
                                <a href="{{ url('beratung-kategorien') }}">{{ ( trans('navigation.juristenPortalBeratung') ) }}-{{ trans('navigation.kategorien') }} </a>
                            </li>
                        @endif
                        @if( ViewHelper::universalHasPermission( array(35), false ) == true ) 
                            <li>
                                <a href="{{ url('beratungsportal/meta-info') }}">{{ ( trans('navigation.juristenPortalBeratungMeta') ) }}-{{ trans('navigation.kategorien') }} </a>
                            </li>
                        @endif
                        
                        
                        @if( ViewHelper::universalHasPermission( array(35), false ) == true ) 
                            <li>
                                <a href="{{ url('wiedervorlagen-status') }}">{{ ucfirst( trans('navigation.wiedervorlagenStatus') ) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(35), false ) == true ) 
                            <li>
                                <a href="{{ url('beratungsportal/aktenart') }}">{{  trans('navigation.aktenArt') }}</a>
                            </li>
                        @endif
                        
                        
                        @if( ViewHelper::universalHasPermission( array(6) ) == true ) 
                            <li>
                                <a href="{{ url('rollen') }}">{{ ucfirst( trans('navigation.rollenverwatung') ) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(6) ) == true ) 
                            <li>
                                <a href="{{ url('tipps-und-tricks/create') }}">{{ ucfirst( trans('navigation.tipsAndTricks') ) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission() == true )
                            <li>
                                <a href="{{ url('neptun-verwaltung/datenbank-bereinigen') }}">{{ ucfirst( trans('maintenance.databaseCleanup') ) }}</a>
                            </li>
                        @endif
                        
                    </ul>
                </li>
            @endif
            <!--end neptun verwalten-->
            
            <!--papierkorb-->
            @if( ViewHelper::universalHasPermission( array(11,13) ) == true ) 
                <li>
                    <a href="{{ url('papierkorb') }}">{{ ucfirst( trans('navigation.trash') ) }}</a>
                </li>
            @endif
            <!--end papierkorb-->
            
            
            
            <li class="legend"> <!-- legend - start -->
                <span class="legend-text">Legende</span>
                <span id="btn-legend" class="icon-legend pull-right" title="Legende Ein-/Ausblenden"></span>
            </li> <!-- legend - end -->
            
            <div class="clearfix"></div>
        </ul>
    </div>
    
    
    <div class="legend-wrapper legend">
        <ul class="legend-ul nav">
            <li>
                <span class="legend-text">neues Dokument</span>
                <span class="legend-icons icon-favorites "></span>
            </li>
            <li>
                <span class="legend-text">nicht freigegeben</span>
                <span class="legend-icons icon-blocked"></span>
            </li>
            <li>
                <span class="legend-text">freigegeben</span>
                <span class="legend-icons icon-open"></span>
            </li>
            <li>
                <span class="legend-text">muss gelesen werden</span>
                <span class="legend-icons icon-notread "></span>
            </li>
            <li>
                <span class="legend-text">gelesen</span>
                <span class="legend-icons icon-read"></span>
            </li>
            <li>
                <span class="legend-text">Entwurf</span>
                <span class="legend-icons icon-draft"></span>
            </li>
            <li>
                <span class="legend-text">nicht veröffentlicht</span>
                <span class="legend-icons icon-notreleased"></span>
            </li>
            <li>
                <span class="legend-text">veröffentlicht</span>
                <span class="legend-icons icon-released"></span>
            </li>
            <li>
                <span class="legend-text">freigegeben, nicht veröffentlicht</span>
                <span class="legend-icons icon-approvedunpublished"></span>
            </li>
            <li>
                <span class="legend-text">Historie vorhanden</span>
                <span class="legend-icons icon-history"></span>
            </li>
            <li>
                <span class="legend-text">Download</span>
                <span class="legend-icons icon-download"></span>
            </li>
            <li>
                <span class="legend-text">Auswahl aufheben</span>
                <span class="legend-icons icon-reset"></span>
            </li>
        </ul>
    </div>

</div>



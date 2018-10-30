
    @if(count($usersActive)>0)
        @if(count($usersActive)==1)
            (1 aktiver
        @else
            ({{count($usersActive) . " aktive" }}
        @endif
    @else
        @if(count($usersActive) == 0 && count($usersInactive) != 0)
            (0 aktive /
        @endif
    @endif
    
    @if(count($usersInactive)>0)
        @if(count($usersInactive)==1)
            @if(count($usersActive) != 0)/ @endif 1 inaktiver
        @else
            @if(count($usersActive) != 0)/  @endif
            {{count($usersInactive) . " inaktive" }}     
        @endif
    @endif
    
    @if(count($usersActive) == 0 && count($usersInactive) == 0) (0 aktive @endif
    Benutzer)
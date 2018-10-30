<div id="absolute" class="absolute">
    <div class="page-number number-{PAGENO}">
        - {PAGENO} -
    </div>
    @if($document->landscape == false)
        <div class="footer-line-left footer-line">
            <img  class="left-image" src={{url("/img/left-line.jpg")}} style="" alt="Neptun logo"/>
        </div>
        <div class="footer-box box-1">
            <p><b>NEPTUN</b> Verwaltungs-<br/>
            und Beteiligungsgesellschaft mbH<br/>
            Eschenstraße 8 <br/>
            82024 Taufkirchen 
            </p>
        </div>
        <div class="footer-box box-2">
            <p>
                Telefon +49 89 51 29 010<br/>
                Fax +49 89 61 29 0 122<br/>
                kontakt@neptun-gmbh.de<br/>
                www.neptun-gmbh.de
            </p>
        </div>
        <div class="footer-box box-3">
            <p>Commerzbank AG<br/>
                München<br/>
                IBAN DE19 7008 0000 0603 0660 00<br/>
                BIC: DRESDEFF700
            </p>
        </div>
        <div class="footer-box box-4">
            <p>
                Siz Taufkirchen<br/>
                Amtsgericht München<br/>
                HRB 74557<br/>
                Geschäftsfürein Bettina Engel
            </p>
        </div>
        
        <div class=" footer-line footer-line-right">
            <img src={{url("/img/right-line.jpg")}}  alt="Neptun logo"/>
        </div>
    @endif
</div>

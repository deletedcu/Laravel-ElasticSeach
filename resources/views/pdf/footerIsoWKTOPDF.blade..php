<div class="footer">
       <p>&copy; Neptun GmbH</p>
       <p>Revisionsstand 05</p>
       <p>Taufkirchen, {{ $dateNow }}</p>
       <script type="text/php">
                 $text = '{PAGE_NUM}/{PAGE_COUNT}';
                 $font = Font_Metrics::get_font("Verdana", "normal");
                 $y = $pdf->get_height() - 24;
                 $x = $pdf->get_width()/2 - Font_Metrics::get_text_width('1/1', $font,9);
                 $pdf->page_text($x, $y, $text, $font, 9);
            </script>
</div>
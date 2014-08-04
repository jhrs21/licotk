<div id="main-container" class="span-24">
    <?php include_partial('html_static/colorBanner') ?>
    <div class="main-container-inner">
        <div id="prizes-container" class="main-canvas">
            <form action="index" method="post">
                <div class="paging_container container2">
                    <div id="top-paginator" class="page_navigation"></div>
                    <div class="content">
                        <div id="first_step" class="step">
                            <div id="form1"><?php echo $form1 ?>
                                <?php echo $form2 ?></div>
                            <div id="mapCanvas" ></div>
                            <div id="infoPanel">
                                <div id="markerStatus"></div>
                                <div id="info"></div>
                                <div id="address"></div>
                            </div>
                        </div>  
                        <div id="fourth_step" style="margin-bottom:4px;" class="step">
                            <?php echo $formP ?>

                            <input type="submit" value="Enviar" />
                        </div>
                    </div>
                    <div id="bottom-paginator" class="page_navigation"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.paging_container').pajinate({items_per_page : 1, item_container_id : '.content', nav_panel_id : '.page_navigation', show_first_last : false});
    });

    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Novimbre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
    var daysShort = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
    var daysMin = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'];

    $(document).ready(function(){        
        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            monthNames: months,
            monthNamesShort: monthsShort,
            dayNames: days,
            dayNamesShort: daysShort,
            dayNamesMin: daysMin,
            dateFormat: 'dd/mm/yy',
            yearRange: 'c:c+100'
        });


        $('#sign-up-form').bValidator();

        $('.submit').click(function(event){
            event.preventDefault();

            if($('#sign-up-form').data('bValidator').validate()){
                $('#sign-up-form').submit();
            }
            else
            {
                return false;
            }
        });
    })
    
    var geocoder = new google.maps.Geocoder();
    var map;
    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                console.log(responses[0].address_components[1].long_name);
                updateMarkerAddress(responses[0].formatted_address);
            } else {
                updateMarkerAddress('Cannot determine address at this location.');
            }
        });
    }

    function updateMarkerStatus() {}

    function updateMarkerPosition(latLng) {
        document.getElementById("secondStep_latitude").value = latLng.lat();
        document.getElementById("secondStep_longitude").value = latLng.lng();
    }

    function updateMarkerAddress(str) {
        document.getElementById("secondStep_address").value = str;
    }
    
    function initialize() {
        var latLng = new google.maps.LatLng(10.476102,-66.855304);
        var map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 13,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        var marker = new google.maps.Marker({
            position: latLng,
            title: 'Point A',
            map: map,
            draggable: true
        });
  
        // Update current position info.
        updateMarkerPosition(latLng);
        geocodePosition(latLng);
  
        // Add dragging event listeners.
        google.maps.event.addListener(marker, 'dragstart', function() {
            updateMarkerAddress('');
        });
  
        google.maps.event.addListener(marker, 'drag', function() {
            updateMarkerStatus('Dragging...');
            updateMarkerPosition(marker.getPosition());
        });
  
        google.maps.event.addListener(marker, 'dragend', function() {
            updateMarkerStatus('Drag ended');
            geocodePosition(marker.getPosition());
        });
        /**/
    }
    
    // Onload handler to fire off the app.
    google.maps.event.addDomListener(window, 'load', initialize);

    var prizes = <?php echo $formP['prizes']->count() ?>;
    var terms = <?php echo $formP['terms']->count() ?>;

    function addPrize(num) {
        var r = $.ajax({
            type: 'GET',
            url: '<?php echo url_for('promo_add_prize') ?>'+'<?php echo ($formP->getObject()->isNew() ? '' : '?id=' . $formP->getObject()->getId()) . ($formP->getObject()->isNew() ? '?count=' : '&count=') ?>'+num,
            async: false
        }).responseText;
        
        return r;
    }
    
    function addTerm(num) {
        var r = $.ajax({
            type: 'GET',
            url: '<?php echo url_for('promo_add_term') ?>'+'<?php echo ($formP->getObject()->isNew() ? '' : '?id=' . $formP->getObject()->getId()) . ($formP->getObject()->isNew() ? '?count=' : '&count=') ?>'+num,
            async: false
        }).responseText;
        
        return r;
    }
        
    $(document).ready(function() {
        $( ".form_row > .form_row_field > table:first" ).append(' <?php echo escape_javascript("<tfoot><tr><td colspan=\"2\">" . "<a id='add_prize'>Add Prize</a>&nbsp;<img id=\"prize_loader\" src=\"/images/loader16x16.gif\">" . "</td></tr></tfoot>") ?>');
        $( ".form_row > .form_row_field > table:last" ).append('<?php echo escape_javascript("<tfoot><tr><td colspan=\"2\">" . "<a id='add_term'>Add Term</a>&nbsp;<img id=\"term_loader\" src=\"/images/loader16x16.gif\">" . "</td></tr></tfoot>") ?>' );
                                            
        $('#prize_loader').hide();
        
        $('#term_loader').hide();
                                    
        $('a#add_prize').click(function() {
            $('#prize_loader').show();
            $('#fourth_step table tbody').first().append(addPrize(prizes));
            $('#prize_loader').hide();
            prizes = prizes + 1;
        });
        
        $('a#add_term').click(function() {
            $('#term_loader').show();
            $('#fourth_step table:last tbody').append(addTerm(terms));
            $('#term_loader').hide();
            terms = terms + 1;
        });
        
    });
</script>
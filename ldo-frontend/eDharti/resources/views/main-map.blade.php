<!DOCTYPE html>
<html lang="en">

<head>
    <title>MIS</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font SS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather|Open+Sans">
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.min.css">

<!-- JSQUERY CSS -->

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">

<!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css"rel="stylesheet"> -->
   
    <!-- Leaflet CSS-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{asset('assets/MIS_V3/leaflet-labeler-master/leaflet-labeler.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-tooltip@1.0.4/dist/tooltip.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://ptma.github.io/Leaflet.Legend/src/leaflet.legend.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-measure/dist/leaflet-measure.css" />
    <link rel="stylesheet" href="https://scanex.github.io/Leaflet-IconLayers/src/iconLayers.css">
    <link rel="stylesheet" href="{{asset('assets/MIS_V3/style.css')}}">   
</head>
<body>
        
    <header id="header">
        <div class="custom-inner-header">
            <div class="header-logo">
                <img style="height:auto;"
                    src="https://dfwgnctd.maps.arcgis.com/sharing/rest/content/items/7292401d0ef943b584440df376f0b3ee/resources/media/instantAppsConfigPanel_1717672004862_customHeaderHTML_textEditor_1717672004472.png"
                    class="e-dharti-logo">
            </div>
            <div class="brand_label">
                <h3>e-Dharti Geo-Portal 2.0</h3>
                <a href="https://dfwgnctd.maps.arcgis.com/apps/instant/3dviewer/index.html?appid=7292401d0ef943b584440df376f0b3ee" target="_blank" class="neon_animation">MetaVerse</a>
            </div>
            <!-- Additional header content if needed -->
        </div>
    </header>
    <main>
        
        <!-- change the DIV here -->
        
        <div id="map" class="map_view"></div>
            <div>
            <!-- <button class="leaflet-control-geocoder-icon" type="button" aria-label="Initiate a new search"><i class="fa-solid fa-magnifying-glass-location"></i></button> -->
            <div class="leaflet-control-geocoder-form"><input class="" type="text" placeholder="Search your location"></div>
            <a href="https://dfwgnctd.maps.arcgis.com/apps/instant/3dviewer/index.html?appid=7292401d0ef943b584440df376f0b3ee" target="_blank" class="threed_btn" title="3D View">3D</a>
            <div class="map-search-float">
                <form id="searchForm">
                    <div class="form-group search-bar">
                        <input type="text" class="form-control" id="propertyId"
                            placeholder="Search by Property ID/Vacant Land">
                        <div class="search_btn-group">
                            <button type="submit" class="search_btn"><i class="fas fa-search"></i></button>
                            <button type="button" class="trash_btn" id="trash" title="Clear View"><i class="fas fa-times" ></i></button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    
        <!-- Form for Search -->
        

        <!-- Table for property details -->
        <div id="floating-table" style="display: none;">
            <button type="button" class="close" aria-label="Close" id="closeFloatingTable">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4>Property Details</h4>
            <table id="property-table" class="table">
                <thead>
                    <tr>
                        <th>Attribute</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table body dynamic -->
                </tbody>
            </table>
            <!-- <button id="downloadPDFButton">Download PDF</button> -->
            <a id="downloadPDF" class="text-primary"> <i class="fas fa-file-pdf text-danger"></i> Download PDF</a>
        </div>
    </main>


<footer id= "footer" class="footer-color text-center w-100 py-1">
    <p class="text-white mb-0">© 2024 Land & Development Office. All rights reserved.</p>
</footer>

</div>

</body>
<!-- SCRIPT -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Bootstrap Script  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script> -->
   
    <!-- LEAFLET -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.4.4/proj4.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="./leaflet-labeler-master/leaflet-<!DOCTYPE html>
<html lang="en">

<head>
    <title>MIS</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font SS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather|Open+Sans">
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.min.css">

<!-- JSQUERY CSS -->

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">

<!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css"rel="stylesheet"> -->
   
    <!-- Leaflet CSS-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="./leaflet-labeler-master/leaflet-labeler.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-tooltip@1.0.4/dist/tooltip.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://ptma.github.io/Leaflet.Legend/src/leaflet.legend.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-measure/dist/leaflet-measure.css" />
    <link rel="stylesheet" href="https://scanex.github.io/Leaflet-IconLayers/src/iconLayers.css">
    <link rel="stylesheet" href="./style.css">   
</head>
<body>

    <main>
        <main>
        
            <header id="header">
                <div class="custom-inner-header">
                    <div class="header-logo">
                        <img style="height:auto;"
                            src="https://dfwgnctd.maps.arcgis.com/sharing/rest/content/items/7292401d0ef943b584440df376f0b3ee/resources/media/instantAppsConfigPanel_1717672004862_customHeaderHTML_textEditor_1717672004472.png"
                            class="e-dharti-logo">
                    </div>
                    <div class="brand_label">
                        <h3>e-Dharti Geo-Portal 2.0</h3>
                        <a href="https://dfwgnctd.maps.arcgis.com/apps/instant/3dviewer/index.html?appid=7292401d0ef943b584440df376f0b3ee" target="_blank" class="neon_animation">MetaVerse</a>
                    </div>
                    <!-- Additional header content if needed -->
                </div>
            </header>
            <main>
                
                <!-- change the DIV here -->
                
                <div id="map" class="map_view"></div>
                    <div>
                    <!-- <button class="leaflet-control-geocoder-icon" type="button" aria-label="Initiate a new search"><i class="fa-solid fa-magnifying-glass-location"></i></button> -->
                    <div class="leaflet-control-geocoder-form"><input class="" type="text" placeholder="Search your location"></div>
                    <a href="https://dfwgnctd.maps.arcgis.com/apps/instant/3dviewer/index.html?appid=7292401d0ef943b584440df376f0b3ee" target="_blank" class="threed_btn" title="3D View">3D</a>
                    <div class="map-search-float">
                        <form id="searchForm">
                            <div class="form-group search-bar">
                                <input type="text" class="form-control" id="propertyId"
                                    placeholder="Search by Property ID/Vacant Land">
                                <div class="search_btn-group">
                                    <button type="submit" class="search_btn"><i class="fas fa-search"></i></button>
                                    <button type="button" class="trash_btn" id="trash" title="Clear View"><i class="fas fa-times" ></i></button>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            
                <!-- Form for Search -->
                
    
                <!-- Table for property details -->
                <div id="floating-table" style="display: none;">
                    <button type="button" class="close" aria-label="Close" id="closeFloatingTable">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4>Property Details</h4>
                    <table id="property-table" class="table">
                        <thead>
                            <tr>
                                <th>Attribute</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table body dynamic -->
                        </tbody>
                    </table>
                    <!-- <button id="downloadPDFButton">Download PDF</button> -->
                    <button id="downloadPDF" class="btn btn-primary">Download PDF</button>
                </div>
            </main>
        </main>
    
        <footer id= "footer" class="footer-color text-center w-100 py-1">
            <p class="text-white mb-0">© 2024 Land & Development Office. All rights reserved.</p>
        </footer>
    
    </div>
    
</body>
<!-- SCRIPT -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Bootstrap Script  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script> -->
   
    <!-- LEAFLET -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.4.4/proj4.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{asset ('assets/MIS_V3/leaflet-labeler-master/leaflet-labeler.js')}}"></script>
    <script src="https://unpkg.com/geojson-vt@3.2.0/geojson-vt.js"></script>
    <script src="https://unpkg.com/leaflet.vectorgrid@latest/dist/Leaflet.VectorGrid.js"></script>
    <script src="https://unpkg.com/@mapbox/leaflet-vector-grid@latest/dist/Leaflet.VectorGrid.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-tooltip@1.0.4/dist/L.Tooltip.min.js"></script>
    <script src="https://unpkg.com/leaflet-measure/dist/leaflet-measure.js"></script>
    <script src=" https://ish.lol/leaflet-groupedlayercontrol/src/leaflet.groupedlayercontrol.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-tooltip@1.0.4/dist/L.Tooltip.min.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://ptma.github.io/Leaflet.Legend/src/leaflet.legend.js"></script>
    <script src="https://scanex.github.io/Leaflet-IconLayers/src/iconLayers.js"></script>

    <!-- jSPDF -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
         <!-- leafletprint -->
        <!-- Print -->
<script src="https://cdn.jsdelivr.net/npm/leaflet.browser.print@2.0.2/dist/leaflet.browser.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- Geojson Data -->
    <!-- <script src="{{asset('assets/MIS_V3/Geojson_s/Property_10739.js')}}"></script> -->
    <script src="{{asset('assets/MIS_V3/Geojson_s/Property_16045.js')}}"></script>
    <!-- <script src="{{asset('assets/MIS_V3/Geojson_s/Vacant_Land.js')}}"></script> -->
    <script src="{{asset('assets/MIS_V3/Geojson_s/VacantLand_P3.js')}}"></script>
    <script src="{{asset('assets/MIS_V3/Logo/logo.js')}}"></script>
    <script src="{{asset('assets/MIS_V3/main.js')}}"></script>


    </html>
    

document.addEventListener("DOMContentLoaded", function () {
  var google = L.tileLayer(
    "https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}",
    {
      zIndex: 50,
      opacity: 1,
      maxZoom: 20,
      subdomains: ["mt0", "mt1", "mt2", "mt3"],
      attribution: '©DFW, Google 2023© <a href="" target="_blank"></a>',
    }
  );

  var googlestreet = L.tileLayer(
    "http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}",
    {
      zIndex: 50,
      opacity: 1,
      maxZoom: 20,
      attribution: '© Google 2023© <a href="" target="_blank"></a>',
    }
  );

  var map = L.map("map", {
    center: [28.635781888792277, 77.17983510997755],
    zoom: 20,
    minZoom: 8,
    scrollWheelZoom: true,
    dragging: true,
    doubleClickZoom: true,
    zoomControl: true,
    attributionControl: false,
    layers: [googlestreet],
    maxBounds: [
      [29.00708947145038, 76.64878806249736], // Southwest corner of the bounding box
      [28.363761223602285, 77.53999678065577], // Northeast corner of the bounding box
    ],
  });

  // Geocode(Deb)
  var geocoder = L.Control.geocoder({
    query: "",
    placeholder: "Search your location",
    defaultMarkGeocode: false,
  }).addTo(map);

  var marker = null; // Initialize the marker variable

  // Function to remove the marker
  function removeMarker() {
    if (marker) {
      map.removeLayer(marker); // Remove the existing marker if it exists
      marker = null; // Reset the marker variable
    }
  }

  geocoder.on("markgeocode", function (event) {
    var latlng = event.geocode.center;

    removeMarker(); // Call the function to remove the marker

    marker = L.marker(latlng).addTo(map);
    marker.bindPopup(event.geocode.name).openPopup(); // Display geocode information in a popup on the marker

    map.setView(latlng, 15);
  });

  // Add an event listener to the "Delete Location Marker" button
  document.getElementById("trash").addEventListener("click", function () {
    removeMarker();
  });

  //Load Layer
  var vtLayer = L.vectorGrid
    .slicer(pro, {
      vectorTileLayerStyles: {
        sliced: function (properties, zoom) {
          // Define color mapping based on the status Properties
          var statusColors = {
            "Lease Hold": "#28a745",
            "Free Hold": "#dc3545",
          };

          var color = statusColors[properties.status] || "#dc3545"; // Default color if status not found

          return {
            weight: 1,
            color: color,
            fillColor: color,
            fillOpacity: 0.2,
            radius: 6,
          };
        },
      },

      interactive: true,
      getFeatureId: function (f) {
        return f.properties.old_propert_id;
      },
      rendererFactory: L.canvas.tile,
      unloadInvisibleTiles: false,
      updateWhenIdle: false,
    })
    .on("click", function (e) {
      if (e.layer && e.layer.properties) {
        var props = e.layer.properties;
        var content = `
    <table>
        <tr><td>Property No.:</td><td>${props.old_propert_id}</td></tr>
        <tr><td>Unique Property ID:</td><td>${props.unique_propert_id}</td></tr>
        <tr><td>Locality Name:</td><td>${props.loacalityn}</td></tr>
        <tr><td>Status:</td><td>${props.status}</td></tr>
        <tr><td>Land Use:</td><td>${props.land_use}</td></tr>
        <tr><td>Area Sqmts:</td><td>${props.area_in_sqm}</td></tr>
        <tr><td>Adress:</td><td>${props.address}</td></tr>
        <tr><td>Lessee Name:</td><td>${props.lesse_name}</td></tr>
        <tr><td>Land Type:</td><td>${props.land_type}</td></tr>
        <tr><td>Lease Tenure:</td><td>${props.lease_tenure}</td></tr>
    </table>
`;

        // Show popup
        L.popup().setContent(content).setLatLng(e.latlng).openOn(map);

        //  floating table
        $("#property-table").empty().append(content);
        $("#floating-table").show();
      }
    })
    .on("mouseover", function (e) {
      if (e.layer && e.layer.properties) {
        var props = e.layer.properties;
        var tooltipContent = "Property No.: " + props.old_propert_id;
        e.layer
          .bindTooltip(tooltipContent, { permanent: false, direction: "auto" })
          .openTooltip();
      }
    });

  // Define styles for different properties(Debobrata Sadhukhan)
  var freeHoldStyle = {
    color: "#006599",
    weight: 2,
    opacity: 1,
    fillOpacity: 0.7,
  };

  var leaseHoldStyle = {
    color: "#f0ad4e",
    weight: 2,
    opacity: 1,
    fillOpacity: 0.7,
  };

  // Function to determine the style
  function getStyle(feature) {
    switch (feature.properties.status) {
      case "Free Hold":
        return freeHoldStyle;
      case "Lease Hold":
        return leaseHoldStyle;
      default:
        return {
          color: "grey",
          weight: 2,
          opacity: 1,
          fillOpacity: 0.7,
        };
    }
  }

  // Define the GeoJSON layer with the style
  var property = L.geoJSON(pro, {
    style: getStyle,

    onEachFeature: function (feature, layer) {
      // // Create a marker for labeling
      // var label = L.marker(layer.getBounds().getCenter(), {
      //     icon: L.divIcon({
      //         className: 'label-icon',
      //         html: `
      //             <div>
      //                 <strong>${feature.properties.Name}</strong><br>
      //                 Area (Sqmts): ${feature.properties.Area}
      //             </div>
      //         `,
      //         iconSize: [150, 40], // Adjust as needed
      //         iconAnchor: [75, 20] // Position of the label relative to its center
      //     })
      // }).addTo(map);

      // Handle click event on the GeoJSON layer

      layer.on("click", function () {
        var properties = feature.properties;
        $("#property-table").empty();
        $("#property-table").append(`
        <tr>
            <td>Property No:</td>
            <td>${properties.old_propert_id}</td>
            <td>Unique Property ID:</td>
            <td>${properties.unique_propert_id}</td>
        </tr>
        <tr>
            <td>Locality Name:</td>
            <td>${properties.loacalityn}</td>
            <td>Status:</td>
            <td>${properties.status}</td>
        </tr>
        <tr>
            <td>Land Use:</td>
            <td>${properties.land_use}</td>
            <td>Area (Sqmts):</td>
            <td>${properties.area_in_sqm}</td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>${properties.address}</td>
            <td>Lessee Name:</td>
            <td>${properties.lessee_name}</td>
        </tr>
        <tr>
            <td>Land Type:</td>
            <td>${properties.land_type}</td>
            <td>Lease Tenure:</td>
            <td>${properties.lease_tenure}</td>
        </tr>
    `);

        $("#floating-table").show();
      });
    },
  }).addTo(map);
  // labelProp: 'old_propert_id',
  // labelPos: 'cc',
  // labelStyle: { fontWeight: 'bold', whiteSpace: 'normal', minWidth: '80px', textAlign: 'center' },

  // // Add labels to GeoJSON features using circles
  // pointToLayer: function (feature, latlng) {
  //     return L.circleMarker(latlng, {
  //         radius: 6, // Adjust circle radius as needed
  //         fillColor: "blue",
  //         color: "#000",
  //         weight: 1,
  //         opacity: 1,
  //         fillOpacity: 0.8
  //     }).bindLabel('My label', {
  //         noHide: true,
  //         direction: 'auto'
  //     });
  // }

  // labeler
  var propertyLabeler = L.labeler(pro, {
    labelProp: "old_propert_id",
    labelPos: "cc",
    labelStyle: {
      fontWeight: "bold",
      whiteSpace: "normal",
      minWidth: "60px",
      textAlign: "center",
      color: "black",
      fontFamily: "Times New Roman",
      fontSize: "12px",
    },
    style: { opacity: 0.0 },
    onEachFeature: function (feature, layer) {
      var tooltipContent = `
                                                    <div>
                                                        <strong>Property No.:</strong> ${feature.properties.old_propert_id}<br>
                                                    </div>
                                                `;

      layer.bindTooltip(tooltipContent);
      layer.on("click", function () {
        var properties = feature.properties;
        $("#property-table").empty();
        $("#property-table").append(`
        <tr>
            <td>Property No:</td>
            <td>${properties.old_propert_id}</td>
            <td>Unique Property ID:</td>
            <td>${properties.unique_propert_id}</td>
        </tr>
        <tr>
            <td>Locality Name:</td>
            <td>${properties.loacalityn}</td>
            <td>Status:</td>
            <td>${properties.status}</td>
        </tr>
        <tr>
            <td>Land Use:</td>
            <td>${properties.land_use}</td>
            <td>Area (Sqmts):</td>
            <td>${properties.area_in_sqm}</td>
        </tr>
        <tr>
            <td>Adress:</td>
            <td>${properties.address}</td>
            <td>Lessee Name:</td>
            <td>${properties.lesse_name}</td>
        </tr>
        <tr>
            <td>Land Type:</td>
            <td>${properties.land_type}</td>
            <td>Lease Tenure:</td>
            <td>${properties.lease_tenure}</td>
        </tr>
    `);

        $("#floating-table").show();
      });
    },
  });
  // function toggleLabels() {
  //     var currentZoom = map.getZoom();
  //     if (currentZoom > 16) {
  //         map.addLayer(propertyLabeler);
  //     } else {
  //         map.removeLayer(propertyLabeler);
  //     }
  // }

  // map.on('zoomend', toggleLabels);
  // toggleLabels();

  function toggleLabels() {
    var currentZoom = map.getZoom();
    if (map.hasLayer(property) && currentZoom > 19) {
      map.addLayer(propertyLabeler);
    } else {
      map.removeLayer(propertyLabeler);
    }
  }

  map.on("zoomend", toggleLabels);
  toggleLabels();

  // Add event listeners for layer add/remove events
  map.on("layeradd", function (e) {
    if (e.layer === property) {
      toggleLabels();
    }
  });
  map.on("layerremove", function (e) {
    if (e.layer === property) {
      map.removeLayer(propertyLabeler);
    }
  });

  // Add event listeners for layer add/remove events
  map.on("layeradd", function (e) {
    if (e.layer === property) {
      toggleLabels();
    }
  });
  map.on("layerremove", function (e) {
    if (e.layer === property) {
      map.removeLayer(propertyLabeler);
    }
  });

  var property_v = L.geoJSON(vacant, {
    style: {
      color: "Red",
      weight: 2,
      opacity: 1,
      fillOpacity: 0.3,
    },

    onEachFeature: function (feature, layer) {
      var tooltipContent = `
    <div>
        <strong>Vacant Land Name:</strong> ${feature.properties.Name}<br>
        <strong>Area (Sqmts):</strong> ${feature.properties.Area.toFixed(2)}
    </div>
`;

      layer.bindTooltip(tooltipContent);

      // Create a marker for labeling
      // var label = L.marker(layer.getBounds().getCenter(), {
      //     icon: L.divIcon({
      //         className: 'label-icon',
      //         html: `
      //             <div>
      //                 <strong>${feature.properties.Name}</strong><br>
      //                 Area (Sqmts): ${feature.properties.Area.toFixed(2)}
      //             </div>
      //         `,
      //         iconSize: [150, 40], // Adjust as needed
      //         iconAnchor: [75, 20] // Position of the label relative to its center
      //     })
      // }).addTo(map);

      // Handle click event
      layer.on("click", function () {
        var properties = feature.properties;
        $("#property-table").empty();
        $("#property-table").append(`
        <tr>
            <td>Vacant Land Name:</td>
            <td>${properties.Name}</td>
            <td>Area (Sqmts):</td>
            <td>${properties.Area.toFixed(2)}</td>
        </tr>
    `);
        $("#floating-table").show();
      });
    },
  }).addTo(map);

  var baseMaps = {
    "Google Satellite": google,
    "Google Street": googlestreet,
  };
  var overlayMaps = {
    Properties: property,
    "Vacant Land": property_v,
  };

  var controlLayers = L.control
    .layers(baseMaps, overlayMaps, {
      position: "bottomleft",
      collapsed: true,
    })
    .addTo(map);

  var legend = L.control
    .Legend({
      position: "bottomright",
      title: "Legend",
      collapsed: false,
      symbolWidth: 24,
      opacity: 0.8,
      legends: [
        {
          label: "Selection",
          type: "image",
          url: "assets/MIS_V3/Logo/Green.png",
        },
        {
          label: "Lease Hold",
          type: "image",
          url: "assets/MIS_V3/Logo/Yellow.png",
        },
        {
          label: "Free Hold",
          type: "image",
          url: "assets/MIS_V3/Logo/Blue.png",
        },
        {
          label: "Vacant Land",
          type: "image",
          url: "assets/MIS_V3/Logo/Red.png",
        },
      ],
    })
    .addTo(map);

  // var vacantLandNames = [];
  // property_v.eachLayer(function (layer) {
  //     vacantLandNames.push(layer.feature.properties.Name);
  // });

  // $('#propertyId').autocomplete({
  //     source: vacantLandNames,
  //     select: function(event, ui) {
  //         $('#propertyId').val(ui.item.value);
  //         $('#searchForm').submit();
  //         return false;
  //     }
  // });

  // var propertyNames = [];
  // property.eachLayer(function (layer) {
  //     propertyNames.push(layer.feature.properties.old_propert_id);
  // });

  // $('#propertyId').autocomplete({
  //     source: function(request, response) {
  //         var filteredNames = propertyNames.filter(function(name) {
  //             return name.toLowerCase().indexOf(request.term.toLowerCase()) !== -1;
  //         });
  //         response(filteredNames.slice(0, 3));
  //     },
  //     select: function(event, ui) {
  //         $('#propertyId').val(ui.item.value);
  //         $('#searchForm').submit();
  //         return false;
  //     }
  // });

  var vacantLandNames = [];
  property_v.eachLayer(function (layer) {
    vacantLandNames.push(layer.feature.properties.Name);
  });

  var propertyNames = [];
  property.eachLayer(function (layer) {
    propertyNames.push(layer.feature.properties.old_propert_id);
  });

  // Combine both arrays
  var combinedNames = vacantLandNames.concat(propertyNames);

  $("#propertyId").autocomplete({
    source: function (request, response) {
      var filteredNames = combinedNames.filter(function (name) {
        return name.toLowerCase().indexOf(request.term.toLowerCase()) !== -1;
      });
      response(filteredNames.slice(0, 3));
    },
    select: function (event, ui) {
      $("#propertyId").val(ui.item.value);
      $("#searchForm").submit();
      return false;
    },
  });

  // Enter Key shortcut code
  $("#propertyId").on("keypress", function (event) {
    if (event.which === 13) {
      event.preventDefault();
      $("#searchForm").submit();
    }
  });

  //  floating table
  $("#floating-table").draggable();

  // Form submission handler
  $("#searchForm").submit(function (event) {
    event.preventDefault();

    // Get the property ID from the form(Debobrata Sadhukhan)
    var propertyId = $("#propertyId").val().trim();
    var foundProperty = false;

    $("#property-table").empty();
    $("#floating-table").hide();
    // Clear the input field value
    $("#propertyId").val("");

    property.eachLayer(function (layer) {
      var properties = layer.feature.properties;

      if (
        properties.old_propert_id === propertyId ||
        properties.unique_propert_id === propertyId
      ) {
        $("#property-table").append(`
        <tr>
            <td>Property No:</td>
            <td>${properties.old_propert_id}</td>
            <td>Unique Property ID:</td>
            <td>${properties.unique_propert_id}</td>
        </tr>
        <tr>
            <td>Locality Name:</td>
            <td>${properties.loacalityn}</td>
            <td>Status:</td>
            <td>${properties.status}</td>
        </tr>
        <tr>
            <td>Land Use:</td>
            <td>${properties.land_use}</td>
            <td>Area (Sqmts):</td>
            <td>${properties.area_in_sqm}</td>
        </tr>
        <tr>
            <td>Adress:</td>
            <td>${properties.address}</td>
            <td>Lessee Name:</td>
            <td>${properties.lesse_name}</td>
        </tr>
        <tr>
            <td>Land Type:</td>
            <td>${properties.land_type}</td>
            <td>Lease Tenure:</td>
            <td>${properties.lease_tenure}</td>
        </tr>
    `);

        // Show floating table
        $("#floating-table").show();

        foundProperty = true;

        // Zoom to the layer's bounds
        map.fitBounds(layer.getBounds());

        // Remove highlighting from all layers
        property.resetStyle();

        // Highlight the layer found
        layer.setStyle({
          color: "green",
          fillOpacity: 0.5,
        });
      }
    });

    // Add an event listener to the "Delete Location Marker" button
    document.getElementById("trash").addEventListener("click", function () {
      property.resetStyle();
    });
    // Search in the property_v layer(Debobrata Sadhukhan)
    property_v.eachLayer(function (layer) {
      var properties = layer.feature.properties;

      if (properties.Name === propertyId || properties.Area === propertyId) {
        $("#property-table").append(`
        <tr>
            <td>Vacant Land Name:</td>
            <td>${properties.Name}</td>
            <td>Area (Sqmts):</td>
            <td>${properties.Area.toFixed(2)}</td>
    `);

        $("#floating-table").show();

        foundProperty = true;

        map.fitBounds(layer.getBounds());

        property_v.resetStyle();

        layer.setStyle({
          color: "green",
          fillOpacity: 0.5,
        });
      }
    });

    if (!foundProperty) {
      alert("Property not found!");
    }
  });

  // Close button handler(Deb)
  $("#closeFloatingTable").click(function () {
    $("#floating-table").hide();
  });

  // Add an event listener to the "Delete Location Marker" button
  document.getElementById("trash").addEventListener("click", function () {
    $("#floating-table").hide();
  });

  // Add an event listener to the "Delete Location Marker" button
  document.getElementById("trash").addEventListener("click", function () {
    property_v.resetStyle();
  });

  var iconLayersControl = new L.Control.IconLayers(
    [
      {
        title: "Map",
        layer: googlestreet,
        icon: "/assets/MIS_V3/Logo/street.png",
      },
      {
        title: "Satellite",
        layer: google,
        icon: "assets/MIS_V3/Logo/sattelite.png",
      },
    ],
    {
      position: "bottomright",
      maxLayersInRow: 20,
    }
  );

  iconLayersControl.addTo(map);

  iconLayersControl.on("activelayerchange", function (e) {
    console.log("layer switched", e.layer);
    // map.setZoom(20);
  });

  // Extend the L.Control.Measure class
  L.Control.Measure.include({
    // Override the _setCaptureMarkerIcon function
    _setCaptureMarkerIcon: function () {
      // Disable autopan
      this._captureMarker.options.autoPanOnFocus = false;

      // Default function
      this._captureMarker.setIcon(
        L.divIcon({
          iconSize: this._map.getSize().multiplyBy(2),
        })
      );
    },
  });

  // Measurement
  var measure = L.control
    .measure({
      primaryLengthUnit: "kilometers",
      secondaryLengthUnit: "meters",
      primaryAreaUnit: "hectares",
      secondaryAreaUnit: "acres",
      position: "topleft",
      crs: L.CRS.EPSG3857,
    })
    .addTo(map);

  // Download PDF button event listener
  document.getElementById("downloadPDF").addEventListener("click", function () {
    downloadPDF();
  });

  //
  async function downloadPDF() {
    const { jsPDF } = window.jspdf;
    if (!jsPDF) {
      console.error("jsPDF is not loaded correctly.");
      return;
    }

    var doc = new jsPDF("p", "pt", "letter");

    // Calculate watermark dimensions
    const watermarkText = "Land and Development Authority";
    const fontSize = 5;
    const opacity = 0.03; // Adjust opacity as needed (very light)
    const textWidth =
      (doc.getStringUnitWidth(watermarkText) * fontSize) /
      doc.internal.scaleFactor;
    const textHeight = fontSize / doc.internal.scaleFactor;
    const pageWidth = doc.internal.pageSize.width;
    const pageHeight = doc.internal.pageSize.height;

    const numCols = Math.ceil(pageWidth / textWidth);
    const numRows = Math.ceil(pageHeight / textHeight);

    // Add watermark on every page in a block-wise pattern
    for (let col = 0; col < numCols; col++) {
      for (let row = 0; row < numRows; row++) {
        const x = col * textWidth - (row % 2 === 0 ? 0 : textWidth / 2);
        const y = row * textHeight;

        // Set semi-transparent fill color for watermark
        doc.setFillColor(255, 255, 255); // Light gray color
        doc.rect(x, y, textWidth, textHeight, "F"); // Draw a rectangle as background

        // Set watermark text properties
        doc.setFontSize(fontSize);
        doc.setTextColor(244, 244, 244); // Very light gray color (almost invisible)
        doc.setFont("helvetica", "italic");

        // Write watermark text
        doc.text(watermarkText, x, y, {
          angle: 0, // No rotation
          opacity: opacity, // Adjust opacity
        });
      }
    }

    // Add the logo
    const logoBase64 = logo;
    const aspectRatio = 480 / 520;
    const desiredWidth = 100;
    const desiredHeight = desiredWidth / aspectRatio;
    doc.addImage(logoBase64, "PNG", 260, 45, desiredWidth, desiredHeight);

    // Add the header text with improved styling
    doc.setFontSize(16);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 102, 204);
    doc.text(
      "Land and Development Office",
      doc.internal.pageSize.width / 2,
      170,
      { align: "center" }
    );

    // Add the header text with improved styling
    doc.setFontSize(16);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 102, 204);
    doc.text(
      "Ministry of Housing and Urban Affairs",
      doc.internal.pageSize.width / 2,
      190,
      { align: "center" }
    );

    doc.setFontSize(11);
    doc.setFont("helvetica", "normal");
    doc.setTextColor(0, 0, 0); // Set color to black
    doc.text(
      "Address: Gate-4, A Wing, 6th floor, Moulana Azad Road, Nirman Bhawan, New Delhi, Delhi 110011",
      doc.internal.pageSize.width / 2,
      208,
      { align: "center" }
    );

    doc.setFontSize(14);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 102, 204);
    doc.text("Property/Plot Details", doc.internal.pageSize.width / 2, 230, {
      align: "center",
    });

    // Add the portal URL to the left-hand side
    const portalUrl =
      "https://dfwgnctd.maps.arcgis.com/apps/instant/3dviewer/index.html?appid=7292401d0ef943b584440df376f0b3ee";
    doc.setFontSize(4);
    doc.setFont("helvetica", "normal");
    doc.setTextColor(0, 0, 0);
    doc.text(portalUrl, 60, 70, { align: "left" });

    // Generate a unique code
    const uniqueCode = Math.floor(Math.random() * 1000000)
      .toString()
      .padStart(6, "0");

    // Add the current date to the top right corner
    const date = new Date();
    const formattedDate = date.toLocaleDateString();
    doc.setFontSize(10);
    doc.setFont("helvetica", "normal");
    doc.setTextColor(0, 0, 0);
    doc.text(`Date: ${formattedDate}`, doc.internal.pageSize.width - 60, 60, {
      align: "right",
    });

    // Add the unique code below the date
    doc.text(`Issue No: ${uniqueCode}`, doc.internal.pageSize.width - 60, 80, {
      align: "right",
    });

    // number of columns in the table
    const table = document.getElementById("property-table");
    const numColumns = table.rows[0].cells.length;

    // Calculate the width of each column dynamically
    const margin = 30;
    const tableWidth = pageWidth - 2.8 * margin;
    const columnWidth = tableWidth / numColumns;

    // Prepare column styles
    let columnStyles = {};
    for (let i = 0; i < numColumns; i++) {
      columnStyles[i] = { cellWidth: columnWidth, overflow: "linebreak" };
    }

    // Add the table
    doc.autoTable({
      html: "#property-table",
      startY: 240,
      theme: "striped",
      styles: {
        fontSize: 10,
        cellPadding: 10,
        overflow: "linebreak",
        fontStyle: "bold",
        lineColor: [0, 0, 0],
        lineWidth: 0.5,
        halign: "justify",
      },
      headStyles: {
        fillColor: [0, 102, 204],
        textColor: [255, 255, 255],
      },
      alternateRowStyles: {
        fillColor: [238, 238, 228],
      },
      columnStyles: columnStyles,
      margin: { top: 50 },
      pageBreak: "auto",
      tableWidth: "wrap",
    });

    // Generate the QR code
    const propertyId = $("#propertyId").val().trim();
    const qrCodeUrl = await new Promise((resolve) => {
      const qrCodeElement = document.createElement("div");
      new QRCode(qrCodeElement, {
        text: `https://dfwgnctd.maps.arcgis.com/apps/instant/3dviewer/index.html?appid=7292401d0ef943b584440df376f0b3ee&propertyId=${propertyId}`, // Fixed template string for QR code URL
        width: 100,
        height: 100,
        correctLevel: QRCode.CorrectLevel.H,
      });
      const qrCodeImg = qrCodeElement.querySelector("img");
      qrCodeImg.onload = () => resolve(qrCodeImg.src);
    });

    // Add the QR code
    const qrCodeX = (pageWidth - 100) / 2;
    const tableEndY = doc.lastAutoTable.finalY;
    const qrCodeY = tableEndY + 20;
    doc.addImage(qrCodeUrl, "PNG", qrCodeX, qrCodeY, 100, 100);

    // // Add the stamp logo after the QR code
    // const stampLogoBase64 = stamp; // Replace with your stamp logo base64 string
    // const stampLogoWidth = 50; // Adjust the width of the stamp logo as needed
    // const stampLogoHeight = 50; // Adjust the height of the stamp logo as needed
    // const stampLogoX = pageWidth - 60 - stampLogoWidth; // Position the stamp logo on the right side
    // const stampLogoY = qrCodeY + 120; // Adjust the Y position of the stamp logo as needed

    // doc.addImage(stampLogoBase64, 'PNG', stampLogoX, stampLogoY, stampLogoWidth, stampLogoHeight);

    // Add the footer
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 0, 0);
    doc.text(
      "Authorised Signatory",
      doc.internal.pageSize.width / 2,
      pageHeight - 90,
      { align: "center" }
    );

    doc.setFontSize(10);
    doc.setFont("helvetica", "normal");
    doc.text("Note:", 45, pageHeight - 80);

    doc.setFontSize(8);
    doc.text(
      "(1) This is a digitally signed document only for generic information",
      45,
      pageHeight - 70
    );

    const noteText =
      "(2) The information provided is as per updation made by the concerned division based on available records with them. It does not have any legal authenticity till certified by the Competent Authority";
    const wrappedNoteText = doc.splitTextToSize(noteText, 500);

    doc.text(wrappedNoteText, 45, pageHeight - 60);

    // Add a border
    const contentWidth = pageWidth - 2 * margin;
    const contentHeight = pageHeight - 2 * margin;
    doc.setDrawColor(165, 42, 42); // Brown color
    doc.setLineWidth(3); // Thickness

    doc.rect(margin, margin, contentWidth, contentHeight);

    doc.setDrawColor(0);
    doc.setLineWidth(0.5);

    //     // Store the unique code and date in JSON
    //     const jsonData = {
    //         uniqueCode: uniqueCode,
    //         date: formattedDate
    //     };
    //     console.log(jsonData); // You can store this JSON object as needed

    // Save the PDF
    doc.save("property_details.pdf");
  }

  // MAP Print
  var customActionToPrint = function (context, mode) {
    return function () {
      context._printMode(mode);
    };
  };

  var printControl = L.control.browserPrint({
    title: "Land and Development Office",
    documentTitle: "Property Details",
    closePopupsOnPrint: false,
    cancelWithEsc: false,
    contentSelector:
      "[leaflet-browser-print-content], #header, #map, [leaflet-browser-print-pages]", // Include header content selector
    pagesSelector: "[leaflet-browser-print-pages]",
    printModes: [
      L.BrowserPrint.Mode.Landscape(),
      L.BrowserPrint.Mode.Portrait(),
      L.BrowserPrint.Mode.Custom("A3", {
        title: "Select area",
        action: customActionToPrint,
        pageSize: "A3",
        orientation: "Landscape",
      }),
    ],
  });

  // Adding the print control to your map instance
  printControl.addTo(map); // Assuming `map` is your Leaflet map instance

  // Ensure the content selector includes all necessary elements
  var updateContentSelector = function () {
    var header = document.getElementById("header");
    if (header) {
      header.setAttribute("leaflet-browser-print-content", "true");
    }
  };
  updateContentSelector();
  // L.control.browserPrint({
  // contentSelector: "[leaflet-browser-print-content], #header, #map",
  // printModes: [ L.BrowserPrint.Mode.Custom("A2", {
  //                 title: "Select area",
  //                 pageSize: "A0",
  //                 orientation: 'Landscape'
  //             })
  //         ]}).addTo(map);

  // var printControl = L.control.browserPrint({

  // // printLayer: printLayer,
  // closePopupsOnPrint: false,
  // contentSelector: "[leaflet-browser-print-content], #header, #map ",
  // // contentSelector: "[leaflet-browser-print-content]",
  // pagesSelector: "[leaflet-browser-print-pages]",
  // printModes: [
  // L.BrowserPrint.Mode.Landscape("Tabloid", { title: "Tabloid VIEW" }),
  // L.BrowserPrint.Mode.Landscape(),
  // "Portrait",
  // L.BrowserPrint.Mode.Custom("A4", { title: "Select area", action: customActionToPrint }),
  // L.BrowserPrint.Mode.Auto("A4", { title: "Auto" })
  // ],
  // manualMode: false,
  // cancelWithEsc: true,
  // printFunction: window.print,
  // debug: false,
  // enableZoom: false,
  // documentTitle:'Property Card Details',

  // }).addTo(map);
});

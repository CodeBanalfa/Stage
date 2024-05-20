<?php
/*
Template Name: Polygon Page
*/
get_header(); // Inclure l'en-tête du thème
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Ajouter FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <title>polygon</title>
</head>

<body>
    <!-- Div pour afficher la carte -->
    <div id="map" style="height: 400px;"></div>
    <!-- Div pour afficher l'adresse -->
    <div id="info">
        <label for="surface-input">Surface:</label>
        <span id="surface-area">0 m²</span>
    </div>
    <button id="clearDrawing">Clear Drawing</button>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYfENzzKetiOcxrv7ucMPvPMocVziUlp8&libraries=drawing,geometry,places&callback=initMap"
        async defer></script>
    <script>
    let drawingManager;
    let polygon;

    function initMap() {
        const address = new URLSearchParams(window.location.search).get('address');
        if (!address) {
            alert("Adresse non fournie.");
            return;
        }

        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            address: address
        }, (results, status) => {
            if (status === "OK") {
                const map = new google.maps.Map(document.getElementById("map"), {
                    center: results[0].geometry.location,
                    zoom: 15,
                    mapTypeId: "satellite",
                });

                drawingManager = new google.maps.drawing.DrawingManager({
                    drawingMode: google.maps.drawing.OverlayType.POLYGON,
                    drawingControl: true,
                    drawingControlOptions: {
                        position: google.maps.ControlPosition.TOP_CENTER,
                        drawingModes: ["polygon"],
                    },
                    polygonOptions: {
                        fillColor: "blue",
                        strokeColor: "blue",
                        editable: true,
                        draggable: true,
                    },
                });

                drawingManager.setMap(map);

                const surfaceAreaElement = document.getElementById("surface-area");

                google.maps.event.addListener(drawingManager, "polygoncomplete", (poly) => {
                    if (poly.getPath().getLength() > 5) {
                        alert("Vous ne pouvez créer que des polygones avec jusqu'à 5 points.");
                        poly.setMap(null);
                        return;
                    }

                    polygon = poly;
                    const area = google.maps.geometry.spherical.computeArea(polygon.getPath());
                    surfaceAreaElement.textContent = `${area.toFixed(2)} m²`;
                });

                var input = document.getElementById("pac-input");
                var searchBox = new google.maps.places.SearchBox(input);

                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                map.addListener("bounds_changed", () => {
                    searchBox.setBounds(map.getBounds());
                });

                searchBox.addListener("places_changed", () => {
                    const places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    markers.forEach((marker) => marker.setMap(null));
                    markers = [];

                    const bounds = new google.maps.LatLngBounds();

                    places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
                            return;
                        }

                        markers.push(
                            new google.maps.Marker({
                                map,
                                title: place.name,
                                position: place.geometry.location,
                            })
                        );

                        if (place.geometry.viewport) {
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });

                    map.fitBounds(bounds);
                });

                document.getElementById("clearDrawing").addEventListener("click", function() {
                    drawingManager.setDrawingMode(null);
                    if (polygon) {
                        polygon.setMap(null);
                    }
                    surfaceAreaElement.textContent = "0 m²";
                });
            } else {
                alert("La géocodification n'a pas réussi pour la raison suivante : " + status);
            }
        });
    }

    if (typeof initMap === "function") {
        initMap();
    } else {
        console.error("initMap n'est pas défini.");
    }
    </script>

</body>

</html>
<?php get_footer(); // Inclure le pied de page du thème ?>
function initMap() {
  const initialPosition = { lat: 49.2502, lng: 4.0319 }; // Coordonnées approximatives du nord de la France

  // Créer la carte Google Maps
  const map = new google.maps.Map(document.getElementById("map"), {
    center: initialPosition,
    zoom: 7,
  });

  // Configurer le DrawingManager pour dessiner des polygones
  const drawingManager = new google.maps.drawing.DrawingManager({
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

  // Événement déclenché lorsqu'un polygone est complété
  google.maps.event.addListener(
    drawingManager,
    "polygoncomplete",
    (polygon) => {
      // Calculer la surface du polygone
      const area = google.maps.geometry.spherical.computeArea(
        polygon.getPath()
      );

      // Afficher la surface dans l'élément "info"
      const info = document.getElementById("info");
      info.textContent = `Surface : ${area.toFixed(2)} m²`;
    }
  );  function redirectToSurface() {
    const input = document.getElementById("pac-input").value;
    const encodedAddress = encodeURIComponent(input);
    window.location.href = "surface.html?address=" + encodedAddress;
}
}

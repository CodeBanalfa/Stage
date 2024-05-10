function initMap() {
  const initialPosition = { lat: 48.8566, lng: 2.3522 };

  // Créer la carte Google Maps
  const map = new google.maps.Map(document.getElementById("map"), {
    center: initialPosition,
    zoom: 12,
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
  );
}

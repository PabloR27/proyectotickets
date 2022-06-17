let map;

function initMap() {
  let ubicacion={ lat: 37.37605306763572, lng: -5.967670991652086 };
  map = new google.maps.Map(document.getElementById("map"), {
    center: ubicacion,
    mapId: "6101933ca1311e51",
    zoom: 17,
  });
  const marcador = new google.maps.Marker({
    position: ubicacion,
    map: map,
  });
}
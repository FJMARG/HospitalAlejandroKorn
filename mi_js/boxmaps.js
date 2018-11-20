    mapboxgl.accessToken = 'pk.eyJ1IjoicHJ1c3NvMjUiLCJhIjoiY2pvbjgzazJ2MHM3dTNxbzV6MHowYXZ1bCJ9.1rAOSL_0iJrGtCl4rq6tHw';
    var map = new mapboxgl.Map({
    container: 'map', // container id
    style: 'mapbox://styles/mapbox/streets-v9',
    center: [-58.3772300, -34.6083], // starting position
    zoom: 8 // starting zoom
    });

    // Add zoom and rotation controls to the map.
    map.addControl(new mapboxgl.NavigationControl());
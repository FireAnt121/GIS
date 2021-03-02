jQuery(document).ready(function($){
var mymap = L.map('mapid',{
    maxZoom:17,scrollWheelZoom: false,minZoom:8,zoomDelta: 2,zoomSnap:0
}).setView([28.3949, 84.1240], 8);

// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',

// }).addTo(mymap);
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
}).addTo(mymap);
// var marker = L.marker([51.5, -0.09]).addTo(mymap);

mymap.bounds = [],
// Now set your maxbounds, see the leaflet documentation for reference.
    mymap.setMaxBounds([
      [32.324276,77.573942],
      [23.563987, 90.194668]
    ]);

// var polygon = L.polygon([
//     [30.186683,81.038882],
//     [29.741725, 80.376462],
//     [28.806174, 80.103920],
//     [29.329509, 81.544090]
// ]).addTo(mymap);

// var p7 = ["67-Bajura","68-Bajhang","69-Achham","70-Doti","71-Kailali","72-Kanchanpur","73-Dadeldhura","74-Baitadi","75-Darchula"];
// var bajura ="";
// $.each(p7,function(key,val){
//     $.getJSON('shapes/Province-7/'+ val +'.json',function(json){
//         bajura = bajura.concat(json);
//         console.log(json);
//     });
// });

var anim_done = true;
var openMap,innerMap;
var url = myScript.pluginsUrl + '/nepal-mapping/admin/json/country.json';
$.getJSON(url, function (geojson) {
    L.geoJson(geojson, {
      onEachFeature: onEachFeature,
      style: setStyle
    }).addTo(mymap);
  });

  function setStyle(feature){
    return {
        fillColor: feature.properties.color,
        fillOpacity: 0.7,
      };
  }
  function onSuperInner(feature,layer){
    layer.bindTooltip('<p>'+feature.properties.name+'</p>',{permanent:true}).addTo(mymap);
    layer.on('click',function(e){
      mymap.flyTo(layer.getBounds().getCenter(), 12,{animate:true,duration:.5});
      $('#mapid-content').empty();
      $('#mapid-content').show();
      $('#mapid-content').append(organizeMunContent(feature.properties.ID,feature.properties.name,feature.properties.nepali,feature.properties.T_W,feature.properties.T_Pop,feature.properties.T_Area));
    
    });
    layer.on('mouseover', function () {
      this.setStyle({
        'fillColor': '#0000ff'
      });
    });
    layer.on('mouseout', function () {
      this.setStyle({
        'fillColor': '#000'
      });
    });
  }
 function onInnerFeature(feature,layer){
  layer.bindTooltip('<p>'+feature.properties.name+'</p>',{permanent:true}).addTo(mymap);
    layer.on('click',function(e){
        if(innerMap != undefined){
            mymap.removeLayer(innerMap);
        }
        $('#mapid-content').empty();
        $('#mapid-content').show();
        $('#mapid-content').append(organizeDisContent(feature.properties.name,feature.properties.nepali,feature.properties.T_W,feature.properties.T_M,feature.properties.T_Metro,feature.properties.T_Sub,feature.properties.T_Urban,feature.properties.T_Rural,feature.properties.T_Pop,feature.properties.T_Area));
        
        var tar = feature.properties.TARGET;
        var uri = myScript.pluginsUrl + '/nepal-mapping/admin/json/districts/'+tar+'.json';
        $.getJSON(uri,function(js){
            innerMap = L.geoJson(js,{
              onEachFeature:onSuperInner
            }).addTo(mymap);
            mymap.flyTo(innerMap.getBounds().getCenter(), 10,{animate:true,duration:.5});
        });

        // console.log(innerMap);
        // console.log(innerMap.getBounds().getCenter());
        // mymap.flyTo([feature.properties.lat,feature.properties.long], 10,{animate:true,duration:.5});
        // mymap.removeLayer(innerMap);
        // setTimeout(function(){
        //     innerMap.addTo(mymap);
        //   },600);
    });

    layer.on('mouseover', function () {
        this.setStyle({
          'fillColor': '#0000ff'
        });
      });
      layer.on('mouseout', function () {
        this.setStyle({
          'fillColor': '#000'
        });
      });
 }

  function organizeContent(name,nepali,T_D,T_M,T_Metro,T_Sub,T_Urban,T_Rural,T_W,T_Pop,T_Area){
    return "<div class='fireBoxes'><h3>"+name+"</h3>"+
            "<h4>"+nepali+"</h4><br>"+
            "<p>Districts<span>"+T_D+"</span></p>"+
            "<p>Municipalities<span>"+T_M+"</span></p>"+
            "<p>Metropolitans<span>"+T_Metro+"</span></p>"+
            "<p>Sub Metropolis<span>"+T_Sub+"</span></p>"+
            "<p>Urban<span>"+T_Urban+"</span></p>"+
            "<p>Rural<span>"+T_Rural+"</span></p>"+
            "<p>Population<span>"+T_Pop+"</span></p>"+
            "<p>Area<span>"+T_Area+"sq km</span></p></div>";
  }
  function organizeDisContent(name,nepali,T_W,T_M,T_Metro,T_Sub,T_Urban,T_Rural,T_Pop,T_Area){
    return "<div class='fireBoxes'><h3>"+name+"</h3>"+
            "<h4>"+nepali+"</h4><br>"+
            "<p>Wards<span>"+T_W+"</span></p>"+
            "<p>Municipalities<span>"+T_M+"</span></p>"+
            "<p>Metropolitans<span>"+T_Metro+"</span></p>"+
            "<p>Sub Metropolis<span>"+T_Sub+"</span></p>"+
            "<p>Urban<span>"+T_Urban+"</span></p>"+
            "<p>Rural<span>"+T_Rural+"</span></p>"+
            "<p>Population<span>"+T_Pop+"</span></p>"+
            "<p>Area<span>"+T_Area+"sq km</span></p></div>";
  } 
  function organizeMunContent(ID,name,nepali,T_W,T_Pop,T_Area){
    var res = "<div class='fireBoxes'><h3>"+name+"</h3>"+
            "<h4>"+nepali+"</h4><br>"+
            "<p>Wards<span>"+T_W+"</span></p>"+
            "<p>Population<span>"+T_Pop+"</span></p>"+
            "<p>Area<span>"+T_Area+"sq km</span></p>";
    res += (T_W > 0)? "<p id='VIEWwards' data-id='"+ID+"' >View all wards</p></div>" : "</div>";
    return res;
  } 

  function onEachFeature(feature,layer) {
    // L.popup({autoClose:false,closeButton:false,closeOnClick:false})
    // .setLatLng([feature.properties.lat,feature.properties.long])
    // .setContent('<p>'+feature.properties.TARGET+'</p>')
    // .openOn(mymap);

    // layer.bindPopup(feature.properties.TARGET);
    //L.marker([feature.properties.lat,feature.properties.long]).addTo(mymap);
    // layer.bindTooltip(feature.properties.TARGET).addTo(mymap);
    // layer.openTooltip();
    layer.bindTooltip('<p>'+feature.properties.name+'</p>',{permanent:true}).addTo(mymap);

    layer.on('click',function(e){
        if(anim_done){
            anim_done = false;
        if(openMap != undefined){
            mymap.removeLayer(openMap);
        }
        if(innerMap != undefined){
          mymap.removeLayer(innerMap);
        }
        $('#mapid-content').empty();
        $('#mapid-content').show();
        $('#mapid-content').append(organizeContent(feature.properties.name,feature.properties.nepali,feature.properties.T_D,feature.properties.T_M,feature.properties.T_Metro,feature.properties.T_Sub,feature.properties.T_Urban,feature.properties.T_Rural,2,feature.properties.T_Pop,feature.properties.T_Area));
        var tar = feature.properties.TARGET;
        var ur = myScript.pluginsUrl + '/nepal-mapping/admin/json/provinces/'+tar+'.json';
          $.getJSON(ur,function (js){
              openMap = L.geoJson(js,{
                  onEachFeature: onInnerFeature
              }).addTo(mymap);
              mymap.flyTo([feature.properties.lat,feature.properties.long], 9,{animate:true,duration:.5});
          });
           setTimeout(function(){
             openMap.addTo(mymap);
             anim_done = true;
           },600);
        }
          // L.geoJson(bajura).addTo(mymap);
    });
    layer.on('mouseover', function () {
        this.setStyle({
          'fillColor': '#0000ff'
        });
      });
      layer.on('mouseout', function () {
        this.setStyle({
          'fillColor': feature.properties.color
        });
      });
  }
// $.each(layers,function(index,layer){
//     console.log(layer);
//     layer.on('click',function(e){
//         var tar = features[index].properties.TARGET;
//         console.log("tar");
//           mymap.flyTo([features[index].properties.lat,features[index].properties.long], 8);
//           console.log(tar);
//           $.getJSON(tar + '.json',function (js){
//               L.geoJson(js).addTo(mymap);
//           });
//           // L.geoJson(bajura).addTo(mymap);
//     });
// });

//L.geoJSON(nepal).addTo(mymap);
});
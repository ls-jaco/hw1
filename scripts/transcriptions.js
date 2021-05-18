// OAuth credentials --- NON SICURO!
const client_id = 'a3d490c5fd084afe820e482860e3c59e';
const client_secret = '1bee37a7a4f3468f9e0c1f18ec16ad27';

// Dichiara variabile token
let token;

// All'apertura della pagina, richiediamo il token
fetch("https://accounts.spotify.com/api/token",
  {
  method: "post",
  body: 'grant_type=client_credentials',
  headers:
  {
    'Content-Type': 'application/x-www-form-urlencoded',
    'Authorization': 'Basic ' + btoa(client_id + ':' + client_secret)
  }

  }
).then(onTokenResponse).then(onTokenJson).then(searchItemFromSpotify);


function searchItemFromSpotify(){

album.forEach((element, indiceProva) => {

  let copiaTitolo = element.titolo;

  let titoloEncoded = encodeURI(copiaTitolo);
  titoloEncoded = titoloEncoded.toLowerCase();

  console.log(titoloEncoded);

  let urlAlbum = "https://api.spotify.com/v1/search?q=" + titoloEncoded + "&type=album&market=IT&limit=1"

  console.log(urlAlbum)
    fetch(urlAlbum,
    {
    headers:
      {
      'Authorization' : 'Bearer ' + token
      }
    }
    ).then(response => onResponse(response))
     .then(data => searchAlbumFromSpotify(data, indiceProva));
});
}



function searchAlbumFromSpotify(json, indiceProva){

console.log(json)
let resultSearch = json.albums.items[0].id;
console.log(resultSearch);
//esegui la richiesta
fetch("https://api.spotify.com/v1/albums/" + resultSearch + "/tracks?market=IT",
{
headers:
{
  'Authorization' : 'Bearer ' + token
}
}
).then(response => onResponse(response))
 .then(data => onJson(data, indiceProva));
}


let indice = 0;

function onJson(json, indiceProva) {
console.log(indiceProva)
let results = json.items;

const musicDiv = document.querySelector("#spotifyDivId-" + indiceProva);
indice ++;
results.forEach((element) => {


  let track_div = document.createElement("div");
  track_div.className = "track_div_class";

  let titolo_brano = document.createElement("h1");
  titolo_brano.textContent = element.name;
  track_div.appendChild(titolo_brano);

  let sound = document.createElement('audio');
  if(element.preview_url!= null){
  sound.id = 'audio-player';
  sound.controls = 'controls';
  sound.src = element.preview_url
  track_div.appendChild(sound);
  } else {
    let notFound = document.createElement("p");
    notFound.textContent = "The following preview is not avaible :(";
    track_div.appendChild(notFound);
  }

  musicDiv.appendChild(track_div);
});
}
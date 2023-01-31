let sectionHeader = document.getElementsByClassName('section');
sectionHeader[0].innerHTML += `<div id='scriptTag' style='border: 1px solid red;
    height: 80px;
    margin-top: 30px;
    overflow: scroll;'>
  </div>`;

let api_url = 'https://gorest.co.in/public/v2/users';

async function getapi(url) {

    // Storing response
    const response = await fetch(url);

    // Storing data in form of JSON
    var data = await response.json();
    console.log(data);

    show(data);
}

getapi(api_url);

function show(data) {
    let tab = '';
    for (let r of data) {
        tab += `
    <p>Name: ${r.name} </p>
    <p>Email: ${r.email}</p>
    <p>Gender: ${r.gender}</p>
    <p>Status: ${r.status}</p>`;
    }
    // Setting innerHTML as tab variable
    document.getElementById("scriptTag").innerHTML = tab;
}

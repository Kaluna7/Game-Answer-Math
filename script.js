 // Mengambil URL parameter dan menampilkan konten yg sesuai di menu
 const urlParams = new URLSearchParams(window.location.search);
 const action = urlParams.get('action');

 // take content element
 const content = document.getElementById('content');

 // Function untuk memuat konten sesuai pda menu
 function loadContent(page) {
     const xhr = new XMLHttpRequest();
     xhr.open('GET', page, true);
     xhr.onload = function () {
         if (xhr.status === 200) {
             content.innerHTML = xhr.responseText;
         }
     };
     xhr.send();
 }

 //  memuat content sesuai action
 if (action === 'register') {
     loadContent('register.html');
 } else if (action === 'play') {
     loadContent('play.html');
 } else if (action === 'record') {
     loadContent('record.html');
 }
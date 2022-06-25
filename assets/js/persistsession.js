onmessage = e => {
    const url = e.data;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //postMessage(this.responseText);
            console.log(this.responseText);
            const reply = setTimeout(() => postMessage("Polo!"), 3000);
            console.log(`[From Main]: ${url}`);
       }
    };
    xhttp.open("POST", url, true);
    xhttp.send();
    
    
};
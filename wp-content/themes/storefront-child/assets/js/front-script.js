window.onload = function() {
    var divs = document.getElementById("small_thumbs").getElementsByTagName("div");
    for (let i = 0; i < divs.length; i++) {
        const element = divs[i];
        element.addEventListener('click', event => {
            var main = document.getElementsByClassName("main_img")[0];
            main.style.cssText = event.target.style.cssText;
        });
    }
  };
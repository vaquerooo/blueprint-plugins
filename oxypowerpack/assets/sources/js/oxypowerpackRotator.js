var oxyPowerPackTextRotator = function(el, rotateArray, period) {
    this.rotateArray = rotateArray;
    this.el = el;
    this.loopNum = 0;
    this.period = parseInt(period) || 2000;
    this.txt = '';
    this.tick();
    this.deleting = false;
};

oxyPowerPackTextRotator.prototype.tick = function() {
    var i = this.loopNum % this.rotateArray.length;
    var fullTxt = this.rotateArray[i];

    if (this.deleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
    } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
    }

    this.el.innerHTML = this.txt;

    var that = this;
    var delta = 200 - Math.random() * 100;

    if (this.deleting) { delta /= 2; }

    if (!this.deleting && this.txt === fullTxt) {
        delta = this.period;
        this.deleting = true;
    } else if (this.deleting && this.txt === '') {
        this.deleting = false;
        this.loopNum++;
        delta = 500;
    }

    setTimeout(function() {
        that.tick();
    }, delta);
};

window.onload = function() {
    var elements = document.querySelectorAll('span[data-oxypowerpack-rotator]');
    var rotateSpansExist = false;
    for (var i=0; i<elements.length; i++) {
        var rotateArray = elements[i].getAttribute('data-oxypowerpack-rotator');
        rotateArray = rotateArray.split('|');
        rotateArray.unshift( elements[i].innerHTML );
        var period = 1800; // hardcoded. will add a setting for this later
        if (rotateArray) {
            new oxyPowerPackTextRotator(elements[i], rotateArray, period);
            rotateSpansExist = true;
        }
    }
    if( rotateSpansExist ){
        var css = document.createElement("style");
        css.type = "text/css";
        css.innerHTML = "span[data-oxypowerpack-rotator]::after { content: '|'; animation: 1s oppblinker step-end infinite; }\n" +
            "@keyframes oppblinker {\n" +
            "    50% {\n" +
            "        opacity: 0;\n" +
            "    }\n" +
            "}";
        document.body.appendChild(css);
    }
};
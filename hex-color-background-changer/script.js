(function() {
    const button = document.querySelector('.btn-change');
    const body = document.querySelector('body');
    const hexValues = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F'];
    const hexvalue = document.querySelector('.hex-value')


    button.addEventListener('click', changeColor)
    function changeColor() {
        let hexColor = '#';
        for (let i = 0; i < 6; i++) {
            let random = Math.floor(Math.random() * hexValues.length);
            hexColor += hexValues[random];
        }
        body.style.backgroundColor = hexColor;
        hexvalue.innerHTML = hexColor;
    }
})()
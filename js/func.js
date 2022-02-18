let x, y, r;
let errorMessage = "";
const maxLength = 7;

function isNumber(input) {
    return !isNaN(parseFloat(input)) && isFinite(input);
}

function addToErrorMessage(errorDesc) {
    errorMessage += (errorDesc + "\n");
}

function hasProperLength(input) {
    return input.length <= maxLength;
}

function validateX() {
    let XButtons = document.querySelectorAll("input[name=x]");

    XButtons.forEach(function (button) {
        console.log(button.value);
        if (button.checked) {
            x = button.value;
            console.log("success");
        }
    });

    if (x === undefined) {
        addToErrorMessage("Выберите X.");
        console.log("check x");
        return false;
    }
    return true;
}

function validateY() {
    y = document.querySelector("input[id=yCoordinate]").value.replace(",", ".");
    if (y === undefined) {
        addToErrorMessage("Поле Y не заполнено");
        return false;
    }
    if (!isNumber(y)) {
        addToErrorMessage("Y должен быть числом от -3 до 3!");
        return false;
    }
    if (!hasProperLength(y)) {
        addToErrorMessage(`Длина числа должна быть не более ${maxLength} символов`);
        return false;
    }
    if (!((y > -3) && (y < 3))) {
        addToErrorMessage("Нарушена область допустимых значений Y (-3; 3)");
        return false;
    }
    return true;
}

function validateR() {
    r = document.querySelector("input[id=rCoordinate]").value.replace(",", ".");
    if (r === undefined) {
        addToErrorMessage("Поле R не заполнено");
        return false;
    }
    if (!isNumber(r)) {
        addToErrorMessage("R должен быть числом от 1 до 4!");
        return false;
    }
    if (!hasProperLength(r)) {
        addToErrorMessage(`Длина числа должна быть не более ${maxLength} символов`);
        return false;
    }
    if (!((r > 1) && (r < 4))) {
        addToErrorMessage("Нарушена область допустимых значений R (1; 4)");
        return false;
    }
    return true;
}


function submit() {
    if (validateX() & validateY() & validateR()) {
        $.get("./main.php", {
            'x': x,
            'y': y,
            'r' : r,
            'timezone': new Date().getTimezoneOffset()
        }).done(function(PHP_RESPONSE) { // do when success callback is received
            let result = JSON.parse(PHP_RESPONSE); // take array with results
            if (!result[0].isValid) {
                addToErrorMessage("Request is not valid. Try refreshing the page");
                return;
            }
            let newRow = result[0].isBlueAreaHit ? '<tr class="hit-yes">' : '<tr class="hit-no">';
            newRow += '<td>' + result[0].x + '</td>';
            newRow += '<td>' + result[0].y + '</td>';
            newRow += '<td>' + result[0].r + '</td>';
            newRow += '<td>' + result[0].userTime + '</td>';
            newRow += '<td>' + result[0].execTime + '</td>';
            newRow += '<td>' + (result[0].isBlueAreaHit ? '<h6>Да</h6>' : '<h6>Нет</h6>') + '</td>';
            $('#result-table tr:first').after(newRow);
            document.getElementById("result-table").style.backgroundColor = `rgba(250, 235, 215, ${Math.random() * 0.6 + 0.2})`;
        }).fail(function (error) {
            addToErrorMessage(error);
        });
    }

    if (!(errorMessage === "")) {
        alert(errorMessage);
        errorMessage = "";
    }
}
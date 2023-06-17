let items = [
    { word: "Better late than never", category: "Proverb" },
    { word: "Lubie placki", category: "Phrase" },
    { word: "Hello world", category: "Phrase" },
    { word: "Type Script", category: "Programming Language" },
    { word: "Java Script", category: "Programming Language" },
    { word: "Kotlin", category: "Programming Language" },
    { word: "Minecraft", category: "Game" },
    { word: "Iphone", category: "Technology" },
    { word: "Apple", category: "Fruit" }
];

let words = items[Math.floor(Math.random()*items.length)];

let category = randomItem.category;

words = words.toUpperCase();

let letters = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];

let stringLength = words.length;

let hiddenWords = "";

let countOfFails = 0;

let yesAudio = new Audio("public/sounds/yes.wav");
let noAudio = new Audio("public/sounds/no.wav");

for (let i = 0; i < stringLength; i++) {
    if (words.charAt(i) === " ") {
        hiddenWords += " ";
    } else {
        hiddenWords += "-";
    }
}

function printWords() {
    document.getElementById('board').innerHTML = hiddenWords;
}

function showLetters() {
    let alphabetContent = "";

    for (let i = 0; i <= 25; i++) {
        let element = "letter" + i;
        alphabetContent += '<div onclick="checkLetter(' + i + ')" class="letter" id="' + element + '">' + letters[i] + '</div>';
        if ((i + 1) % 7 === 0) {
            alphabetContent += '<div class="clear-both"></div>'
        }
    }
    document.getElementById('category').innerHTML = "Category: " + category;
    document.getElementById('alphabet').innerHTML = alphabetContent;

    printWords();
}

String.prototype.setChar = function (place, char) {
    if (place > this.length - 1) {
        return this.toString();
    } else {
        return this.substr(0, place) + char + this.substr(place + 1);
    }
}

function checkLetter(number) {
    let correctLetter = false;

    for (let i = 0; i < stringLength; i++) {
        if (words.charAt(i) === letters[number]) {
            hiddenWords = hiddenWords.setChar(i, letters[number]);
            correctLetter = true;
        }
    }
    let element = "letter" + number;

    if (correctLetter) {
        yesAudio.play();
        document.getElementById(element).style.background = "#003300";
        document.getElementById(element).style.color = "#00C000";
        document.getElementById(element).style.border = "3px solid #00C000";
        document.getElementById(element).style.cursor = "default";

        printWords();
    } else {
        noAudio.play();
        document.getElementById(element).style.background = "#330000";
        document.getElementById(element).style.color = "#C00000";
        document.getElementById(element).style.border = "3px solid #C00000";
        document.getElementById(element).style.cursor = "default";
        document.getElementById(element).setAttribute("onclick", ";");

        //Fails
        countOfFails++;
        let image = "public/images/image_" + countOfFails + ".png";
        document.getElementById("gallows").innerHTML = '<img src="'+ image +'" alt="" width="400"/>';
    }

    //win
    if(words == hiddenWords){
        document.getElementById("alphabet").innerHTML = "Congratulations! You guessed the proverb: " + words + '<br/><br/><span class="reset" onclick="location.reload()">Play again?</span>';
    }

    //lose
    if(countOfFails >= 9){
        document.getElementById("alphabet").innerHTML = "Game Over! Correct proverb is: " + words + '<br/><br/><span class="reset" onclick="location.reload()">Play again?</span>';
    }


}

window.onload = showLetters;
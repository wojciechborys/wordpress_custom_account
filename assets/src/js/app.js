"use strict";

(function ($) {
  $(document).ready(function () {
    App.init();
  });

  var App = {
    init: function () {
      console.log("APP INIT");
    },
  };
})(jQuery);

function addCommasToNumber(number) {
  const numberString = String(number);
  let result = "";

  let digitCount = 0;
  for (let i = numberString.length - 1; i >= 0; i--) {
    result = numberString[i] + result;
    digitCount++;

    if (digitCount % 3 === 0 && i > 0) {
      result = "," + result;
    }
  }

  return result;
}

// Get all input elements
const inputElements = document.querySelectorAll('.currency input[type="text"]');

// Iterate over each input element and add event listeners
inputElements.forEach(function (inputElement) {
  inputElement.addEventListener("input", function (event) {
    const inputNumber = event.target.value.replace(/,/g, ""); // Remove existing commas
    const formattedNumber = addCommasToNumber(inputNumber);
    event.target.value = formattedNumber;
  });
});

document.querySelector(".download_pdf").addEventListener("click", function () {
  window.print();
});
